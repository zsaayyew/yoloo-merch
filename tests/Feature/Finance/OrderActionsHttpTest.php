<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentRecordStatus;
use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use App\Http\Middleware\EnsureUserIsActive;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Role;
use App\Models\User;
use App\Services\Finance\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Alur form ke controller: status order, pembatalan, pembayaran, Void, refund, dan hak akses.
 * FIN-FR-008, 009, 010, 016, 019 | FIN-BR-009, FIN-BR-019
 * Tidak merender halaman, jadi tidak bergantung pada layout. Memakai RefreshDatabase.
 */
class OrderActionsHttpTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(EnsureUserIsActive::class);

        $this->customer = Customer::create([
            'name' => 'Budi Santoso',
            'whatsapp_number' => '6281234567890',
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'code' => 'MAN-261126-0001',
            'customer_id' => $this->customer->id,
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

    private function showUrl(): string
    {
        return route('admin.orders.show', $this->order);
    }

    private function payOnce(int $amount): Payment
    {
        return app(PaymentService::class)->record($this->order, $amount, '2026-11-26');
    }

    // ---------------- Status order ----------------

    public function test_admin_memajukan_status_order_lewat_form(): void
    {
        $this->actingAs($this->actor(false))
            ->from($this->showUrl())
            ->patch(route('admin.orders.status.update', $this->order), ['status' => 'processing', 'note' => 'Mulai produksi'])
            ->assertRedirect($this->showUrl())
            ->assertSessionHas('success');

        $this->assertSame(OrderStatus::Processing, $this->order->fresh()->status);
        $this->assertSame(1, OrderStatusHistory::where('order_id', $this->order->id)->count());
    }

    public function test_status_yang_tidak_valid_ditolak_dengan_pesan_dan_status_tetap(): void
    {
        $this->actingAs($this->actor(false))
            ->from($this->showUrl())
            ->patch(route('admin.orders.status.update', $this->order), ['status' => 'completed'])
            ->assertRedirect($this->showUrl())
            ->assertSessionHas('error');

        $this->assertSame(OrderStatus::AwaitingPayment, $this->order->fresh()->status);
    }

    public function test_status_tujuan_kosong_atau_asal_asalan_gagal_validasi(): void
    {
        $this->actingAs($this->actor(false))
            ->from($this->showUrl())
            ->patch(route('admin.orders.status.update', $this->order), ['status' => 'ngawur'])
            ->assertSessionHasErrors('status', null, 'status');

        $this->assertSame(OrderStatus::AwaitingPayment, $this->order->fresh()->status);
    }

    public function test_pembatalan_lewat_form_status_ditolak_walau_oleh_owner(): void
    {
        $this->actingAs($this->actor(true))
            ->from($this->showUrl())
            ->patch(route('admin.orders.status.update', $this->order), ['status' => 'cancelled'])
            ->assertSessionHas('error');

        $this->assertSame(OrderStatus::AwaitingPayment, $this->order->fresh()->status);
    }

    // ---------------- Pembatalan ----------------

    public function test_owner_membatalkan_transaksi_dengan_alasan(): void
    {
        $this->actingAs($this->actor(true))
            ->from($this->showUrl())
            ->post(route('admin.orders.cancel', $this->order), ['reason' => 'Pelanggan membatalkan pesanan'])
            ->assertRedirect($this->showUrl())
            ->assertSessionHas('success');

        $fresh = $this->order->fresh();
        $this->assertSame(OrderStatus::Cancelled, $fresh->status);
        $this->assertSame('Pelanggan membatalkan pesanan', $fresh->cancellation_reason);
    }

    public function test_admin_tidak_boleh_membatalkan_transaksi(): void
    {
        $this->actingAs($this->actor(false))
            ->post(route('admin.orders.cancel', $this->order), ['reason' => 'Coba batalkan'])
            ->assertForbidden();

        $this->assertSame(OrderStatus::AwaitingPayment, $this->order->fresh()->status);
    }

    public function test_pembatalan_tanpa_alasan_gagal_validasi(): void
    {
        $this->actingAs($this->actor(true))
            ->from($this->showUrl())
            ->post(route('admin.orders.cancel', $this->order), ['reason' => ''])
            ->assertSessionHasErrors('reason', null, 'cancel');

        $this->assertSame(OrderStatus::AwaitingPayment, $this->order->fresh()->status);
    }

    // ---------------- Pembayaran ----------------

    public function test_admin_mencatat_pembayaran_dan_status_pembayaran_ikut_berubah(): void
    {
        $this->actingAs($this->actor(false))
            ->from($this->showUrl())
            ->post(route('admin.orders.payments.store', $this->order), [
                'amount' => 50000,
                'payment_date' => '2026-11-26',
                'reference' => 'QR-001',
                'note' => 'DP',
                'submission_key' => 'form-1',
            ])
            ->assertRedirect($this->showUrl())
            ->assertSessionHas('success');

        $this->assertSame(1, Payment::count());
        $this->assertSame(PaymentStatus::PartiallyPaid, $this->order->fresh()->payment_status);
        $this->assertSame(OrderStatus::AwaitingPayment, $this->order->fresh()->status);
    }

    public function test_pembayaran_melebihi_sisa_ditolak_dan_pesan_menyebut_sisa(): void
    {
        $this->payOnce(100000);

        $response = $this->actingAs($this->actor(false))
            ->from($this->showUrl())
            ->post(route('admin.orders.payments.store', $this->order), [
                'amount' => 120000,
                'payment_date' => '2026-11-26',
            ])
            ->assertRedirect($this->showUrl())
            ->assertSessionHas('error');

        $this->assertStringContainsString('Rp100.000', (string) session('error'));
        $this->assertSame(1, Payment::count());
    }

    public function test_pembayaran_dengan_nominal_nol_atau_tanggal_salah_gagal_validasi(): void
    {
        $this->actingAs($this->actor(false))
            ->from($this->showUrl())
            ->post(route('admin.orders.payments.store', $this->order), ['amount' => 0, 'payment_date' => '26/11/2026'])
            ->assertSessionHasErrors(['amount', 'payment_date'], null, 'payment');

        $this->assertSame(0, Payment::count());
    }

    public function test_pengiriman_ganda_pembayaran_dengan_kunci_sama_hanya_tercatat_sekali(): void
    {
        $payload = ['amount' => 50000, 'payment_date' => '2026-11-26', 'submission_key' => 'form-ganda'];
        $url = route('admin.orders.payments.store', $this->order);

        $this->actingAs($this->actor(false))->from($this->showUrl())->post($url, $payload);
        $this->actingAs($this->actor(false))->from($this->showUrl())->post($url, $payload)->assertSessionHas('error');

        $this->assertSame(1, Payment::count());
    }

    // ---------------- Void ----------------

    public function test_owner_void_pembayaran_dan_admin_tidak_boleh(): void
    {
        $payment = $this->payOnce(50000);

        $this->actingAs($this->actor(false))
            ->post(route('admin.payments.void', $payment), ['reason' => 'Salah catat'])
            ->assertForbidden();
        $this->assertSame(PaymentRecordStatus::Valid, $payment->fresh()->status);

        $this->actingAs($this->actor(true))
            ->from($this->showUrl())
            ->post(route('admin.payments.void', $payment), ['reason' => 'Salah catat nominal'])
            ->assertRedirect($this->showUrl())
            ->assertSessionHas('success');

        $this->assertSame(PaymentRecordStatus::Void, $payment->fresh()->status);
        $this->assertSame(PaymentStatus::Unpaid, $this->order->fresh()->payment_status);
        $this->assertSame(1, Payment::count());
    }

    public function test_void_tanpa_alasan_gagal_validasi(): void
    {
        $payment = $this->payOnce(50000);

        $this->actingAs($this->actor(true))
            ->from($this->showUrl())
            ->post(route('admin.payments.void', $payment), ['reason' => ''])
            ->assertSessionHasErrors('reason', null, 'void');

        $this->assertSame(PaymentRecordStatus::Valid, $payment->fresh()->status);
    }

    // ---------------- Refund ----------------

    public function test_owner_mencatat_refund_dan_admin_tidak_boleh(): void
    {
        $payment = $this->payOnce(100000);
        $payload = [
            'amount' => 40000,
            'refund_date' => '2026-11-27',
            'method' => 'transfer',
            'reason' => 'Pelanggan mengurangi jumlah pesanan',
        ];

        $this->actingAs($this->actor(false))
            ->post(route('admin.payments.refunds.store', $payment), $payload)
            ->assertForbidden();
        $this->assertSame(0, Refund::count());

        $this->actingAs($this->actor(true))
            ->from($this->showUrl())
            ->post(route('admin.payments.refunds.store', $payment), $payload)
            ->assertRedirect($this->showUrl())
            ->assertSessionHas('success');

        $this->assertSame(1, Refund::count());
        $this->assertSame(PaymentStatus::PartiallyPaid, $this->order->fresh()->payment_status);
    }

    public function test_refund_melebihi_saldo_ditolak_dan_pesan_menyebut_maksimum(): void
    {
        $payment = $this->payOnce(100000);

        $this->actingAs($this->actor(true))
            ->from($this->showUrl())
            ->post(route('admin.payments.refunds.store', $payment), [
                'amount' => 100001,
                'refund_date' => '2026-11-27',
                'method' => 'transfer',
                'reason' => 'Uji',
            ])
            ->assertSessionHas('error');

        $this->assertStringContainsString('Rp100.000', (string) session('error'));
        $this->assertSame(0, Refund::count());
    }

    public function test_refund_tanpa_alasan_atau_metode_gagal_validasi(): void
    {
        $payment = $this->payOnce(100000);

        $this->actingAs($this->actor(true))
            ->from($this->showUrl())
            ->post(route('admin.payments.refunds.store', $payment), [
                'amount' => 1000,
                'refund_date' => '2026-11-27',
                'method' => '',
                'reason' => '',
            ])
            ->assertSessionHasErrors(['method', 'reason'], null, 'refund');

        $this->assertSame(0, Refund::count());
    }

    // ---------------- Review request (perbaikan Gate OrderRequest) ----------------

    public function test_admin_dapat_mengubah_status_request_tidak_ditolak_gate(): void
    {
        $request = OrderRequest::create([
            'code' => 'REQ-261126-0001',
            'customer_id' => $this->customer->id,
            'status' => RequestStatus::New,
            'description' => 'Kaos komunitas',
            'quantity' => 50,
            'submitted_at' => now(),
        ]);

        $this->actingAs($this->actor(false))
            ->from(route('admin.requests.show', $request))
            ->patch(route('admin.requests.status.update', $request), ['status' => 'under_review'])
            ->assertRedirect(route('admin.requests.show', $request))
            ->assertSessionHas('success');

        $this->assertSame(RequestStatus::UnderReview, $request->fresh()->status);
    }
}
