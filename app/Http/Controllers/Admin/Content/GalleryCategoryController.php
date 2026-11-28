<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryCategoryController extends Controller
{
    /**
     * Display a listing of the gallery categories (ADM-FR-007).
     */
    public function index(): View
    {
        return view('admin.content.gallery-categories.index', [
            'categories' => GalleryCategory::query()->withCount('items')->ordered()->get(),
        ]);
    }

    /**
     * Show the form for creating a new gallery category.
     */
    public function create(): View
    {
        return view('admin.content.gallery-categories.form', [
            'category' => new GalleryCategory(['is_active' => true]),
        ]);
    }

    /**
     * Store a newly created gallery category.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = GalleryCategory::uniqueSlug($data['name']);

        GalleryCategory::query()->create($data);

        return redirect()
            ->route('admin.content.gallery-categories.index')
            ->with('status', 'Kategori galeri berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given gallery category.
     */
    public function edit(GalleryCategory $galleryCategory): View
    {
        return view('admin.content.gallery-categories.form', [
            'category' => $galleryCategory,
        ]);
    }

    /**
     * Update the given gallery category.
     */
    public function update(Request $request, GalleryCategory $galleryCategory): RedirectResponse
    {
        $data = $this->validated($request, $galleryCategory);
        $data['slug'] = GalleryCategory::uniqueSlug($data['name'], $galleryCategory->id);

        $galleryCategory->update($data);

        return redirect()
            ->route('admin.content.gallery-categories.index')
            ->with('status', 'Kategori galeri berhasil diperbarui.');
    }

    /**
     * Remove the given gallery category.
     */
    public function destroy(GalleryCategory $galleryCategory): RedirectResponse
    {
        if ($galleryCategory->items()->exists()) {
            return back()->withErrors([
                'category' => 'Kategori masih memiliki item galeri. Pindahkan atau hapus itemnya lebih dulu.',
            ]);
        }

        $galleryCategory->delete();

        return redirect()
            ->route('admin.content.gallery-categories.index')
            ->with('status', 'Kategori galeri berhasil dihapus.');
    }

    /**
     * Validate the gallery category payload.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?GalleryCategory $category = null): array
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                function (string $attribute, mixed $value, \Closure $fail) use ($category): void {
                    $duplicate = GalleryCategory::query()
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $value))])
                        ->when($category !== null, fn ($query) => $query->whereKeyNot($category->getKey()))
                        ->exists();

                    if ($duplicate) {
                        $fail('Nama kategori galeri ini sudah dipakai. Gunakan nama lain.');
                    }
                },
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
