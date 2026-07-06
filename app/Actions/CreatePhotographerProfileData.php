<?php

namespace App\Actions;

class CreatePhotographerProfileData
{
    public function __construct(
        public string $businessName,
        public string $bio,
        public string $locationArea,
        public array $coverageAreas,
        public string $whatsappNumber,
        public ?string $instagramHandle = null,
        public ?string $phone = null,
    ) {}
}
