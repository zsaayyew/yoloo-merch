<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentRecordStatus;
use App\Enums\PaymentStatus;
use App\Enums\RefundStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

/**
 * FIN-FR-008, FIN-FR-009, FIN-FR-019 | FIN-BR-005, FIN-BR-009
 * Memastikan migration 25 dan 26, enum, dan model Payment/Refund bekerja sebelum service dibuat.
 * Memakai RefreshDatabase (pastikan phpunit.xml memakai SQLite :memory:).
 */
class PaymentRefundSchemaTest extends TestCase
{
    use RefreshDatabase;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

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
            'total' => 200000,
            'ordered_at' => now(),
        ]);
    }

    private function makePayment(array $overrides = []): Payment
    {
        return Payment::create($overrides + [
            'order_id' => $this->order->id,
            'payment_date' => '2026-11-26',
            'amount' => 50000,
            'note' => 'DP',
        ]);
    }

    public function test_nilai_enum_sesuai_kontrak_ai_context(): void
    {
        $this->assertSame('valid', PaymentRecordStatus::Valid->value);
        $this->assertSame('void', PaymentRecordStatus::Void->value);
        $this->assertSame('Valid', PaymentRecordStatus::Valid->label());
        $this->assertSame('Void', PaymentRecordStatus::Void->label());

        $this->assertSame('completed', RefundStatus::Completed->value);
        $this->assertSame('cancelled', RefundStatus::Cancelled->value);
        $this->assertSame('Selesai', RefundStatus::Completed->label());
        $this->assertSame('Dibatalkan', RefundStatus::Cancelled->label());
    }

    public function test_payment_baru_berstatus_valid_dengan_metode_qris_dan_jumlah_integer(): void
    {
        $payment = $this->makePayment()->fresh();

        $this->assertSame(PaymentRecordStatus::Valid, $payment->status);
        $this->assertSame('qris', $payment->method);
        $this->assertSame(50000, $payment->amount);
        $this->assertSame('2026-11-26', $payment->payment_date->format('Y-m-d'));
        $this->assertTrue($payment->isValid());
        $this->assertNull($payment->voided_at);
    }

    public function test_order_punya_banyak_payment_dan_refund_terhubung_ke_payment_sumber(): void
    {
        $payment = $this->makePayment(['amount' => 100000]);
        $this->makePayment(['amount' => 50000]);

        $refund = Refund::create([
            'order_id' => $this->order->id,
            'payment_id' => $payment->id,
            'refund_date' => '2026-11-27',
            'amount' => 40000,
            'method' => 'transfer',
            'reason' => 'Pelanggan mengurangi jumlah pesanan',
        ])->fresh();

        $this->assertSame(RefundStatus::Completed, $refund->status);
        $this->assertSame(40000, $refund->amount);
        $this->assertSame($payment->id, $refund->payment->id);
        $this->assertTrue($refund->isCompleted());
        $this->assertSame(1, $payment->refunds()->count());
        $this->assertSame(2, Payment::where('order_id', $this->order->id)->count());
    }

    public function test_payment_tidak_boleh_dihapus(): void
    {
        $payment = $this->makePayment();

        try {
            $payment->delete();
            $this->fail('Menghapus payment seharusnya ditolak.');
        } catch (LogicException $e) {
            $this->assertStringContainsString('Void', $e->getMessage());
        }

        $this->assertSame(1, Payment::count());
    }

    public function test_refund_tidak_boleh_dihapus(): void
    {
        $payment = $this->makePayment();
        $refund = Refund::create([
            'order_id' => $this->order->id,
            'payment_id' => $payment->id,
            'refund_date' => '2026-11-27',
            'amount' => 10000,
            'method' => 'tunai',
            'reason' => 'Salah bayar',
        ]);

        $this->expectException(LogicException::class);

        $refund->delete();
    }

    public function test_kunci_pengiriman_yang_sama_ditolak_database(): void
    {
        $this->makePayment(['submission_key' => 'abc-123']);

        $this->expectException(QueryException::class);

        $this->makePayment(['submission_key' => 'abc-123']);
    }

    public function test_kunci_pengiriman_kosong_boleh_berulang(): void
    {
        $this->makePayment(['submission_key' => null]);
        $this->makePayment(['submission_key' => null]);

        $this->assertSame(2, Payment::count());
    }
}
