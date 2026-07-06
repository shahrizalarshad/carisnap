<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Quoted = 'quoted';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Expired = 'expired';
}
