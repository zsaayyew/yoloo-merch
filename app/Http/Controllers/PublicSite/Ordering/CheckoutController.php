<?php

namespace App\Http\Controllers\PublicSite\Ordering;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\CheckoutCustomerRequest;
use App\Http\Requests\PublicSite\SubmitOrderRequest;
use App\Models\Order;
use App\Services\Ordering\AttachmentService;
use App\Services\Ordering\CartService;
use App\Services\Ordering\OrderSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * PRD-03 ORD — Checkout guest (PAGE-ORD-002, PAGE-ORD-003, PAGE-ORD-004).
 * Route: checkout.customer, checkout.customer.store, checkout.summary, checkout.submit, orders.success.
 */
class CheckoutController extends Controller
{
    private const SESSION_CUSTOMER = 'ordering.checkout_customer';
    private const SESSION_SEEN_PRICES = 'ordering.checkout_seen_prices';
    private const SESSION_ORDER_CODES = 'ordering.order_codes';

    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderSubmissionService $submissionService,
        private readonly AttachmentService $attachmentService,
    ) {}

    /** PAGE-ORD-002: data pelanggan + informasi pickup. */
    public function customer(Request $request): View|RedirectResponse
    {
        $summary = $this->cartService->summary($this->cartService->current($request));

        if ($redirect = $this->guardCart($summary)) {
            return $redirect;
        }

        $this->attachmentService->uploadToken($request->session()); // siapkan token upload sesi

        return view('public.checkout.customer', $summary + [
            'customer' => $request->session()->get(self::SESSION_CUSTOMER, []),
            'itemAttachments' => $this->itemAttachments($summary),
        ]);
    }

    public function storeCustomer(CheckoutCustomerRequest $request): RedirectResponse
    {
        $request->session()->put(self::SESSION_CUSTOMER, $request->validated());

        return redirect()->route('checkout.summary');
    }

    /** PAGE-ORD-003: ringkasan + persetujuan. */
    public function summary(Request $request): View|RedirectResponse
    {
        $customer = $request->session()->get(self::SESSION_CUSTOMER);

        if (! $customer) {
            return redirect()->route('checkout.customer')
                ->withErrors(['customer' => 'Lengkapi data kontak terlebih dahulu.']);
        }

        $summary = $this->cartService->summary($this->cartService->current($request));

        if ($redirect = $this->guardCart($summary)) {
            return $redirect;
        }

        // Simpan harga yang dilihat pelanggan; submit ditolak jika harga berubah sesudahnya.
        $request->session()->put(self::SESSION_SEEN_PRICES, $this->submissionService->priceFingerprint($summary));
        $token = $this->submissionService->issueToken($request->session()->getId());

        return view('public.checkout.summary', $summary + [
            'customer' => $customer,
            'submissionToken' => $token->token,
            'itemAttachments' => $this->itemAttachments($summary),
        ]);
    }

    public function submit(SubmitOrderRequest $request): RedirectResponse
    {
        $customer = $request->session()->get(self::SESSION_CUSTOMER);

        if (! $customer) {
            return redirect()->route('checkout.customer')
                ->withErrors(['customer' => 'Sesi checkout berakhir. Silakan isi data kontak lagi.']);
        }

        try {
            $order = $this->submissionService->submit(
                $this->cartService->current($request),
                $customer,
                $request->validated('submission_token'),
                $request->session()->getId(),
                $request->session()->get(self::SESSION_SEEN_PRICES),
            );
        } catch (ValidationException $e) {
            $target = array_key_exists('cart', $e->errors()) ? 'cart.index' : 'checkout.summary';

            return redirect()->route($target)->withErrors($e->errors());
        }

        // Email hanya untuk kiriman baru, bukan saat tombol ditekan dua kali.
        if ($order->wasRecentlyCreated) {
            $this->submissionService->sendReceipt($order, $customer['email'] ?? null);
        }

        $codes = $request->session()->get(self::SESSION_ORDER_CODES, []);
        $codes[] = $order->code;
        $request->session()->put(self::SESSION_ORDER_CODES, array_values(array_unique($codes)));
        $request->session()->forget([self::SESSION_CUSTOMER, self::SESSION_SEEN_PRICES]);

        return redirect()->route('orders.success', $order->code);
    }

    /** PAGE-ORD-004: konfirmasi, hanya untuk sesi pengirim. */
    public function success(Request $request, string $code): View
    {
        abort_unless(in_array($code, $request->session()->get(self::SESSION_ORDER_CODES, []), true), 404);

        $order = Order::with(['customer', 'items.attachments'])->where('code', $code)->firstOrFail();

        return view('public.orders.success', [
            'order' => $order,
            'whatsappUrl' => $this->submissionService->whatsappUrl($order->code, $order->customer->name),
        ]);
    }

    /** Lampiran desain sementara, dikelompokkan per cart_item_id. */
    private function itemAttachments(array $summary): \Illuminate\Support\Collection
    {
        return $this->attachmentService->pendingForCartItems(
            $summary['rows']->map(fn (array $row) => $row['item']->id)->all()
        );
    }

    private function guardCart(array $summary): ?RedirectResponse
    {
        if ($summary['rows']->isEmpty()) {
            return redirect()->route('cart.index');
        }

        if ($summary['has_unavailable']) {
            return redirect()->route('cart.index')
                ->withErrors(['cart' => 'Ada produk yang tidak tersedia. Hapus atau ubah dulu sebelum checkout.']);
        }

        return null;
    }
}
