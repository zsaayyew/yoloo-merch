<?php

namespace App\Services\Content;

use App\Models\Media;
use App\Models\MediaUsage;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaLibrary
{
    /**
     * Folder default penyimpanan berkas yang diunggah dari pustaka media.
     */
    public const DEFAULT_DIRECTORY = 'media';

    /**
     * Store an uploaded file and register it in the media library (ADM-FR-008).
     */
    public function record(
        UploadedFile $file,
        ?User $user = null,
        string $directory = self::DEFAULT_DIRECTORY,
        ?string $altText = null,
    ): Media {
        return Media::query()->create([
            'path' => $file->store($directory, 'public'),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => (int) $file->getSize(),
            'alt_text' => $altText,
            'uploaded_by' => $user?->id,
        ]);
    }

    /**
     * Determine whether the given stored path still exists on the public disk.
     */
    public function hasStoredFile(string $path): bool
    {
        return $path !== '' && Storage::disk('public')->exists($path);
    }

    /**
     * Register a file that was stored before the media library existed.
     */
    public function registerStoredFile(string $path, ?string $altText = null, ?int $uploadedBy = null): ?Media
    {
        if (! $this->hasStoredFile($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        return Media::query()->firstOrCreate(
            ['path' => $path],
            [
                'original_name' => basename($path),
                'mime_type' => $disk->mimeType($path) ?: 'application/octet-stream',
                'size' => (int) ($disk->size($path) ?: 0),
                'alt_text' => $altText,
                'uploaded_by' => $uploadedBy,
            ],
        );
    }

    /**
     * Record that the given record uses the media file in the given field.
     */
    public function attach(Model $owner, string $field, Media $media): MediaUsage
    {
        return MediaUsage::query()->updateOrCreate(
            [
                'usable_type' => $owner->getMorphClass(),
                'usable_id' => $owner->getKey(),
                'field' => $field,
            ],
            ['media_id' => $media->id],
        );
    }

    /**
     * Forget the media usage of one field, keeping the file in the library.
     */
    public function detach(Model $owner, string $field): void
    {
        MediaUsage::query()
            ->where('usable_type', $owner->getMorphClass())
            ->where('usable_id', $owner->getKey())
            ->where('field', $field)
            ->delete();
    }

    /**
     * Replace the stored file behind a media entry, keeping its id and usages.
     */
    public function replace(Media $media, UploadedFile $file): Media
    {
        $directory = trim(dirname($media->path), '.');
        $previousPath = $media->path;

        $media->update([
            'path' => $file->store($directory === '' ? self::DEFAULT_DIRECTORY : $directory, 'public'),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => (int) $file->getSize(),
        ]);

        if ($previousPath !== $media->path) {
            Storage::disk('public')->delete($previousPath);
        }

        return $media;
    }
}
