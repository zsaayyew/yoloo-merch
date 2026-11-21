<?php

namespace App\Enums;

enum OrderChannel: string
{
    case Website = 'website';
    case Whatsapp = 'whatsapp';
    case Instagram = 'instagram';
    case WalkIn = 'walk_in';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website',
            self::Whatsapp => 'WhatsApp',
            self::Instagram => 'Instagram',
            self::WalkIn => 'Langsung',
        };
    }
}
