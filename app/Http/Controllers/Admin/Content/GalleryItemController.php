<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryItemController extends Controller
{
    /**
     * Display a listing of the gallery items (ADM-FR-007).
     */
    public function index(): View
    {
        return view('admin.content.gallery.index', [
            'items' => GalleryItem::query()->with('category')->ordered()->get(),
        ]);
    }

    /**
     * Show the form for creating a new gallery item.
     */
    public function create(): View
    {
        return view('admin.content.gallery.form', [
            'item' => new GalleryItem(['is_published' => true]),
            'categories' => GalleryCategory::query()->ordered()->get(),
        ]);
    }

    /**
     * Store a newly created gallery item.
     */
    public function store(Request $request): RedirectResponse
    {
        GalleryItem::query()->create($this->validated($request));

        return redirect()
            ->route('admin.content.gallery.index')
            ->with('status', 'Item galeri berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given gallery item.
     */
    public function edit(GalleryItem $gallery): View
    {
        return view('admin.content.gallery.form', [
            'item' => $gallery,
            'categories' => GalleryCategory::query()->ordered()->get(),
        ]);
    }

    /**
     * Update the given gallery item.
     */
    public function update(Request $request, GalleryItem $gallery): RedirectResponse
    {
        $gallery->update($this->validated($request, $gallery));

        return redirect()
            ->route('admin.content.gallery.index')
            ->with('status', 'Item galeri berhasil diperbarui.');
    }

    /**
     * Toggle the publication state of the given gallery item.
     */
    public function publish(GalleryItem $gallery): RedirectResponse
    {
        $gallery->update(['is_published' => ! $gallery->is_published]);

        return redirect()
            ->route('admin.content.gallery.index')
            ->with('status', $gallery->is_published
                ? 'Item galeri ditampilkan di website.'
                : 'Item galeri disembunyikan dari website.');
    }

    /**
     * Remove the given gallery item.
     */
    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()
            ->route('admin.content.gallery.index')
            ->with('status', 'Item galeri berhasil dihapus.');
    }

    /**
     * Validate the gallery item payload.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?GalleryItem $item = null): array
    {
        $data = $request->validate([
            'gallery_category_id' => ['nullable', 'integer', 'exists:gallery_categories,id'],
            'title' => ['required', 'string', 'max:160'],
            'caption' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['image']);

        $image = $request->file('image');

        if ($image instanceof UploadedFile) {
            if ($item?->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }

            $data['image_path'] = $image->store('gallery', 'public');
        }

        return $data;
    }
}
