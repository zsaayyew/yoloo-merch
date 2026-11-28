<?php

namespace App\Http\Controllers\Admin\Content\Concerns;

use App\Models\SiteContent;
use App\Services\Content\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

trait UpdatesSiteContent
{
    /**
     * Simpan konten semua section pada satu halaman (ADM-FR-003, ADM-FR-007).
     */
    protected function updateSiteContent(Request $request, string $page): void
    {
        $request->validate([
            'sections' => ['required', 'array'],
            'sections.*.title' => ['nullable', 'string', 'max:200'],
            'sections.*.subtitle' => ['nullable', 'string', 'max:255'],
            'sections.*.body' => ['nullable', 'string', 'max:5000'],
            'sections.*.is_published' => ['nullable', 'boolean'],
            'sections.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $sections = SiteContent::SECTIONS[$page] ?? [];
        $mediaLibrary = app(MediaLibrary::class);

        foreach (array_keys($sections) as $order => $section) {
            $input = (array) $request->input("sections.{$section}", []);

            $content = SiteContent::query()->firstOrNew([
                'page' => $page,
                'section' => $section,
            ]);

            $content->fill([
                'title' => $input['title'] ?? null,
                'subtitle' => $input['subtitle'] ?? null,
                'body' => $input['body'] ?? null,
                'is_published' => (bool) ($input['is_published'] ?? false),
                'sort_order' => $content->exists ? $content->sort_order : $order,
            ]);

            $image = $request->file("sections.{$section}.image");

            $media = null;

            if ($image instanceof UploadedFile) {
                $altText = trim((string) ($input['title'] ?? '')) ?: $content->label();

                $media = $mediaLibrary->record($image, $request->user(), 'content/'.$page, $altText);
                $content->image_path = $media->path;
            }

            $content->save();

            if ($media !== null) {
                $mediaLibrary->detach($content, 'image');
                $mediaLibrary->attach($content, 'image', $media);
            }
        }
    }
}
