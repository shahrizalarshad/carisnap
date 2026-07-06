<?php

namespace App\Livewire;

use App\Enums\AvailabilityStatus;
use App\Enums\BookingStatus;
use App\Enums\EventType;
use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\PhotographerProfile;
use App\Notifications\BookingRequestReceivedNotification;
use App\Notifications\NewBookingRequestNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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

    public function submit()
    {
        $this->validate();

        $bookingRequest = BookingRequest::create([
            'profile_id' => $this->profile->id,
            'client_id' => Auth::id(),
            'package_id' => $this->selectedPackage?->id,
            'guest_name' => Auth::check() ? null : $this->guest_name,
            'guest_phone' => Auth::check() ? null : $this->guest_phone,
            'guest_email' => Auth::check() ? null : $this->guest_email,
            'event_type' => EventType::from($this->event_type),
            'event_date' => $this->event_date,
            'location' => $this->location,
            'budget_from' => (int) $this->budget_from,
            'budget_to' => (int) $this->budget_to,
            'message' => $this->message,
            'status' => BookingStatus::Pending,
        ]);

        // Notify photographer
        $this->profile->user->notify(new NewBookingRequestNotification($bookingRequest));

        // Notify client/guest
        $email = Auth::check() ? Auth::user()->email : $this->guest_email;
        if ($email) {
            Notification::route('mail', $email)
                ->notify(new BookingRequestReceivedNotification($bookingRequest));
        }

        $this->isSubmitted = true;
    }

    public function render()
    {
        return view('livewire.booking-request-form');
    }
}
