<?php

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
class MyBookingRequests extends Component
{
    use WithPagination;

    #[Url(as: 'status')]
    public string $status = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BookingRequest::query()
            ->where('client_id', auth()->id())
            ->with(['profile', 'latestQuote'])
            ->latest();

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        return view('livewire.my-booking-requests', [
            'bookings' => $query->paginate(10),
            'statuses' => BookingStatus::cases(),
        ])
            ->title('Tempahan Saya - CariSnap')
            ->layoutData([
                'metaDescription' => 'Lihat status permintaan tempahan jurugambar anda di CariSnap.',
            ]);
    }
}
