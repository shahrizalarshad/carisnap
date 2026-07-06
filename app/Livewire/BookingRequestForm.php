<?php

namespace App\Livewire;

use App\Actions\CreateBookingRequest as CreateBookingRequestAction;
use App\Actions\CreateBookingRequestData;
use App\Enums\AvailabilityStatus;
use App\Enums\EventType;
use App\Models\Package;
use App\Models\PhotographerProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class BookingRequestForm extends Component
{
    public PhotographerProfile $profile;

    public ?Package $selectedPackage = null;

    // Form fields
    public $event_type = 'wedding';

    public $event_date = '';

    public $location = '';

    public $budget_from = '';

    public $budget_to = '';

    public $message = '';

    // Guest fields
    public $guest_name = '';

    public $guest_phone = '';

    public $guest_email = '';

    public bool $isSubmitted = false;

    #[On('open-booking-modal')]
    public function openModal(?int $packageId = null)
    {
        if ($packageId) {
            $this->selectedPackage = Package::find($packageId);
            if ($this->selectedPackage) {
                $this->budget_from = $this->selectedPackage->price_from;
                $this->budget_to = $this->selectedPackage->price_from + 1000;
            }
        }

        $this->dispatch('open-sheet-booking-modal');
    }

    public function rules()
    {
        $rules = [
            'event_date' => ['required', 'date', 'after:today'],
            'location' => ['required', 'string', 'max:255'],
            'budget_from' => ['required', 'numeric', 'min:0'],
            'budget_to' => ['required', 'numeric', 'gte:budget_from'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];

        if (! Auth::check()) {
            $rules['guest_name'] = ['required', 'string', 'max:255'];
            // Basic Malaysian phone validation (starts with 01, followed by 8 or 9 digits)
            $rules['guest_phone'] = ['required', 'string', 'regex:/^01[0-9]{8,9}$/'];
            $rules['guest_email'] = ['nullable', 'email', 'max:255'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'guest_phone.regex' => 'Sila masukkan nombor telefon yang sah (cth: 0123456789).',
            'budget_to.gte' => 'Bajet maksimum mestilah lebih besar atau sama dengan bajet minimum.',
        ];
    }

    public function mount(PhotographerProfile $profile, ?Package $package = null)
    {
        $this->profile = $profile;
        $this->selectedPackage = $package;

        if ($package) {
            $this->budget_from = $package->price_from;
            $this->budget_to = $package->price_from + 1000;
        }

        if (Auth::check()) {
            $this->guest_name = Auth::user()->name;
            $this->guest_phone = Auth::user()->phone ?? '';
            $this->guest_email = Auth::user()->email;
        }
    }

    public function getIsDateUnavailableProperty()
    {
        if (! $this->event_date) {
            return false;
        }

        return $this->profile->availabilities()
            ->where('date', $this->event_date)
            ->where('status', '!=', AvailabilityStatus::Available)
            ->exists();
    }

    public function submit(CreateBookingRequestAction $createBookingRequest)
    {
        $this->validate();

        $createBookingRequest->execute(new CreateBookingRequestData(
            profile: $this->profile,
            eventDate: $this->event_date,
            location: $this->location,
            budgetFrom: (int) $this->budget_from,
            budgetTo: (int) $this->budget_to,
            eventType: EventType::from($this->event_type),
            message: $this->message,
            clientId: Auth::id(),
            guestName: Auth::check() ? null : $this->guest_name,
            guestPhone: Auth::check() ? null : $this->guest_phone,
            guestEmail: Auth::check() ? null : $this->guest_email,
            packageId: $this->selectedPackage?->id,
        ));

        $this->isSubmitted = true;
    }

    public function render()
    {
        return view('livewire.booking-request-form');
    }
}
