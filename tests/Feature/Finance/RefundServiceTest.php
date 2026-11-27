<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Enums\RefundStatus;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Role;
use App\Models\User;
use App\Services\Finance\FinanceRuleException;
use App\Services\Finance\OrderLedgerService;
use App\Services\Finance\PaymentLedgerService;
use App\Services\Finance\PaymentService;
use App\Services\Finance\RefundService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FIN-FR-019 | FIN-BR-005, 009, 013, 019 | FIN-AT-017, 018, 019
 * Memakai RefreshDatabase (phpunit.xml memakai SQLite :memory:).
 */
class RefundServiceTest extends TestCase
{
    use RefreshDatabase;

    private RefundService $service;

    private Order $order;

    private Payment $payment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(RefundService::class);

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
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Unpaid,
            'subtotal' => 100000,
            'total' => 100000,
            'ordered_at' => now(),
        ]);

        // Payment Valid Rp100.000 lewat service agar status pembayaran order ikut dihitung.
        $this->payment = app(PaymentService::class)->record($this->order, 100000, '2026-11-26');
    }

    private function actor(bool $owner): User
    {
        $user = new User();
        $user->setRelation('role', (new Role())->forceFill(['name' => $owner ? Role::OWNER : 'admin']));

        return $user;
    }

    private function refund(int $amount, array $extra = []): Refund
    {
        return $this->service->record(
            $extra['payment'] ?? $this->payment,
            $amount,
            '2026-11-27',
            $extra['method'] ?? 'transfer',
            $extra['reason'] ?? 'Pelanggan mengurangi jumlah pesanan',
            reference: $extra['reference'] ?? null,
            submissionKey: $extra['key'] ?? null,
            actor: $extra['actor'] ?? $this->actor(true),
        );
    }

    private function summary(): array
    {
        return app(OrderLedgerService::class)->summary($this->order->fresh());
    }

    public function test_refund_sebagian_mengurangi_pembayaran_bersih_fin_at_017(): void
    {
        $refund = $this->refund(40000);

        $this->assertSame(RefundStatus::Completed, $refund->fresh()->status);
        $this->assertSame($this->payment->id, $refund->payment_id);

        $summary = $this->summary();
        $this->assertSame(60000, $summary['net_payment']);
        $this->assertSame(40000, $summary['remaining']);
        $this->assertSame(PaymentStatus::PartiallyPaid, $this->order->fresh()->payment_status);
        $this->assertSame(PaymentLedgerService::REFUND_PARTIAL, $summary['refund_status']);
    }

    public function test_refund_penuh_mengembalikan_status_belum_dibayar_fin_at_018(): void
    {
        $this->refund(100000);

        $summary = $this->summary();
        $this->assertSame(0, $summary['net_payment']);
        $this->assertSame(PaymentStatus::Unpaid, $this->order->fresh()->payment_status);
        $this->assertSame(PaymentLedgerService::REFUND_FULL, $summary['refund_status']);
    }

    public function test_refund_melebihi_saldo_ditolak_menyebut_maksimum_dan_data_tetap_fin_at_019(): void
    {
        $this->refund(40000);

        try {
            $this->refund(70000);
            $this->fail('Refund berlebih seharusnya ditolak.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Rp60.000', $e->getMessage());
        }

        $this->assertSame(1, Refund::count());
        $this->assertSame(60000, $this->summary()['net_payment']);
    }

    public function test_refund_kedua_sampai_habis_diperbolehkan_dan_berikutnya_ditolak(): void
    {
        $this->refund(40000);
        $this->refund(60000);

        $this->assertSame(PaymentLedgerService::REFUND_FULL, $this->summary()['refund_status']);

        $this->expectException(FinanceRuleException::class);

        $this->refund(1);
    }

    public function test_admin_tidak_boleh_mencatat_refund(): void
    {
        try {
            $this->refund(10000, ['actor' => $this->actor(false)]);
            $this->fail('Admin seharusnya ditolak.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('hanya dapat dicatat oleh Owner', $e->getMessage());
        }

        $this->assertSame(0, Refund::count());
    }

    public function test_refund_tanpa_pengguna_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->service->record($this->payment, 10000, '2026-11-27', 'transfer', 'Alasan');
    }

    public function test_refund_pada_payment_void_ditolak(): void
    {
        app(PaymentService::class)->void($this->payment, 'Salah catat', $this->actor(true));

        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Void');

        $this->refund(10000, ['payment' => $this->payment->fresh()]);
    }

    public function test_refund_tanpa_alasan_metode_atau_dengan_nominal_tidak_valid_ditolak(): void
    {
        $cases = [
            ['amount' => 10000, 'reason' => '  ', 'message' => 'Alasan refund wajib diisi'],
            ['amount' => 10000, 'method' => '', 'message' => 'Metode pengembalian wajib diisi'],
            ['amount' => 0, 'message' => 'lebih dari nol'],
            ['amount' => -500, 'message' => 'lebih dari nol'],
        ];

        foreach ($cases as $case) {
            $message = $case['message'];
            $amount = $case['amount'];
            unset($case['message'], $case['amount']);

            try {
                $this->refund($amount, $case);
                $this->fail('Seharusnya ditolak: '.$message);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString($message, $e->getMessage());
            }
        }

        $this->assertSame(0, Refund::count());
    }

    public function test_tanggal_refund_tidak_valid_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Tanggal refund tidak valid');

        $this->service->record($this->payment, 10000, '27/11/2026', 'transfer', 'Alasan', actor: $this->actor(true));
    }

    public function test_refund_tercatat_di_audit_log_dengan_alasan(): void
    {
        $this->refund(40000, ['reason' => 'Barang tidak jadi diambil']);

        $audit = AuditLog::where('action', 'refund.recorded')->first();
        $this->assertNotNull($audit);
        $this->assertSame('Barang tidak jadi diambil', $audit->reason);
        $this->assertSame(40000, $audit->changes['amount']);
    }

    public function test_refund_tidak_mengubah_status_pengerjaan(): void
    {
        $this->refund(40000);

        $this->assertSame(OrderStatus::Processing, $this->order->fresh()->status);
    }

    public function test_refund_tetap_boleh_dicatat_pada_transaksi_dibatalkan_fin_br_013(): void
    {
        $this->order->update(['status' => OrderStatus::Cancelled]);

        $this->refund(100000);

        $this->assertSame(1, Refund::count());
        $this->assertSame(0, $this->summary()['net_payment']);
    }

    public function test_pengiriman_ganda_refund_dengan_kunci_sama_ditolak(): void
    {
        $this->refund(40000, ['key' => 'refund-form-1']);

        try {
            $this->refund(40000, ['key' => 'refund-form-1']);
            $this->fail('Pengiriman ganda seharusnya ditolak.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Pengiriman ganda', $e->getMessage());
        }

        $this->assertSame(1, Refund::count());
    }
}
