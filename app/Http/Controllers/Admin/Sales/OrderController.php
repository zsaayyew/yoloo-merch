<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Enums\AttachmentStatus;
use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderAttachment;
use App\Models\OrderRequest;
use App\Services\Sales\QuotationService;
use App\Services\Sales\RequestConversionService;
use App\Services\Sales\StatusTransitionService;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * PRD-05 FIN - Daftar & detail order/request (FIN-FR-001, FIN-FR-002).
 * Halaman ini hanya membaca data. Perubahan status, penawaran, dan konversi
 * ditangani controller FIN lain (RequestStatusController, QuotationController, dst.).
 */
class OrderController extends Controller
{
    private const PER_PAGE = 15;

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Order::class);

        $tab = $request->query('tab') === 'requests' ? 'requests' : 'orders';
        $statusEnum = $tab === 'requests' ? RequestStatus::class : OrderStatus::class;

        $filters = $request->validate([
            'tab' => ['nullable', Rule::in(['orders', 'requests'])],
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum($statusEnum)],
            'payment_status' => ['nullable', Rule::enum(PaymentStatus::class)],
            'channel' => ['nullable', Rule::enum(OrderChannel::class)],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $search = trim((string) ($filters['q'] ?? ''));
        $applySearch = $this->searchClosure($search);

        $orders = null;
        $requests = null;

        if ($tab === 'orders') {
            $orders = Order::query()
                ->with('customer')
                ->withCount('items')
                ->when($search !== '', fn ($q) => $q->where($applySearch))
                ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
                ->when($filters['payment_status'] ?? null, fn ($q, $v) => $q->where('payment_status', $v))
                ->when($filters['channel'] ?? null, fn ($q, $v) => $q->where('channel', $v))
                ->when($filters['from'] ?? null, fn ($q, $v) => $q->whereDate('ordered_at', '>=', $v))
                ->when($filters['to'] ?? null, fn ($q, $v) => $q->whereDate('ordered_at', '<=', $v))
                ->orderByDesc('ordered_at')
                ->orderByDesc('id')
                ->paginate(self::PER_PAGE)
                ->withQueryString();
        } else {
            $requests = OrderRequest::query()
                ->with('customer')
                ->when($search !== '', fn ($q) => $q->where($applySearch))
                ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
                ->when($filters['channel'] ?? null, fn ($q, $v) => $q->where('channel', $v))
                ->when($filters['from'] ?? null, fn ($q, $v) => $q->whereDate('submitted_at', '>=', $v))
                ->when($filters['to'] ?? null, fn ($q, $v) => $q->whereDate('submitted_at', '<=', $v))
                ->orderByDesc('submitted_at')
                ->orderByDesc('id')
                ->paginate(self::PER_PAGE)
                ->withQueryString();
        }

        return view('admin.orders.index', [
            'tab' => $tab,
            'filters' => $filters,
            'orders' => $orders,
            'requests' => $requests,
            'orderCount' => Order::count(),
            'requestCount' => OrderRequest::count(),
            'newRequestCount' => OrderRequest::where('status', RequestStatus::New->value)->count(),
            'statusOptions' => $this->options($statusEnum),
            'paymentOptions' => $this->options(PaymentStatus::class),
            'channelOptions' => $this->options(OrderChannel::class),
        ]);
    }

    public function show(Order $order): View
    {
        Gate::authorize('view', $order);

        $order->load(['customer', 'items', 'sourceRequest']);

        $itemIds = $order->items->pluck('id');

        $attachments = OrderAttachment::query()
            ->where('status', AttachmentStatus::Submitted->value)
            ->where(function ($q) use ($order, $itemIds) {
                $q->where('order_id', $order->id)
                    ->orWhereIn('order_item_id', $itemIds)
                    ->when($order->source_request_id, fn ($q, $id) => $q->orWhere('order_request_id', $id));
            })
            ->orderBy('id')
            ->get();

        return view('admin.orders.show', [
            'order' => $order,
            'orderRequest' => null,
            'customer' => $order->customer,
            'attachments' => $attachments,
        ]);
    }

    public function showRequest(
        OrderRequest $orderRequest,
        StatusTransitionService $transitions,
        RequestConversionService $conversion,
    ): View
    {
        // Request tidak punya policy sendiri; hak lihat sama dengan order.
        Gate::authorize('viewAny', Order::class);

        $orderRequest->load(['customer', 'product', 'convertedOrder']);

        $attachments = $orderRequest->attachments()
            ->where('status', AttachmentStatus::Submitted->value)
            ->orderBy('id')
            ->get();

        return view('admin.orders.show', [
            'order' => null,
            'orderRequest' => $orderRequest,
            'customer' => $orderRequest->customer,
            'attachments' => $attachments,
            // Pilihan status manual diambil dari aturan tim; "Penawaran Dikirim" lewat form penawaran.
            'manualTargets' => collect($transitions->allowedRequestTransitions($orderRequest->status))
                ->reject(fn ($status) => $status === \App\Enums\RequestStatus::QuotationSent)
                ->mapWithKeys(fn ($status) => [$status->value => $status->label()])
                ->all(),
            'canQuote' => QuotationService::canQuote($orderRequest),
            'canConvert' => $conversion->canConvert($orderRequest),
            'convertBlocker' => $conversion->blocker($orderRequest),
        ]);
    }

    /** Cari di kode, nama pelanggan, dan nomor WhatsApp. */
    private function searchClosure(string $search): \Closure
    {
        $like = '%'.addcslashes($search, '\\%_').'%';
        $digits = preg_replace('/\D+/', '', $search) ?? '';
        $phoneLike = strlen($digits) >= 3 ? '%'.PhoneNumber::normalize($search).'%' : null;

        return function ($query) use ($like, $phoneLike) {
            $query->where('code', 'like', $like)
                ->orWhereHas('customer', function ($customer) use ($like, $phoneLike) {
                    $customer->where('name', 'like', $like);

                    if ($phoneLike) {
                        $customer->orWhere('whatsapp_number', 'like', $phoneLike);
                    }
                });
        };
    }

    /** @return array<string, string> value => label */
    private function options(string $enumClass): array
    {
        return collect($enumClass::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->all();
    }
}
