<?php

namespace App\Http\Controllers\PublicSite\Ordering;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\CustomOrderRequest;
use App\Models\OrderRequest;
use App\Models\Product;
use App\Services\Ordering\CustomRequestSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PRD-03 ORD — Custom order request (PAGE-ORD-005, PAGE-ORD-006).
 * Route: custom-orders.create, custom-orders.store, custom-orders.success.
 */
class CustomOrderController extends Controller
{
    /** Kunci session berisi kode request yang dikirim oleh sesi ini. */
    private const SESSION_KEY = 'ordering.custom_order_codes';

    public function __construct(
        private readonly CustomRequestSubmissionService $submissionService,
    ) {}

    public function create(Request $request): View
    {
        $product = $this->findReferenceProduct($request->query('produk'));
        $token = $this->submissionService->issueToken($request->session()->getId());

        return view('public.custom-orders.create', [
            'product' => $product,
            'submissionToken' => $token->token,
        ]);
    }

    public function store(CustomOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $product = $this->findReferenceProduct($data['product_slug'] ?? null);

        $orderRequest = $this->submissionService->submit($data, $product, $request->session()->getId());

        // Simpan kode di session supaya halaman berhasil hanya bisa dibuka sesi pengirim.
        $codes = $request->session()->get(self::SESSION_KEY, []);
        $codes[] = $orderRequest->code;
        $request->session()->put(self::SESSION_KEY, array_values(array_unique($codes)));

        return redirect()->route('custom-orders.success', $orderRequest->code);
    }

    public function success(Request $request, string $code): View
    {
        abort_unless(in_array($code, $request->session()->get(self::SESSION_KEY, []), true), 404);

        $orderRequest = OrderRequest::with('customer')->where('code', $code)->firstOrFail();

        return view('public.custom-orders.success', [
            'orderRequest' => $orderRequest,
        ]);
    }

    /**
     * Produk referensi dari ?produk={slug} (dikirim dari tombol katalog CAT).
     * Hanya produk aktif yang dipakai; jika tidak ditemukan, formulir tetap bisa diisi.
     */
    private function findReferenceProduct(?string $slug): ?Product
    {
        if (blank($slug)) {
            return null;
        }

        $query = Product::query()->where('slug', $slug);

        // scopeActive() disediakan pemilik model Product (kontrak AI_CONTEXT bagian 7 poin 1).
        if (method_exists(Product::class, 'scopeActive')) {
            $query->active();
        }

        return $query->first();
    }
}
