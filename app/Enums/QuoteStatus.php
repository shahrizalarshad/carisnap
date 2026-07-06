<?php

namespace App\Enums;

enum QuoteStatus: string
{
    case Sent = 'sent';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Expired = 'expired';
}
