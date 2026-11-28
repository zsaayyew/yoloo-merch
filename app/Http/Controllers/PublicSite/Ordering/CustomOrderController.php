<?php

namespace App\Http\Controllers\PublicSite\Ordering;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\CustomOrderRequest;
use App\Models\OrderRequest;
use App\Models\Product;
use App\Services\Catalog\CatalogData;
use App\Services\Catalog\ConfigurationPayloadBuilder;
use App\Services\Ordering\AttachmentService;
use App\Services\Ordering\CustomRequestSubmissionService;
use App\Services\Ordering\OrderSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        private readonly AttachmentService $attachmentService,
        private readonly ConfigurationPayloadBuilder $payloadBuilder,
        private readonly OrderSubmissionService $orderSubmissionService,
        private readonly CatalogData $catalog,
    ) {}

    public function create(Request $request): View
    {
        $product = $this->findReferenceProduct($request->query('produk'));
        $quantity = filter_var($request->query('jumlah'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
        $referenceOptions = $this->referenceOptions($product, (array) $request->query('opsi', []), $quantity ?? 1);
        $token = $this->submissionService->issueToken($request->session()->getId());
        $uploadToken = $this->attachmentService->uploadToken($request->session());

        return view('public.custom-orders.create', [
            'product' => $product,
            'referenceOptions' => $referenceOptions,
            'prefillQuantity' => $quantity,
            'submissionToken' => $token->token,
            // File yang sudah diunggah tetap tampil jika formulir kembali karena error validasi.
            'attachments' => $this->attachmentService->pendingForCustomOrder($uploadToken),
        ]);
    }

    public function store(CustomOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $slug = $data['product_slug'] ?? null;
        $product = $this->findReferenceProduct($slug);

        if ($slug !== null && $product === null) {
            throw ValidationException::withMessages([
                'product_slug' => 'Produk referensi sudah tidak tersedia. Silakan pilih produk lain dari katalog.',
            ]);
        }

        $optionValueIds = $data['option_value_ids'] ?? [];
        if ($product && $optionValueIds !== []) {
            try {
                $data['options_snapshot'] = $this->payloadBuilder->build(
                    $product, $optionValueIds, (int) $data['quantity'], forQuotation: true,
                )['options'];
            } catch (ValidationException $exception) {
                throw ValidationException::withMessages([
                    'option_value_ids' => collect($exception->errors())->flatten()->first(),
                ]);
            }
        }

        $orderRequest = $this->submissionService->submit(
            $data,
            $product,
            $request->session()->getId(),
            $this->attachmentService->uploadToken($request->session()),
        );

        // Email hanya untuk kiriman baru, bukan saat tombol ditekan dua kali.
        if ($orderRequest->wasRecentlyCreated) {
            $this->orderSubmissionService->sendReceipt($orderRequest, $data['email'] ?? null);
        }

        // Simpan kode di session supaya halaman berhasil hanya bisa dibuka sesi pengirim.
        $codes = $request->session()->get(self::SESSION_KEY, []);
        $codes[] = $orderRequest->code;
        $request->session()->put(self::SESSION_KEY, array_values(array_unique($codes)));

        return redirect()->route('custom-orders.success', $orderRequest->code);
    }

    public function success(Request $request, string $code): View
    {
        abort_unless(in_array($code, $request->session()->get(self::SESSION_KEY, []), true), 404);

        $orderRequest = OrderRequest::with(['customer', 'attachments'])->where('code', $code)->firstOrFail();

        return view('public.custom-orders.success', [
            'orderRequest' => $orderRequest,
            'whatsappUrl' => $this->orderSubmissionService->whatsappUrl(
                $orderRequest->code,
                $orderRequest->customer->name,
                isCustomRequest: true,
            ),
        ]);
    }

    /**
     * Opsi referensi yang dipilih pelanggan di halaman produk (tombol "Minta penawaran").
     * Divalidasi oleh CAT dalam mode penawaran (tanpa hitung harga). Jika tidak valid lagi
     * (misalnya opsi sudah dinonaktifkan), opsi diabaikan dan formulir tetap bisa dikirim.
     *
     * @return array<int, array{group: string, value: string, option_value_id: int}>
     */
    private function referenceOptions(?Product $product, array $optionValueIds, int $quantity): array
    {
        $ids = array_values(array_filter($optionValueIds, fn ($id) => filter_var($id, FILTER_VALIDATE_INT) !== false));

        if (! $product || $ids === []) {
            return [];
        }

        try {
            return $this->payloadBuilder->build($product, array_map('intval', $ids), max(1, $quantity), forQuotation: true)['options'];
        } catch (ValidationException) {
            return [];
        }
    }

    /**
     * Produk referensi dari ?produk={slug} (dikirim dari tombol katalog CAT).
     * Hanya produk aktif yang dipakai; jika tidak ditemukan, formulir tetap bisa diisi.
     */
    private function findReferenceProduct(mixed $slug): ?Product
    {
        return is_string($slug) && $slug !== '' ? $this->catalog->product($slug) : null;
    }
}
