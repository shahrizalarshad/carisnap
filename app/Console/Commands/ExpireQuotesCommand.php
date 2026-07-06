<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Models\Quote;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('quotes:expire')]
#[Description('Expire quotes that have passed their validity date.')]
class ExpireQuotesCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredQuotes = Quote::where('status', QuoteStatus::Sent)
            ->whereDate('valid_until', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredQuotes as $quote) {
            $quote->update(['status' => QuoteStatus::Expired]);
            $quote->bookingRequest->update(['status' => BookingStatus::Expired]);
            $count++;
        }

        $this->info("Expired {$count} quotes.");
    }
}
