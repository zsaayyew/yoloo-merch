<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Http\Middleware\EnsureUserIsActive;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use App\Services\Finance\PaymentService;
use App\Services\Finance\RefundService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Merender halaman detail order dengan panel status, pembayaran, refund, dan riwayat.
 * Tes ini ikut merender layout admin. Jika gagal karena layout atau partial lain (bukan file FIN),
 * pesan kesalahannya menunjuk file yang bermasalah; kirim pesan itu ke tim.
 */
class OrderDetailPageTest extends TestCase
{
    use RefreshDatabase;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->withoutMiddleware(EnsureUserIsActive::class);

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

    public function test_detail_order_menampilkan_panel_pembayaran_dan_form_untuk_admin(): void
    {
        $this->actingAs($this->actor(false))
            ->get(route('admin.orders.show', $this->order))
            ->assertOk()
            ->assertSee('Status Pengerjaan')
            ->assertSee('Riwayat Status')
            ->assertSee('Sisa tagihan')
            ->assertSee('Rp200.000')
            ->assertSee('Tambah Pembayaran QRIS')
            ->assertDontSee('Batalkan Transaksi')
            ->assertSee('Refund hanya dapat dicatat oleh Owner.');
    }

    public function test_owner_melihat_tombol_batal_void_dan_form_refund(): void
    {
        $payment = app(PaymentService::class)->record($this->order, 100000, '2026-11-26', 'QR-777', 'DP');

        $this->actingAs($this->actor(true))
            ->get(route('admin.orders.show', $this->order))
            ->assertOk()
            ->assertSee('Batalkan Transaksi')
            ->assertSee('Void Pembayaran')
            ->assertSee('QR-777')
            ->assertSee('Rp100.000')
            ->assertSee('Simpan Refund');

        $this->assertNotNull($payment);
    }

    public function test_refund_dan_riwayat_status_tampil_di_halaman(): void
    {
        $payment = app(PaymentService::class)->record($this->order, 100000, '2026-11-26');
        app(RefundService::class)->record($payment, 40000, '2026-11-27', 'transfer', 'Kurangi jumlah', actor: $this->actor(true));

        $this->actingAs($this->actor(true))
            ->get(route('admin.orders.show', $this->order))
            ->assertOk()
            ->assertSee('Kurangi jumlah')
            ->assertSee('Rp40.000')
            ->assertSee('Refund Sebagian');
    }

    public function test_transaksi_lunas_tidak_menampilkan_form_pembayaran(): void
    {
        app(PaymentService::class)->record($this->order, 200000, '2026-11-26');

        $this->actingAs($this->actor(false))
            ->get(route('admin.orders.show', $this->order))
            ->assertOk()
            ->assertDontSee('Tambah Pembayaran QRIS')
            ->assertSee('sudah lunas');
    }

    public function test_transaksi_dibatalkan_tidak_menampilkan_form_pembayaran_dan_status_akhir(): void
    {
        $this->order->update(['status' => OrderStatus::Cancelled]);

        $this->actingAs($this->actor(true))
            ->get(route('admin.orders.show', $this->order))
            ->assertOk()
            ->assertDontSee('Tambah Pembayaran QRIS')
            ->assertSee('Transaksi dibatalkan')
            ->assertSee('status akhir');
    }
}
