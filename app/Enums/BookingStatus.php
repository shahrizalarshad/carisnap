<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Quoted = 'quoted';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Quoted => 'Sebut Harga Dihantar',
            self::Accepted => 'Diterima',
            self::Declined => 'Ditolak',
            self::Expired => 'Tamat Tempoh',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Quoted => 'brand',
            self::Accepted => 'green',
            self::Declined => 'red',
            self::Expired => 'gray',
        };
    }
}
