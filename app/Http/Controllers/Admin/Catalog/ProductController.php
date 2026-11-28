<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Catalog\CatalogData;
use App\Services\Content\MediaLibrary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly MediaLibrary $mediaLibrary) {}

    /**
     * Display a listing of the products (ADM-FR-004).
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $categoryId = $request->integer('category');
        $status = (string) $request->query('status', '');

        $products = Product::query()
            ->with('category')
            ->withCount(['images', 'optionGroups'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->when(array_key_exists($status, Product::STATUSES), fn ($query) => $query->where('status', $status))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::query()->ordered()->get(),
            'search' => $search,
            'categoryId' => $categoryId,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product([
                'price_model' => 'quotation',
                'status' => Product::STATUS_DRAFT,
                'allows_direct_purchase' => true,
            ]),
            'categories' => Category::query()->ordered()->get(),
            'priceModels' => CatalogData::PRICE_MODELS,
            'statuses' => Product::STATUSES,
            'specifications' => [],
            'images' => new Collection,
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Product::uniqueSlug($data['name']);

        $product = Product::query()->create($data);

        $this->storeImages($product, $request);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Produk berhasil ditambahkan. Lengkapi gambar dan opsinya di halaman ini.');
    }

    /**
     * Show the form for editing the given product.
     */
    public function edit(Product $product): View
    {
        $product->load(['images', 'optionGroups.values']);

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->ordered()->get(),
            'priceModels' => CatalogData::PRICE_MODELS,
            'statuses' => Product::STATUSES,
            'specifications' => (array) ($product->specifications ?? []),
            'images' => $product->images,
        ]);
    }

    /**
     * Update the given product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Product::uniqueSlug($data['name'], $product->id);

        $product->update($data);

        $this->storeImages($product, $request);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Produk berhasil diperbarui.');
    }

    /**
     * Archive the given product so it disappears from the public catalog.
     */
    public function archive(Product $product): RedirectResponse
    {
        $product->update(['status' => Product::STATUS_ARCHIVED]);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk "'.$product->name.'" diarsipkan.');
    }

    /**
     * Mark the given image as the primary photo.
     */
    public function primaryImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Foto utama diperbarui.');
    }

    /**
     * Remove the given product image.
     */
    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        $wasPrimary = $image->is_primary;

        $this->mediaLibrary->detach($image, 'image');

        $media = Media::query()->where('path', $image->path)->first();
        $media?->delete();

        Storage::disk('public')->delete($image->path);
        $image->delete();

        if ($wasPrimary) {
            $product->images()->first()?->update(['is_primary' => true]);
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Foto produk dihapus.');
    }

    /**
     * Store the uploaded product images.
     */
    private function storeImages(Product $product, Request $request): void
    {
        $files = $request->file('images', []);

        if ($files === []) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $lastSort = (int) $product->images()->max('sort_order');

        foreach ($files as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $media = $this->mediaLibrary->record($file, $request->user(), 'products', $product->name);

            $image = $product->images()->create([
                'path' => $media->path,
                'alt_text' => $product->name,
                'is_primary' => ! $hasPrimary,
                'sort_order' => ++$lastSort,
            ]);

            $this->mediaLibrary->attach($image, 'image', $media);

            $hasPrimary = true;
        }
    }

    /**
     * Validate the product payload.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price_model' => ['required', Rule::in(array_keys(CatalogData::PRICE_MODELS))],
            'status' => ['required', Rule::in(array_keys(Product::STATUSES))],
            'specifications' => ['nullable', 'array', 'max:20'],
            'specifications.*.label' => ['nullable', 'string', 'max:60'],
            'specifications.*.value' => ['nullable', 'string', 'max:120'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['allows_direct_purchase'] = $request->boolean('allows_direct_purchase');
        $data['specifications'] = collect($data['specifications'] ?? [])
            ->filter(fn ($row) => filled($row['label'] ?? null))
            ->mapWithKeys(fn ($row) => [trim((string) $row['label']) => trim((string) ($row['value'] ?? ''))])
            ->all() ?: null;

        unset($data['images']);

        return $data;
    }
}
