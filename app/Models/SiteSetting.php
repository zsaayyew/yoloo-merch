<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['setting_key', 'setting_value', 'setting_group', 'setting_label', 'input_type'])]
class SiteSetting extends Model
{
    public const GROUP_PROFILE = 'profil';

    /**
     * Pengaturan profil usaha dan kontak yang tampil di website publik (ADM-FR-007).
     *
     * @var array<string, array{label: string, type: string}>
     */
    public const FIELDS = [
        'business_name' => ['label' => 'Nama Usaha', 'type' => 'text'],
        'tagline' => ['label' => 'Tagline', 'type' => 'text'],
        'description' => ['label' => 'Deskripsi Singkat', 'type' => 'textarea'],
        'address' => ['label' => 'Alamat', 'type' => 'textarea'],
        'phone' => ['label' => 'Telepon', 'type' => 'text'],
        'whatsapp' => ['label' => 'Nomor WhatsApp', 'type' => 'text'],
        'email' => ['label' => 'Email', 'type' => 'email'],
        'instagram' => ['label' => 'Instagram', 'type' => 'text'],
        'operating_hours' => ['label' => 'Jam Operasional', 'type' => 'text'],
        'maps_url' => ['label' => 'Tautan Peta', 'type' => 'url'],
    ];

    /**
     * Read one setting value.
     */
    public static function value(string $key, ?string $default = null): ?string
    {
        return static::query()->where('setting_key', $key)->value('setting_value') ?? $default;
    }

    /**
     * Write one setting value, creating the row when needed.
     */
    public static function put(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'setting_group' => self::GROUP_PROFILE,
                'setting_label' => self::FIELDS[$key]['label'] ?? ucfirst(str_replace('_', ' ', $key)),
                'input_type' => self::FIELDS[$key]['type'] ?? 'text',
            ],
        );
    }

    /**
     * Get every profile setting keyed by its key.
     *
     * @return array<string, string|null>
     */
    public static function profile(): array
    {
        $stored = static::query()->pluck('setting_value', 'setting_key')->all();

        return collect(self::FIELDS)
            ->map(fn (array $field, string $key): ?string => $stored[$key] ?? null)
            ->all();
    }
}
