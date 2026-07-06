<?php

namespace App\Actions;

use App\Enums\EventType;
use App\Models\PhotographerProfile;

readonly class CreateBookingRequestData
{
    public function __construct(
        public PhotographerProfile $profile,
        public string $eventDate,
        public string $location,
        public int $budgetFrom,
        public int $budgetTo,
        public EventType $eventType = EventType::Wedding,
        public ?string $message = null,
        public ?int $clientId = null,
        public ?string $guestName = null,
        public ?string $guestPhone = null,
        public ?string $guestEmail = null,
        public ?int $packageId = null,
    ) {}
}
