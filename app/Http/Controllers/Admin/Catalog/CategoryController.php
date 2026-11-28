<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the product categories (ADM-FR-004).
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        return view('admin.categories.index', [
            'search' => $search,
            'categories' => Category::query()
                ->withCount('products')
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
                })
                ->ordered()
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category(['is_active' => true]),
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Category::uniqueSlug($data['name']);

        Category::query()->create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given category.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.form', [
            'category' => $category,
        ]);
    }

    /**
     * Update the given category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validated($request, $category);
        $data['slug'] = Category::uniqueSlug($data['name'], $category->id);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori produk berhasil diperbarui.');
    }

    /**
     * Remove the given category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->withErrors([
                'category' => 'Kategori masih memiliki produk. Pindahkan atau arsipkan produknya lebih dulu.',
            ]);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori produk berhasil dihapus.');
    }

    /**
     * Validate the category payload.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                function (string $attribute, mixed $value, \Closure $fail) use ($category): void {
                    $duplicate = Category::query()
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $value))])
                        ->when($category !== null, fn ($query) => $query->whereKeyNot($category->getKey()))
                        ->exists();

                    if ($duplicate) {
                        $fail('Nama kategori produk ini sudah dipakai. Gunakan nama lain.');
                    }
                },
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
