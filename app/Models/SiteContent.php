<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

#[Fillable(['page', 'section', 'title', 'subtitle', 'body', 'image_path', 'is_published', 'sort_order'])]
class SiteContent extends Model
{
    public const PAGE_HOME = 'home';

    public const PAGE_ABOUT = 'about';

    /**
     * Halaman yang punya konten yang dapat disunting dari dashboard.
     *
     * @var array<string, string>
     */
    public const PAGES = [
        self::PAGE_HOME => 'Beranda',
        self::PAGE_ABOUT => 'Tentang Kami',
    ];

    /**
     * Section yang tersedia per halaman beserta labelnya (ADM-FR-003, ADM-FR-007).
     *
     * @var array<string, array<string, string>>
     */
    public const SECTIONS = [
        self::PAGE_HOME => [
            'hero' => 'Hero',
            'intro' => 'Perkenalan',
            'services' => 'Layanan',
            'advantages' => 'Keunggulan',
            'cta' => 'Ajakan Bertindak',
        ],
        self::PAGE_ABOUT => [
            'profile' => 'Profil Usaha',
            'vision-mission' => 'Visi & Misi',
            'values-advantages' => 'Nilai & Keunggulan',
            'ordering-guide' => 'Panduan Pemesanan',
        ],
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    /**
     * Scope a query to published content only.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to one page.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page);
    }

    /**
     * Get the content of one page keyed by section name.
     *
     * @return Collection<string, $this>
     */
    public static function forPage(string $page): Collection
    {
        return static::query()
            ->forPage($page)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->keyBy('section');
    }

    /**
     * Get the section label used in the dashboard.
     */
    public function label(): string
    {
        return self::SECTIONS[$this->page][$this->section] ?? ucfirst($this->section);
    }
}
