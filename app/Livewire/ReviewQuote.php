<?php

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Models\Quote;
use App\Notifications\QuoteAcceptedNotification;
use App\Notifications\QuoteDeclinedNotification;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class ReviewQuote extends Component
{
    public Quote $quote;

    public function mount(Quote $quote)
    {
        $this->quote = $quote;

        // If the quote is not in "Sent" status, they shouldn't be able to interact with it
        // We'll handle this in the view (showing it as expired, accepted, or declined).
    }

    public function accept()
    {
        if (! $this->quoteCanBeRespondedTo()) {
            return;
        }

        $this->quote->update(['status' => QuoteStatus::Accepted]);
        $this->quote->bookingRequest->update(['status' => BookingStatus::Accepted, 'responded_at' => now()]);

        $this->quote->bookingRequest->profile->user->notify(new QuoteAcceptedNotification($this->quote));

        session()->flash('message', 'Sebut harga berjaya diterima! Jurugambar akan menghubungi anda sebentar lagi.');
    }

    public function decline()
    {
        if (! $this->quoteCanBeRespondedTo()) {
            return;
        }

        $this->quote->update(['status' => QuoteStatus::Declined]);
        $this->quote->bookingRequest->update(['status' => BookingStatus::Declined, 'responded_at' => now()]);

        $this->quote->bookingRequest->profile->user->notify(new QuoteDeclinedNotification($this->quote));

        session()->flash('message', 'Sebut harga telah ditolak.');
    }

    public function render()
    {
        return view('livewire.review-quote')
            ->title('Semak Sebut Harga — CariSnap')
            ->layoutData(['noIndex' => true]);
    }

    private function quoteCanBeRespondedTo(): bool
    {
        return $this->quote->status === QuoteStatus::Sent
            && $this->quote->valid_until->toDateString() >= now()->toDateString();
    }
}
