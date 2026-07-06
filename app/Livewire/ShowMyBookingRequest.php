<?php

namespace App\Livewire;

use App\Models\BookingRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class ShowMyBookingRequest extends Component
{
    public BookingRequest $bookingRequest;

    public function mount(BookingRequest $bookingRequest): void
    {
        $this->authorize('view', $bookingRequest);

        $this->bookingRequest = $bookingRequest->load([
            'profile',
            'package',
            'latestQuote',
            'review',
        ]);
    }

    public function render()
    {
        return view('livewire.show-my-booking-request')
            ->title('Butiran Tempahan - CariSnap')
            ->layoutData([
                'metaDescription' => 'Butiran permintaan tempahan jurugambar anda di CariSnap.',
            ]);
    }
}
