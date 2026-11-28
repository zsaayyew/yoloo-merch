<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['path', 'original_name', 'mime_type', 'size', 'alt_text', 'uploaded_by'])]
class Media extends Model
{
    /**
     * Tipe berkas yang boleh diunggah ke pustaka media (ADM-FR-008).
     *
     * @var list<string>
     */
    public const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

    public const MAX_SIZE_KB = 5120;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    /**
     * Get the account that uploaded the file.
     *
     * @return BelongsTo<User, $this>
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the records that use this file (produk, konten, galeri).
     *
     * @return HasMany<MediaUsage, $this>
     */
    public function usages(): HasMany
    {
        return $this->hasMany(MediaUsage::class);
    }

    /**
     * Determine whether the file is used somewhere on the website.
     */
    public function isUsed(): bool
    {
        return $this->usages()->exists();
    }

    /**
     * Get the public URL of the stored file.
     */
    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * Determine whether the file is an image.
     */
    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    /**
     * Get the file size in a human readable form.
     */
    public function sizeForHumans(): string
    {
        $kilobytes = max(1, (int) round($this->size / 1024));

        return $kilobytes >= 1024
            ? number_format($kilobytes / 1024, 1, ',', '.').' MB'
            : $kilobytes.' KB';
    }
}
