<?php

declare(strict_types=1);

namespace App\Services\Sales;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRequest;
use App\Models\OrderStatusHistory;
use App\Models\User;
use App\Services\Audit\AuditLogger;
use App\Services\Finance\FinanceRuleException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PRD-05 FIN | FIN-FR-004 - Konversi request yang disetujui menjadi order, hanya sekali.
 *
 * Pengaman konversi ganda berlapis:
 *  1. baris request dikunci (lockForUpdate) lalu status dan order sumber dicek ulang,
 *  2. aturan dari StatusTransitionService::assertRequestConvertible,
 *  3. orders.source_request_id bersifat unik di database.
 * Order dan itemnya dibuat dalam satu transaksi; gagal di tengah berarti tidak ada yang tersimpan.
 *
 * Status request menjadi "Dikonversi" ditulis di sini karena StatusTransitionService
 * sengaja menolak status itu lewat ubah status manual.
 */
class RequestConversionService
{
    public function __construct(
        private readonly StatusTransitionService $transitions,
        private readonly ?AuditLogger $auditLogger = null,
    ) {
    }

    public function alreadyConverted(OrderRequest $request): bool
    {
        return $request->status === RequestStatus::Converted
            || Order::query()->where('source_request_id', $request->getKey())->exists();
    }

    public function canConvert(OrderRequest $request): bool
    {
        return $request->status === RequestStatus::Approved
            && ! $this->alreadyConverted($request)
            && (int) $request->quotation_amount > 0;
    }

    /** Alasan konversi belum bisa dilakukan; null jika boleh atau sudah dikonversi. */
    public function blocker(OrderRequest $request): ?string
    {
        if ($this->alreadyConverted($request)) {
            return null;
        }

        if ($request->status !== RequestStatus::Approved) {
            return 'Konversi hanya bisa dilakukan setelah request berstatus Disetujui Pelanggan.';
        }

        if ((int) $request->quotation_amount <= 0) {
            return 'Penawaran belum diisi, jadi total order belum bisa ditentukan.';
        }

        return null;
    }

    /**
     * Asumsi nilai awal order (cocokkan dengan dokumen rancangan):
     *  - status awaiting_payment dan pembayaran unpaid (pelanggan sudah menyetujui penawaran)
     *  - total = nominal penawaran, diskon dan biaya tambahan 0, pengambilan di tempat
     *  - satu order item ringkasan request, price_status final, price_model "custom"
     *
     * @throws FinanceRuleException
     */
    public function convert(OrderRequest $request, ?User $actor = null, ?int $agreedDp = null, ?string $paymentTerms = null): Order
    {
        try {
            return DB::transaction(function () use ($request, $actor, $agreedDp, $paymentTerms): Order {
                $locked = OrderRequest::query()->whereKey($request->getKey())->lockForUpdate()->firstOrFail();

                $this->transitions->assertRequestConvertible(
                    $locked->status,
                    Order::query()->where('source_request_id', $locked->getKey())->exists(),
                );

                $total = (int) $locked->quotation_amount;

                if ($total <= 0) {
                    throw new FinanceRuleException('Penawaran belum diisi, jadi total order belum bisa ditentukan.');
                }

                if ($agreedDp !== null && ($agreedDp < 0 || $agreedDp > $total)) {
                    throw new FinanceRuleException('Nominal DP tidak boleh melebihi total order.');
                }

                $order = Order::create([
                    'code' => $this->generateOrderCode(),
                    'customer_id' => $locked->customer_id,
                    'type' => OrderType::Custom,
                    'channel' => $locked->channel,
                    'source_request_id' => $locked->getKey(),
                    'status' => OrderStatus::AwaitingPayment,
                    'payment_status' => PaymentStatus::Unpaid,
                    'subtotal' => $total,
                    'discount' => 0,
                    'additional_fee' => 0,
                    'total' => $total,
                    'agreed_dp_amount' => $agreedDp,
                    'payment_terms_note' => filled($paymentTerms) ? trim((string) $paymentTerms) : $locked->quotation_terms,
                    'fulfillment' => 'pickup',
                    'notes' => $locked->notes,
                    'ordered_at' => now(),
                ]);

                // FIN-BR-003: kuantitas x harga satuan harus sama dengan subtotal. Jika nominal penawaran tidak
                // habis dibagi kuantitas, simpan sebagai satu item dan catat jumlah request di deskripsi.
                $requestedQuantity = max((int) $locked->quantity, 1);
                $quantity = $total % $requestedQuantity === 0 ? $requestedQuantity : 1;
                $quantityNote = $quantity === $requestedQuantity ? '' : "\n\nJumlah pada request: ".$requestedQuantity;

                OrderItem::create([
                    'order_id' => $order->getKey(),
                    'product_id' => $locked->product_id,
                    'product_name_snapshot' => $locked->product_name_snapshot ?: 'Custom order',
                    'options_snapshot' => $locked->options_snapshot ?? [],
                    'quantity' => $quantity,
                    'price_model' => 'quotation',
                    'unit_price' => intdiv($total, $quantity),
                    'subtotal' => $total,
                    'price_status' => OrderItem::PRICE_FINAL,
                    'description' => trim($locked->description."\n\n".($locked->specification ?? '').$quantityNote),
                ]);

                // Catatan awal histori order (previous_status kosong).
                OrderStatusHistory::create([
                    'order_id' => $order->getKey(),
                    'previous_status' => null,
                    'new_status' => OrderStatus::AwaitingPayment,
                    'changed_by_user_id' => $actor?->getKey(),
                    'changed_at' => now(),
                    'note' => 'Dibuat dari konversi request '.$locked->code,
                ]);

                $from = $locked->status;
                $locked->update(['status' => RequestStatus::Converted]);

                $this->audit()->log('request.converted', $locked, 'Dikonversi menjadi order '.$order->code, [
                    'status' => ['old' => $from->value, 'new' => RequestStatus::Converted->value],
                ]);

                return $order;
            });
        } catch (UniqueConstraintViolationException) {
            // Jaring pengaman terakhir: orders.source_request_id unik.
            throw new FinanceRuleException('Request ini sudah dikonversi menjadi order.');
        }
    }

    /** Format CST-YYMMDD-XXXX dan dicek unik. Awalan CST adalah asumsi; ganti bila rancangan menentukan lain. */
    private function generateOrderCode(): string
    {
        do {
            $code = 'CST-'.now()->format('ymd').'-'.Str::upper(Str::random(4));
        } while (Order::query()->where('code', $code)->exists());

        return $code;
    }

    private function audit(): AuditLogger
    {
        return $this->auditLogger ?? app(AuditLogger::class);
    }
}
