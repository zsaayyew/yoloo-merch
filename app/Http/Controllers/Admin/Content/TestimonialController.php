<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the testimonials (ADM-FR-007).
     */
    public function index(): View
    {
        return view('admin.content.testimonials.index', [
            'testimonials' => Testimonial::query()->ordered()->get(),
        ]);
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create(): View
    {
        return view('admin.content.testimonials.form', [
            'testimonial' => new Testimonial(['rating' => 5, 'is_published' => true]),
        ]);
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request): RedirectResponse
    {
        Testimonial::query()->create($this->validated($request));

        return redirect()
            ->route('admin.content.testimonials.index')
            ->with('status', 'Testimoni berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given testimonial.
     */
    public function edit(Testimonial $testimonial): View
    {
        return view('admin.content.testimonials.form', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Update the given testimonial.
     */
    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validated($request, $testimonial));

        return redirect()
            ->route('admin.content.testimonials.index')
            ->with('status', 'Testimoni berhasil diperbarui.');
    }

    /**
     * Toggle the publication state of the given testimonial.
     */
    public function publish(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_published' => ! $testimonial->is_published]);

        return redirect()
            ->route('admin.content.testimonials.index')
            ->with('status', $testimonial->is_published
                ? 'Testimoni ditampilkan di website.'
                : 'Testimoni disembunyikan dari website.');
    }

    /**
     * Remove the given testimonial.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->photo_path) {
            Storage::disk('public')->delete($testimonial->photo_path);
        }

        $testimonial->delete();

        return redirect()
            ->route('admin.content.testimonials.index')
            ->with('status', 'Testimoni berhasil dihapus.');
    }

    /**
     * Validate the testimonial payload.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Testimonial $testimonial = null): array
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_role' => ['nullable', 'string', 'max:120'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'quote' => ['required', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['photo']);

        $photo = $request->file('photo');

        if ($photo instanceof UploadedFile) {
            if ($testimonial?->photo_path) {
                Storage::disk('public')->delete($testimonial->photo_path);
            }

            $data['photo_path'] = $photo->store('testimonials', 'public');
        }

        return $data;
    }
}
