<?php

namespace App\Enums;

enum AvailabilityStatus: string
{
    case Available = 'available';
    case Booked = 'booked';
    case Tentative = 'tentative';
}
