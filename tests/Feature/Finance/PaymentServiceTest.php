<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentRecordStatus;
use App\Enums\PaymentStatus;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Role;
use App\Models\User;
use App\Services\Finance\FinanceRuleException;
use App\Services\Finance\OrderLedgerService;
use App\Services\Finance\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FIN-FR-008, FIN-FR-009 | FIN-BR-005, 006, 008, 009, 016, 019 | FIN-AT-005, 006, 008
 * Memakai RefreshDatabase (phpunit.xml memakai SQLite :memory:).
 */
class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaymentService $service;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(PaymentService::class);

        $customer = Customer::create([
            'name' => 'Budi Santoso',
            'whatsapp_number' => '6281234567890',
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'code' => 'MAN-261126-0001',
            'customer_id' => $customer->id,
            'type' => OrderType::Manual,
            'channel' => OrderChannel::WalkIn,
            'status' => OrderStatus::AwaitingPayment,
            'payment_status' => PaymentStatus::Unpaid,
            'subtotal' => 200000,
            'total' => 200000,
            'ordered_at' => now(),
        ]);
    }

    private function actor(bool $owner): User
    {
        $user = new User();
        $user->setRelation('role', (new Role())->forceFill(['name' => $owner ? Role::OWNER : 'admin']));

        return $user;
    }

    private function pay(int $amount, array $extra = []): Payment
    {
        return $this->service->record(
            $this->order,
            $amount,
            '2026-11-26',
            reference: $extra['reference'] ?? null,
            note: $extra['note'] ?? null,
            method: $extra['method'] ?? 'qris',
            submissionKey: $extra['key'] ?? null,
            actor: $this->actor(false),
        );
    }

    private function summary(): array
    {
        return app(OrderLedgerService::class)->summary($this->order->fresh());
    }

    // ---------------- Catat pembayaran ----------------

    public function test_dp_qris_50rb_dari_total_200rb_fin_at_005(): void
    {
        $payment = $this->pay(50000, ['note' => 'DP', 'reference' => 'QR-001']);

        $this->assertSame(PaymentRecordStatus::Valid, $payment->fresh()->status);
        $this->assertSame('qris', $payment->method);
        $this->assertSame('QR-001', $payment->reference);

        $summary = $this->summary();
        $this->assertSame(50000, $summary['net_payment']);
        $this->assertSame(150000, $summary['remaining']);
        $this->assertSame(PaymentStatus::PartiallyPaid, $summary['payment_status']);
        $this->assertSame(PaymentStatus::PartiallyPaid, $this->order->fresh()->payment_status);
    }

    public function test_pelunasan_membuat_status_lunas(): void
    {
        $this->pay(50000);
        $this->pay(150000, ['note' => 'Pelunasan']);

        $this->assertSame(0, $this->summary()['remaining']);
        $this->assertSame(PaymentStatus::Paid, $this->order->fresh()->payment_status);
    }

    public function test_pembayaran_melebihi_sisa_ditolak_menyebut_sisa_dan_tidak_mengubah_data_fin_at_006(): void
    {
        $this->pay(100000);

        try {
            $this->pay(120000);
            $this->fail('Pembayaran berlebih seharusnya ditolak.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Rp100.000', $e->getMessage());
        }

        $this->assertSame(1, Payment::count());
        $this->assertSame(PaymentStatus::PartiallyPaid, $this->order->fresh()->payment_status);
    }

    public function test_pembayaran_tidak_mengubah_status_pengerjaan_fin_at_008(): void
    {
        $this->pay(200000);

        $this->assertSame(OrderStatus::AwaitingPayment, $this->order->fresh()->status);
        $this->assertSame(PaymentStatus::Paid, $this->order->fresh()->payment_status);
    }

    public function test_pembayaran_pada_transaksi_dibatalkan_ditolak(): void
    {
        $this->order->update(['status' => OrderStatus::Cancelled]);

        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('dibatalkan');

        $this->pay(10000);
    }

    public function test_nominal_nol_atau_negatif_ditolak(): void
    {
        foreach ([0, -5000] as $amount) {
            try {
                $this->pay($amount);
                $this->fail('Nominal tidak valid seharusnya ditolak: '.$amount);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('lebih dari nol', $e->getMessage());
            }
        }

        $this->assertSame(0, Payment::count());
    }

    public function test_transaksi_total_nol_tidak_menerima_pembayaran(): void
    {
        $this->order->update(['subtotal' => 0, 'total' => 0]);

        $this->expectException(FinanceRuleException::class);

        $this->pay(1000);
    }

    public function test_metode_selain_qris_ditolak_fin_br_016(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('hanya QRIS');

        $this->pay(10000, ['method' => 'tunai']);
    }

    public function test_tanggal_bayar_tidak_valid_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Tanggal bayar tidak valid');

        $this->service->record($this->order, 10000, '26-11-2026');
    }

    public function test_referensi_terlalu_panjang_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->pay(10000, ['reference' => str_repeat('A', 101)]);
    }

    public function test_pengiriman_ganda_dengan_kunci_sama_ditolak_dan_hanya_satu_tercatat(): void
    {
        $this->pay(50000, ['key' => 'form-abc-123']);

        try {
            $this->pay(50000, ['key' => 'form-abc-123']);
            $this->fail('Pengiriman ganda seharusnya ditolak.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Pengiriman ganda', $e->getMessage());
        }

        $this->assertSame(1, Payment::count());
        $this->assertSame(50000, $this->summary()['net_payment']);
    }

    // ---------------- Void ----------------

    public function test_owner_void_payment_dengan_alasan_dan_angka_dihitung_ulang(): void
    {
        $payment = $this->pay(50000);

        $voided = $this->service->void($payment, 'Salah catat nominal', $this->actor(true));

        $fresh = $voided->fresh();
        $this->assertSame(PaymentRecordStatus::Void, $fresh->status);
        $this->assertSame('Salah catat nominal', $fresh->void_reason);
        $this->assertNotNull($fresh->voided_at);

        $summary = $this->summary();
        $this->assertSame(0, $summary['net_payment']);
        $this->assertSame(200000, $summary['remaining']);
        $this->assertSame(PaymentStatus::Unpaid, $this->order->fresh()->payment_status);

        $audit = AuditLog::where('action', 'payment.void')->first();
        $this->assertNotNull($audit);
        $this->assertSame('Salah catat nominal', $audit->reason);
        $this->assertSame('void', $audit->changes['status']['new']);
    }

    public function test_void_tidak_menghapus_data_pembayaran(): void
    {
        $payment = $this->pay(50000);

        $this->service->void($payment, 'Salah catat', $this->actor(true));

        $this->assertSame(1, Payment::count());
    }

    public function test_admin_tidak_boleh_void_dan_payment_tetap_valid(): void
    {
        $payment = $this->pay(50000);

        try {
            $this->service->void($payment, 'Salah catat', $this->actor(false));
            $this->fail('Admin seharusnya ditolak.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('hanya dapat dilakukan oleh Owner', $e->getMessage());
        }

        $this->assertSame(PaymentRecordStatus::Valid, $payment->fresh()->status);
        $this->assertSame(0, AuditLog::where('action', 'payment.void')->count());
    }

    public function test_void_tanpa_pengguna_ditolak(): void
    {
        $payment = $this->pay(50000);

        $this->expectException(FinanceRuleException::class);

        $this->service->void($payment, 'Salah catat', null);
    }

    public function test_void_tanpa_alasan_ditolak(): void
    {
        $payment = $this->pay(50000);

        foreach (['', '   '] as $reason) {
            try {
                $this->service->void($payment, $reason, $this->actor(true));
                $this->fail('Void tanpa alasan seharusnya ditolak.');
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('Alasan Void wajib diisi', $e->getMessage());
            }
        }

        $this->assertSame(PaymentRecordStatus::Valid, $payment->fresh()->status);
    }

    public function test_payment_yang_sudah_void_tidak_bisa_di_void_lagi(): void
    {
        $payment = $this->pay(50000);
        $this->service->void($payment, 'Salah catat', $this->actor(true));

        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('berstatus Valid');

        $this->service->void($payment, 'Void kedua', $this->actor(true));
    }

    public function test_payment_yang_sudah_punya_refund_selesai_tidak_bisa_di_void(): void
    {
        $payment = $this->pay(100000);
        Refund::create([
            'order_id' => $this->order->id,
            'payment_id' => $payment->id,
            'refund_date' => '2026-11-27',
            'amount' => 40000,
            'method' => 'transfer',
            'reason' => 'Pengurangan jumlah',
        ]);

        try {
            $this->service->void($payment, 'Salah catat', $this->actor(true));
            $this->fail('Void seharusnya ditolak karena sudah ada refund selesai.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Refund Selesai', $e->getMessage());
        }

        $this->assertSame(PaymentRecordStatus::Valid, $payment->fresh()->status);
    }

    public function test_setelah_void_sisa_tagihan_bertambah_sehingga_bisa_dibayar_ulang(): void
    {
        $payment = $this->pay(200000);
        $this->assertSame(PaymentStatus::Paid, $this->order->fresh()->payment_status);

        $this->service->void($payment, 'Dana ternyata belum masuk', $this->actor(true));
        $this->pay(200000, ['note' => 'Pelunasan setelah verifikasi ulang']);

        $this->assertSame(2, Payment::count());
        $this->assertSame(PaymentStatus::Paid, $this->order->fresh()->payment_status);
        $this->assertSame(200000, $this->summary()['net_payment']);
    }
}
