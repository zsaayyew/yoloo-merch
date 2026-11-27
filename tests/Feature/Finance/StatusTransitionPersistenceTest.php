<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\OrderStatusHistory;
use App\Models\Role;
use App\Models\User;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\StatusTransitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

/**
 * FIN-FR-010, FIN-FR-003 | FIN-BR-007, FIN-BR-009 | FIN-AT-008, FIN-AT-024
 * Menyimpan ke database, jadi memakai RefreshDatabase.
 * PENTING: pastikan phpunit.xml memakai SQLite :memory: sebelum menjalankan tes ini.
 */
class StatusTransitionPersistenceTest extends TestCase
{
    use RefreshDatabase;

    private StatusTransitionService $service;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(StatusTransitionService::class);
        $this->customer = Customer::create([
            'name' => 'Budi Santoso',
            'whatsapp_number' => '6281234567890',
            'is_active' => true,
        ]);
    }

    /** Pengguna uji tanpa baris database: cukup untuk menentukan Owner atau Admin. */
    private function actor(bool $owner): User
    {
        $user = new User();
        $user->setRelation('role', (new Role())->forceFill(['name' => $owner ? Role::OWNER : 'admin']));

        return $user;
    }

    private function makeOrder(OrderStatus $status): Order
    {
        return Order::create([
            'code' => 'MAN-261126-'.random_int(1000, 9999),
            'customer_id' => $this->customer->id,
            'type' => OrderType::Manual,
            'channel' => OrderChannel::WalkIn,
            'status' => $status,
            'payment_status' => PaymentStatus::Unpaid,
            'ordered_at' => now(),
        ]);
    }

    private function makeRequest(RequestStatus $status, ?int $quotationAmount = null): OrderRequest
    {
        return OrderRequest::create([
            'code' => 'REQ-261126-'.random_int(1000, 9999),
            'customer_id' => $this->customer->id,
            'status' => $status,
            'description' => 'Kaos komunitas 50 pcs',
            'quantity' => 50,
            'quotation_amount' => $quotationAmount,
            'submitted_at' => now(),
        ]);
    }

    // ---------------- ORDER ----------------

    public function test_admin_memajukan_status_dan_histori_tercatat_fin_at_024(): void
    {
        $order = $this->makeOrder(OrderStatus::Processing);

        $updated = $this->service->changeOrderStatus($order, OrderStatus::ReadyForPickup, $this->actor(false), 'Barang siap diambil');

        $this->assertSame(OrderStatus::ReadyForPickup, $updated->status);
        $this->assertSame(OrderStatus::ReadyForPickup, $order->fresh()->status);

        $history = OrderStatusHistory::where('order_id', $order->id)->get();
        $this->assertCount(1, $history);
        $this->assertSame(OrderStatus::Processing, $history[0]->previous_status);
        $this->assertSame(OrderStatus::ReadyForPickup, $history[0]->new_status);
        $this->assertSame('Barang siap diambil', $history[0]->note);
        $this->assertNotNull($history[0]->changed_at);
    }

    public function test_perubahan_yang_tidak_valid_tidak_mengubah_apa_pun(): void
    {
        $order = $this->makeOrder(OrderStatus::Processing);

        try {
            $this->service->changeOrderStatus($order, OrderStatus::AwaitingPayment, $this->actor(true));
            $this->fail('Seharusnya melempar FinanceRuleException.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('tidak dapat diubah', $e->getMessage());
        }

        $this->assertSame(OrderStatus::Processing, $order->fresh()->status);
        $this->assertSame(0, OrderStatusHistory::count());
    }

    public function test_status_tanpa_pengguna_dianggap_perubahan_sistem(): void
    {
        $order = $this->makeOrder(OrderStatus::AwaitingConfirmation);

        $this->service->changeOrderStatus($order, OrderStatus::AwaitingPayment);

        $this->assertNull(OrderStatusHistory::first()->changed_by_user_id);
    }

    public function test_admin_tidak_bisa_membatalkan_dan_status_tetap(): void
    {
        $order = $this->makeOrder(OrderStatus::Processing);

        try {
            $this->service->changeOrderStatus($order, OrderStatus::Cancelled, $this->actor(false), 'Pelanggan batal');
            $this->fail('Admin seharusnya ditolak.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('hanya dapat dilakukan oleh Owner', $e->getMessage());
        }

        $fresh = $order->fresh();
        $this->assertSame(OrderStatus::Processing, $fresh->status);
        $this->assertNull($fresh->cancelled_at);
        $this->assertSame(0, OrderStatusHistory::count());
        $this->assertFalse(AuditLog::where('action', 'order.cancel')->exists());
    }

    public function test_owner_membatalkan_dengan_alasan_tercatat_di_order_histori_dan_audit(): void
    {
        $order = $this->makeOrder(OrderStatus::Processing);

        $this->service->changeOrderStatus($order, OrderStatus::Cancelled, $this->actor(true), 'Pelanggan membatalkan pesanan');

        $fresh = $order->fresh();
        $this->assertSame(OrderStatus::Cancelled, $fresh->status);
        $this->assertSame('Pelanggan membatalkan pesanan', $fresh->cancellation_reason);
        $this->assertNotNull($fresh->cancelled_at);

        $this->assertSame(1, OrderStatusHistory::where('order_id', $order->id)->count());

        $audit = AuditLog::where('action', 'order.cancel')->first();
        $this->assertNotNull($audit);
        $this->assertSame('Pelanggan membatalkan pesanan', $audit->reason);
        $this->assertEquals($order->id, $audit->subject_id);
    }

    public function test_pembatalan_tanpa_alasan_ditolak_dan_status_tetap(): void
    {
        $order = $this->makeOrder(OrderStatus::AwaitingPayment);

        try {
            $this->service->changeOrderStatus($order, OrderStatus::Cancelled, $this->actor(true), '   ');
            $this->fail('Seharusnya ditolak tanpa alasan.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Alasan pembatalan wajib diisi', $e->getMessage());
        }

        $this->assertSame(OrderStatus::AwaitingPayment, $order->fresh()->status);
    }

    public function test_perubahan_status_pengerjaan_tidak_mengubah_status_pembayaran_fin_at_008(): void
    {
        $order = $this->makeOrder(OrderStatus::AwaitingPayment);

        $this->service->changeOrderStatus($order, OrderStatus::Processing, $this->actor(false));

        $this->assertSame(PaymentStatus::Unpaid, $order->fresh()->payment_status);
    }

    public function test_dua_perubahan_berurutan_menghasilkan_dua_histori_urut(): void
    {
        $order = $this->makeOrder(OrderStatus::AwaitingConfirmation);

        $this->service->changeOrderStatus($order, OrderStatus::AwaitingPayment, $this->actor(false));
        $this->service->changeOrderStatus($order, OrderStatus::Processing, $this->actor(false));

        $history = OrderStatusHistory::where('order_id', $order->id)->orderBy('id')->get();
        $this->assertCount(2, $history);
        $this->assertSame(OrderStatus::AwaitingPayment, $history[0]->new_status);
        $this->assertSame(OrderStatus::Processing, $history[1]->new_status);
    }

    public function test_perubahan_dengan_data_basi_ditolak_karena_status_dibaca_ulang(): void
    {
        // Dua admin membuka order yang sama; admin A sudah memajukan status.
        $order = $this->makeOrder(OrderStatus::AwaitingPayment);
        $stale = Order::find($order->id);

        $this->service->changeOrderStatus($order, OrderStatus::Processing, $this->actor(false));

        // Admin B masih melihat "Menunggu Pembayaran" dan mencoba Diproses lagi.
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('sudah Diproses');

        $this->service->changeOrderStatus($stale, OrderStatus::Processing, $this->actor(false));
    }

    public function test_histori_status_append_only(): void
    {
        $order = $this->makeOrder(OrderStatus::AwaitingConfirmation);
        $this->service->changeOrderStatus($order, OrderStatus::AwaitingPayment, $this->actor(false));
        $history = OrderStatusHistory::first();

        try {
            $history->update(['note' => 'diubah']);
            $this->fail('Update histori seharusnya ditolak.');
        } catch (LogicException $e) {
            $this->assertStringContainsString('append-only', $e->getMessage());
        }

        $this->expectException(LogicException::class);
        $history->delete();
    }

    // ---------------- REQUEST ----------------

    public function test_request_dikirim_penawaran_tercatat_di_audit_log(): void
    {
        $request = $this->makeRequest(RequestStatus::UnderReview, 1500000);

        $updated = $this->service->changeRequestStatus($request, RequestStatus::QuotationSent, $this->actor(false));

        $this->assertSame(RequestStatus::QuotationSent, $updated->status);
        $this->assertSame(RequestStatus::QuotationSent, $request->fresh()->status);

        $audit = AuditLog::where('action', 'request.status_changed')->first();
        $this->assertNotNull($audit);
        $this->assertEquals($request->id, $audit->subject_id);
        $this->assertSame('under_review', $audit->changes['status']['old']);
        $this->assertSame('quotation_sent', $audit->changes['status']['new']);
    }

    public function test_penawaran_tanpa_nominal_ditolak_dan_status_tetap(): void
    {
        $request = $this->makeRequest(RequestStatus::UnderReview, null);

        try {
            $this->service->changeRequestStatus($request, RequestStatus::QuotationSent, $this->actor(false));
            $this->fail('Seharusnya ditolak tanpa nominal penawaran.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('nominal penawaran', $e->getMessage());
        }

        $this->assertSame(RequestStatus::UnderReview, $request->fresh()->status);
        $this->assertSame(0, AuditLog::where('action', 'request.status_changed')->count());
    }

    public function test_request_ditolak_wajib_alasan_dan_alasan_tersimpan(): void
    {
        $request = $this->makeRequest(RequestStatus::QuotationSent, 900000);

        try {
            $this->service->changeRequestStatus($request, RequestStatus::Rejected, $this->actor(false), '');
            $this->fail('Seharusnya ditolak tanpa alasan.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Alasan wajib diisi', $e->getMessage());
        }

        $this->service->changeRequestStatus($request, RequestStatus::Rejected, $this->actor(false), 'Harga tidak cocok');

        $this->assertSame(RequestStatus::Rejected, $request->fresh()->status);
        $this->assertSame('Harga tidak cocok', AuditLog::where('action', 'request.status_changed')->first()->reason);
    }

    public function test_request_tidak_bisa_diubah_ke_dikonversi_lewat_ubah_status(): void
    {
        $request = $this->makeRequest(RequestStatus::Approved, 900000);

        $this->expectException(FinanceRuleException::class);

        $this->service->changeRequestStatus($request, RequestStatus::Converted, $this->actor(false));
    }
}
