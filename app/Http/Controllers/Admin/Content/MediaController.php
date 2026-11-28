<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\Content\MediaLibrary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(private readonly MediaLibrary $mediaLibrary) {}

    /**
     * Display a listing of the media library (PAGE-ADM-005, ADM-FR-008).
     */
    public function index(): View
    {
        return view('admin.media.index', [
            'media' => Media::query()
                ->with(['usages.usable'])
                ->withCount('usages')
                ->latest('id')
                ->paginate(24),
        ]);
    }

    /**
     * Store one or more uploaded files in the library.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'max:10'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:'.Media::MAX_SIZE_KB],
        ]);

        $stored = 0;

        foreach ($request->file('files', []) as $file) {
            $media = $this->mediaLibrary->record($file, $request->user());

            if ($media->exists) {
                $stored++;
            }
        }

        return redirect()
            ->route('admin.media.index')
            ->with('status', $stored.' berkas berhasil diunggah ke pustaka media.');
    }

    /**
     * Update the alternative text of the given media.
     */
    public function update(Request $request, Media $media): RedirectResponse
    {
        $data = $request->validate([
            'alt_text' => ['nullable', 'string', 'max:200'],
        ]);

        $media->update($data);

        return redirect()
            ->route('admin.media.index')
            ->with('status', 'Keterangan media berhasil diperbarui.');
    }

    /**
     * Replace the stored file of the given media without losing its usages.
     */
    public function replaceFile(Request $request, Media $media): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:'.Media::MAX_SIZE_KB],
        ]);

        $this->mediaLibrary->replace($media, $request->file('file'));

        return redirect()
            ->route('admin.media.index')
            ->with('status', 'Berkas media berhasil diganti. Pemakaian di halaman tetap tersimpan.');
    }

    /**
     * Remove the given media and its file.
     */
    public function destroy(Media $media): RedirectResponse
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();

        return redirect()
            ->route('admin.media.index')
            ->with('status', 'Media berhasil dihapus.');
    }
}
