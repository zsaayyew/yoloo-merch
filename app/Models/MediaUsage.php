<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['media_id', 'usable_type', 'usable_id', 'field'])]
class MediaUsage extends Model
{
    /**
     * Get the media file used here.
     *
     * @return BelongsTo<Media, $this>
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Get the record that uses the media file.
     *
     * @return MorphTo<Model, $this>
     */
    public function usable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Describe the usage in a form the dashboard can display.
     */
    public function label(): string
    {
        $usable = $this->usable;

        return match (true) {
            $usable instanceof ProductImage => 'Produk: '.($usable->product?->name ?? 'tanpa nama'),
            $usable instanceof GalleryItem => 'Galeri: '.$usable->title,
            $usable instanceof SiteContent => 'Konten '.SiteContent::PAGES[$usable->page].' · '.$usable->label(),
            default => 'Pemakaian lain',
        };
    }
}
