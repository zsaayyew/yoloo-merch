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
use App\Models\OrderItem;
use App\Models\OrderRequest;
use App\Models\OrderStatusHistory;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\RequestConversionService;
use App\Services\Sales\StatusTransitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FIN-FR-004 | FIN-BR-001, FIN-BR-003, FIN-BR-004 | FIN-AT-002
 * Memakai RefreshDatabase (phpunit.xml memakai SQLite :memory:).
 */
class RequestConversionServiceTest extends TestCase
{
    use RefreshDatabase;

    private RequestConversionService $service;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(RequestConversionService::class);
        $this->customer = Customer::create([
            'name' => 'Budi Santoso',
            'whatsapp_number' => '6281234567890',
            'is_active' => true,
        ]);
    }

    private function makeRequest(
        RequestStatus $status = RequestStatus::Approved,
        ?int $amount = 2150000,
        int $quantity = 50,
        string $code = 'REQ-261126-0001',
    ): OrderRequest {
        return OrderRequest::create([
            'code' => $code,
            'customer_id' => $this->customer->id,
            'channel' => OrderChannel::Whatsapp,
            'status' => $status,
            'description' => 'Kaos komunitas',
            'specification' => 'Sablon 2 sisi',
            'quantity' => $quantity,
            'notes' => 'Butuh sebelum akhir bulan',
            'quotation_amount' => $amount,
            'quotation_terms' => 'DP 50% sebelum produksi',
            'submitted_at' => now(),
        ]);
    }

    public function test_request_disetujui_dikonversi_menjadi_satu_order_custom(): void
    {
        $request = $this->makeRequest();

        $order = $this->service->convert($request)->fresh();

        $this->assertMatchesRegularExpression('/^CST-\d{6}-[A-Z0-9]{4}$/', $order->code);
        $this->assertSame($this->customer->id, $order->customer_id);
        $this->assertSame(OrderType::Custom, $order->type);
        $this->assertSame(OrderChannel::Whatsapp, $order->channel);
        $this->assertSame($request->id, $order->source_request_id);
        $this->assertSame(OrderStatus::AwaitingPayment, $order->status);
        $this->assertSame(PaymentStatus::Unpaid, $order->payment_status);
        $this->assertSame(2150000, $order->subtotal);
        $this->assertSame(0, $order->discount);
        $this->assertSame(0, $order->additional_fee);
        $this->assertSame(2150000, $order->total);
        $this->assertSame('pickup', $order->fulfillment);
        $this->assertSame('DP 50% sebelum produksi', $order->payment_terms_note);
        $this->assertSame('Butuh sebelum akhir bulan', $order->notes);

        $this->assertSame(RequestStatus::Converted, $request->fresh()->status);
        $this->assertSame($order->id, $request->fresh()->convertedOrder->id);
    }

    public function test_item_order_konsisten_dengan_subtotal_dan_memakai_price_model_quotation(): void
    {
        $order = $this->service->convert($this->makeRequest());

        $items = OrderItem::where('order_id', $order->id)->get();
        $this->assertCount(1, $items);

        $item = $items[0];
        $this->assertSame(50, $item->quantity);
        $this->assertSame(43000, $item->unit_price);
        $this->assertSame(2150000, $item->subtotal);
        $this->assertSame($item->quantity * $item->unit_price, $item->subtotal);
        $this->assertSame('quotation', $item->price_model);
        $this->assertSame('final', $item->price_status);
        $this->assertSame('Custom order', $item->product_name_snapshot);
        $this->assertSame([], $item->options_snapshot);
        $this->assertStringContainsString('Kaos komunitas', (string) $item->description);
        $this->assertStringContainsString('Sablon 2 sisi', (string) $item->description);
    }

    public function test_nominal_yang_tidak_habis_dibagi_kuantitas_tetap_konsisten_fin_br_003(): void
    {
        // 100.000 dibagi 3 tidak bulat: simpan sebagai satu item agar kuantitas x harga = subtotal.
        $order = $this->service->convert($this->makeRequest(amount: 100000, quantity: 3));

        $item = OrderItem::where('order_id', $order->id)->first();
        $this->assertSame(1, $item->quantity);
        $this->assertSame(100000, $item->unit_price);
        $this->assertSame(100000, $item->subtotal);
        $this->assertSame($item->quantity * $item->unit_price, $item->subtotal);
        $this->assertStringContainsString('Jumlah pada request: 3', (string) $item->description);
    }

    public function test_histori_awal_dan_audit_log_tercatat(): void
    {
        $order = $this->service->convert($this->makeRequest());

        $history = OrderStatusHistory::where('order_id', $order->id)->get();
        $this->assertCount(1, $history);
        $this->assertNull($history[0]->previous_status);
        $this->assertSame(OrderStatus::AwaitingPayment, $history[0]->new_status);
        $this->assertStringContainsString('REQ-261126-0001', (string) $history[0]->note);

        $audit = AuditLog::where('action', 'request.converted')->first();
        $this->assertNotNull($audit);
        $this->assertStringContainsString($order->code, (string) $audit->reason);
        $this->assertSame('approved', $audit->changes['status']['old']);
        $this->assertSame('converted', $audit->changes['status']['new']);
    }

    public function test_dp_dan_syarat_pembayaran_dari_input_tersimpan(): void
    {
        $order = $this->service->convert($this->makeRequest(), null, 1000000, 'DP dulu, lunas saat ambil')->fresh();

        $this->assertSame(1000000, $order->agreed_dp_amount);
        $this->assertSame('DP dulu, lunas saat ambil', $order->payment_terms_note);
    }

    public function test_dp_melebihi_total_atau_negatif_ditolak_dan_tidak_membuat_order(): void
    {
        $request = $this->makeRequest();

        foreach ([2150001, -1] as $dp) {
            try {
                $this->service->convert($request, null, $dp);
                $this->fail('DP tidak valid seharusnya ditolak: '.$dp);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('DP', $e->getMessage());
            }
        }

        $this->assertSame(0, Order::count());
        $this->assertSame(RequestStatus::Approved, $request->fresh()->status);
    }

    public function test_konversi_kedua_ditolak_dan_hanya_ada_satu_order_fin_at_002(): void
    {
        $request = $this->makeRequest();
        $this->service->convert($request);

        // Objek $request masih berstatus Disetujui (data basi), seperti admin kedua yang belum memuat ulang.
        try {
            $this->service->convert($request);
            $this->fail('Konversi kedua seharusnya ditolak.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('sudah dikonversi', $e->getMessage());
        }

        $this->assertSame(1, Order::where('source_request_id', $request->id)->count());
        $this->assertSame(1, Order::count());
        $this->assertSame(1, OrderItem::count());
    }

    public function test_request_yang_belum_disetujui_tidak_boleh_dikonversi(): void
    {
        foreach ([RequestStatus::New, RequestStatus::UnderReview, RequestStatus::QuotationSent, RequestStatus::Rejected] as $status) {
            $request = $this->makeRequest($status, 2150000, 50, 'REQ-261126-'.$status->value);

            try {
                $this->service->convert($request);
                $this->fail('Seharusnya ditolak untuk status '.$status->value);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('Disetujui Pelanggan', $e->getMessage());
            }

            $this->assertSame($status, $request->fresh()->status);
        }

        $this->assertSame(0, Order::count());
    }

    public function test_request_tanpa_penawaran_ditolak_dan_tetap_disetujui(): void
    {
        $request = $this->makeRequest(RequestStatus::Approved, null);

        try {
            $this->service->convert($request);
            $this->fail('Seharusnya ditolak tanpa penawaran.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Penawaran belum diisi', $e->getMessage());
        }

        $this->assertSame(RequestStatus::Approved, $request->fresh()->status);
        $this->assertSame(0, Order::count());
    }

    public function test_pengecekan_boleh_tidaknya_konversi_untuk_tombol(): void
    {
        $approved = $this->makeRequest();
        $noQuote = $this->makeRequest(RequestStatus::Approved, null, 50, 'REQ-261126-0002');
        $reviewing = $this->makeRequest(RequestStatus::UnderReview, 500000, 50, 'REQ-261126-0003');

        $this->assertTrue($this->service->canConvert($approved));
        $this->assertNull($this->service->blocker($approved));

        $this->assertFalse($this->service->canConvert($noQuote));
        $this->assertStringContainsString('Penawaran belum diisi', (string) $this->service->blocker($noQuote));

        $this->assertFalse($this->service->canConvert($reviewing));
        $this->assertStringContainsString('Disetujui Pelanggan', (string) $this->service->blocker($reviewing));

        $this->service->convert($approved);
        $this->assertTrue($this->service->alreadyConverted($approved->fresh()));
        $this->assertFalse($this->service->canConvert($approved->fresh()));
        $this->assertNull($this->service->blocker($approved->fresh()));
    }

    public function test_request_yang_sudah_dikonversi_tidak_bisa_diubah_statusnya_lagi(): void
    {
        $request = $this->makeRequest();
        $this->service->convert($request);

        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('status akhir');

        app(StatusTransitionService::class)->changeRequestStatus($request, RequestStatus::UnderReview);
    }
}
