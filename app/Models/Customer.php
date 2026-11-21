<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Bersama ORD + FIN — Pelanggan (guest, tanpa akun login).
 */
class Customer extends Model
{
    protected $fillable = [
        'name',
        'whatsapp_number',
        'email',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orderRequests(): HasMany
    {
        return $this->hasMany(OrderRequest::class);
    }

    /**
     * Cari pelanggan dari nomor WhatsApp ternormalisasi, atau buat baru (FIN-BR-012).
     * Nama pelanggan lama tidak ditimpa (dikelola admin); email diisi hanya jika sebelumnya kosong.
     * Panggil di dalam DB::transaction().
     *
     * @param  array{name: string, whatsapp_number: string, email?: string|null}  $contact
     */
    public static function resolveFromContact(array $contact): self
    {
        $customer = static::query()
            ->where('whatsapp_number', $contact['whatsapp_number'])
            ->lockForUpdate()
            ->first();

        if (! $customer) {
            return static::create([
                'name' => $contact['name'],
                'whatsapp_number' => $contact['whatsapp_number'],
                'email' => $contact['email'] ?? null,
                'is_active' => true,
            ]);
        }

        if (blank($customer->email) && filled($contact['email'] ?? null)) {
            $customer->update(['email' => $contact['email']]);
        }

        return $customer;
    }
}
