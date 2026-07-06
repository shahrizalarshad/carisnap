<?php

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use App\Models\Review;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class SubmitReview extends Component
{
    public BookingRequest $bookingRequest;

    public $rating = 5;

    public $comment = '';

    public $alreadySubmitted = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ];

    public function mount(BookingRequest $bookingRequest)
    {
        $this->bookingRequest = $bookingRequest;

        if ($this->bookingRequest->status !== BookingStatus::Accepted) {
            abort(403, 'Hanya tempahan yang telah diterima (accepted) boleh diberi ulasan.');
        }

        if (Review::where('booking_request_id', $this->bookingRequest->id)->exists()) {
            $this->alreadySubmitted = true;
        }
    }

    public function submit()
    {
        if ($this->alreadySubmitted) {
            return;
        }

        $this->validate();

        Review::create([
            'booking_request_id' => $this->bookingRequest->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'published_at' => null, // Pending moderation
        ]);

        $this->alreadySubmitted = true;

        session()->flash('message', 'Terima kasih atas ulasan anda! Ia akan disemak sebelum diterbitkan.');
    }

    public function render()
    {
        return view('livewire.submit-review')
            ->title('Tinggalkan Ulasan — CariSnap')
            ->layoutData(['noIndex' => true]);
    }
}
