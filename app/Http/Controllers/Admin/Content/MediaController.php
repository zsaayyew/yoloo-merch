<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    /**
     * Display a listing of the media library (PAGE-ADM-005, ADM-FR-008).
     */
    public function index(): View
    {
        return view('admin.media.index', [
            'media' => Media::query()->latest('id')->paginate(24),
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
            $media = Media::query()->create([
                'path' => $file->store('media', 'public'),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => $request->user()?->id,
            ]);

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
