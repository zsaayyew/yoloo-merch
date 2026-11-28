<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Collaboration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CollaborationController extends Controller
{
    /**
     * Display a listing of the collaborations (ADM-FR-007).
     */
    public function index(): View
    {
        return view('admin.content.collaborations.index', [
            'collaborations' => Collaboration::query()->ordered()->get(),
        ]);
    }

    /**
     * Show the form for creating a new collaboration.
     */
    public function create(): View
    {
        return view('admin.content.collaborations.form', [
            'collaboration' => new Collaboration(['is_published' => true]),
        ]);
    }

    /**
     * Store a newly created collaboration.
     */
    public function store(Request $request): RedirectResponse
    {
        Collaboration::query()->create($this->validated($request));

        return redirect()
            ->route('admin.content.collaborations.index')
            ->with('status', 'Kolaborasi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given collaboration.
     */
    public function edit(Collaboration $collaboration): View
    {
        return view('admin.content.collaborations.form', [
            'collaboration' => $collaboration,
        ]);
    }

    /**
     * Update the given collaboration.
     */
    public function update(Request $request, Collaboration $collaboration): RedirectResponse
    {
        $collaboration->update($this->validated($request, $collaboration));

        return redirect()
            ->route('admin.content.collaborations.index')
            ->with('status', 'Kolaborasi berhasil diperbarui.');
    }

    /**
     * Toggle the publication state of the given collaboration.
     */
    public function publish(Collaboration $collaboration): RedirectResponse
    {
        $collaboration->update(['is_published' => ! $collaboration->is_published]);

        return redirect()
            ->route('admin.content.collaborations.index')
            ->with('status', $collaboration->is_published
                ? 'Kolaborasi ditampilkan di website.'
                : 'Kolaborasi disembunyikan dari website.');
    }

    /**
     * Remove the given collaboration.
     */
    public function destroy(Collaboration $collaboration): RedirectResponse
    {
        if ($collaboration->logo_path) {
            Storage::disk('public')->delete($collaboration->logo_path);
        }

        $collaboration->delete();

        return redirect()
            ->route('admin.content.collaborations.index')
            ->with('status', 'Kolaborasi berhasil dihapus.');
    }

    /**
     * Validate the collaboration payload.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Collaboration $collaboration = null): array
    {
        $data = $request->validate([
            'partner_name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1000'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['logo']);

        $logo = $request->file('logo');

        if ($logo instanceof UploadedFile) {
            if ($collaboration?->logo_path) {
                Storage::disk('public')->delete($collaboration->logo_path);
            }

            $data['logo_path'] = $logo->store('collaborations', 'public');
        }

        return $data;
    }
}
