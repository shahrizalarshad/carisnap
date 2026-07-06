<?php

namespace App\Actions;

readonly class SendQuoteData
{
    public function __construct(
        public int $amount,
        public ?string $message = null,
        public int $validForDays = 7,
    ) {}
}
