<?php

namespace App\Enums;

enum RequestStatus: string
{
    case New = 'new';
    case UnderReview = 'under_review';
    case AwaitingInfo = 'awaiting_info';
    case QuotationSent = 'quotation_sent';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Expired = 'expired';
    case Converted = 'converted';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Baru Diterima',
            self::UnderReview => 'Ditinjau Admin',
            self::AwaitingInfo => 'Menunggu Informasi',
            self::QuotationSent => 'Penawaran Dikirim',
            self::Approved => 'Disetujui Pelanggan',
            self::Rejected => 'Ditolak',
            self::Expired => 'Kedaluwarsa',
            self::Converted => 'Dikonversi Menjadi Order',
        };
    }
}
