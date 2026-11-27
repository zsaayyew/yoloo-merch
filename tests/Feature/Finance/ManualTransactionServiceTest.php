<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\ManualTransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FIN-FR-005, FIN-FR-006, FIN-FR-007 | FIN-BR-002, 003, 004, 017 | FIN-AT-003
 * Memakai RefreshDatabase (phpunit.xml memakai SQLite :memory:).
 * Item katalog (product_id valid) belum dites karena butuh data produk dari domain ADM.
 */
class ManualTransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    private ManualTransactionService $service;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ManualTransactionService::class);
        $this->customer = Customer::create([
            'name' => 'Budi Santoso',
            'whatsapp_number' => '6281234567890',
            'is_active' => true,
        ]);
    }

    /** @return array<int, array<string, mixed>> */
    private function items(): array
    {
        return [
            ['name' => 'Kaos komunitas', 'quantity' => 2, 'unit_price' => 25000, 'description' => 'Sablon depan'],
            ['name' => 'Stiker custom', 'quantity' => 1, 'unit_price' => 40000],
        ];
    }

    public function test_transaksi_manual_dibuat_dengan_total_dihitung_sistem_fin_at_003(): void
    {
        // Subtotal 50.000 + 40.000 = 90.000; diskon 10.000; biaya tambahan 5.000 => 85.000
        $order = $this->service->create(
            $this->customer,
            $this->items(),
            discount: 10000,
            additionalFee: 5000,
            channel: OrderChannel::Whatsapp,
            notes: 'Pesan via WhatsApp',
        )->fresh();

        $this->assertSame('MAN-'.now()->format('ymd').'-0001', $order->code);
        $this->assertSame($this->customer->id, $order->customer_id);
        $this->assertSame(OrderType::Manual, $order->type);
        $this->assertSame(OrderChannel::Whatsapp, $order->channel);
        $this->assertSame(OrderStatus::AwaitingPayment, $order->status);
        $this->assertSame(PaymentStatus::Unpaid, $order->payment_status);
        $this->assertSame(90000, $order->subtotal);
        $this->assertSame(10000, $order->discount);
        $this->assertSame(5000, $order->additional_fee);
        $this->assertSame(85000, $order->total);
        $this->assertSame('pickup', $order->fulfillment);
        $this->assertSame('Pesan via WhatsApp', $order->notes);
        $this->assertNull($order->source_request_id);
    }

    public function test_item_tersimpan_sebagai_snapshot_dengan_subtotal(): void
    {
        $order = $this->service->create($this->customer, $this->items());

        $items = OrderItem::where('order_id', $order->id)->orderBy('id')->get();
        $this->assertCount(2, $items);

        $this->assertSame('Kaos komunitas', $items[0]->product_name_snapshot);
        $this->assertSame(2, $items[0]->quantity);
        $this->assertSame(25000, $items[0]->unit_price);
        $this->assertSame(50000, $items[0]->subtotal);
        $this->assertSame('quotation', $items[0]->price_model);
        $this->assertSame('final', $items[0]->price_status);
        $this->assertNull($items[0]->product_id);
        $this->assertSame([], $items[0]->options_snapshot);
        $this->assertSame('Sablon depan', $items[0]->description);

        $this->assertSame(40000, $items[1]->subtotal);
    }

    public function test_riwayat_status_awal_tercatat(): void
    {
        $order = $this->service->create($this->customer, $this->items());

        $history = OrderStatusHistory::where('order_id', $order->id)->get();
        $this->assertCount(1, $history);
        $this->assertNull($history[0]->previous_status);
        $this->assertSame(OrderStatus::AwaitingPayment, $history[0]->new_status);
    }

    public function test_nomor_transaksi_berurutan_dan_unik_per_hari(): void
    {
        $first = $this->service->create($this->customer, $this->items());
        $second = $this->service->create($this->customer, $this->items());

        $prefix = 'MAN-'.now()->format('ymd').'-';
        $this->assertSame($prefix.'0001', $first->code);
        $this->assertSame($prefix.'0002', $second->code);
        $this->assertSame(2, Order::count());
    }

    public function test_dp_dan_catatan_pembayaran_tersimpan(): void
    {
        $order = $this->service->create(
            $this->customer,
            $this->items(),
            agreedDpAmount: 40000,
            paymentTermsNote: 'DP dulu, lunas saat ambil',
        )->fresh();

        $this->assertSame(40000, $order->agreed_dp_amount);
        $this->assertSame('DP dulu, lunas saat ambil', $order->payment_terms_note);
    }

    public function test_dp_melebihi_total_atau_nol_ditolak(): void
    {
        foreach ([90001, 0, -10] as $dp) {
            try {
                $this->service->create($this->customer, $this->items(), agreedDpAmount: $dp);
                $this->fail('DP tidak valid seharusnya ditolak: '.$dp);
            } catch (FinanceRuleException $e) {
                $this->assertNotSame('', $e->getMessage());
            }
        }

        $this->assertSame(0, Order::count());
    }

    public function test_transaksi_tanpa_item_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('minimal satu item');

        $this->service->create($this->customer, []);
    }

    public function test_item_tidak_valid_membatalkan_seluruh_transaksi(): void
    {
        $items = [
            ['name' => 'Kaos', 'quantity' => 2, 'unit_price' => 25000],
            ['name' => 'Rusak', 'quantity' => 0, 'unit_price' => 1000],
        ];

        try {
            $this->service->create($this->customer, $items);
            $this->fail('Seharusnya ditolak karena kuantitas nol.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Kuantitas item ke-2', $e->getMessage());
        }

        $this->assertSame(0, Order::count());
        $this->assertSame(0, OrderItem::count());
        $this->assertSame(0, OrderStatusHistory::count());
    }

    public function test_nama_item_kosong_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Nama item ke-1 wajib diisi');

        $this->service->create($this->customer, [['name' => ' ', 'quantity' => 1, 'unit_price' => 1000]]);
    }

    public function test_diskon_melebihi_subtotal_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('tidak boleh melebihi subtotal');

        $this->service->create($this->customer, $this->items(), discount: 90001);
    }

    public function test_total_nol_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('lebih dari nol');

        $this->service->create($this->customer, [['name' => 'Gratis', 'quantity' => 1, 'unit_price' => 0]]);
    }

    public function test_pelanggan_nonaktif_ditolak(): void
    {
        $this->customer->update(['is_active' => false]);

        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('nonaktif');

        $this->service->create($this->customer->fresh(), $this->items());
    }

    public function test_kanal_website_ditolak_untuk_transaksi_manual(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('di luar website');

        $this->service->create($this->customer, $this->items(), channel: OrderChannel::Website);
    }

    public function test_produk_katalog_yang_tidak_ada_ditolak(): void
    {
        $items = [['name' => 'Produk hantu', 'quantity' => 1, 'unit_price' => 1000, 'product_id' => 999999]];

        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('tidak ditemukan');

        $this->service->create($this->customer, $items);
    }

    public function test_angka_string_dari_form_diterima(): void
    {
        $order = $this->service->create($this->customer, [
            ['name' => 'Kaos', 'quantity' => '2', 'unit_price' => '25000'],
        ]);

        $this->assertSame(50000, $order->fresh()->total);
    }
}
