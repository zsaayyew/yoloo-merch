<?php

namespace App\Enums;

enum AttachmentStatus: string
{
    case Temporary = 'temporary';
    case Submitted = 'submitted';

    public function label(): string
    {
        return match ($this) {
            self::Temporary => 'Sementara',
            self::Submitted => 'Terkirim',
        };
    }
}
