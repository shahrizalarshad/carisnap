<?php

namespace App\Livewire;

use App\Enums\AvailabilityStatus;
use App\Enums\BookingStatus;
use App\Enums\EventType;
use App\Mail\BookingRequestConfirmation;
use App\Mail\BookingRequestReceived;
use App\Models\BookingRequest;
use App\Models\PhotographerProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CreateBookingRequest extends Component
{
    public PhotographerProfile $profile;

    public $event_date = '';

    public $location = '';

    public $budget_range = ''; // Format: "1000-2000"

    public $message = '';

    public $guest_name = '';

    public $guest_phone = '';

    public $guest_email = '';

    public $showAvailabilityWarning = false;

    public $success = false;

    public function updatedEventDate($value)
    {
        $this->showAvailabilityWarning = false;

        if (empty($value)) {
            return;
        }

        $isAvailable = $this->profile->availabilities()
            ->where('date', $value)
            ->where('status', AvailabilityStatus::Available)
            ->exists();

        if (! $isAvailable) {
            $this->showAvailabilityWarning = true;
        }
    }

    public function rules()
    {
        $rules = [
            'event_date' => 'required|date|after_or_equal:today',
            'location' => 'required|string|max:255',
            'budget_range' => 'required|string',
            'message' => 'nullable|string|max:1000',
        ];

        if (! Auth::check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_phone'] = ['required', 'string', 'regex:/^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/'];
            $rules['guest_email'] = 'nullable|email|max:255';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'guest_phone.regex' => 'Sila masukkan nombor telefon Malaysia yang sah (contoh: 0123456789).',
        ];
    }

    public function submit()
    {
        $this->validate();

        $budgetParts = explode('-', $this->budget_range);
        $budgetFrom = isset($budgetParts[0]) ? (int) $budgetParts[0] : 0;
        $budgetTo = isset($budgetParts[1]) ? (int) $budgetParts[1] : 0;

        if ($budgetTo === 0 && count($budgetParts) === 1 && str_contains($this->budget_range, '+')) {
            $budgetTo = 99999; // For "5000+"
        }

        $booking = BookingRequest::create([
            'profile_id' => $this->profile->id,
            'client_id' => Auth::id(),
            'event_type' => EventType::Wedding,
            'event_date' => $this->event_date,
            'location' => $this->location,
            'budget_from' => $budgetFrom,
            'budget_to' => $budgetTo,
            'message' => $this->message,
            'status' => BookingStatus::Pending,
            'guest_name' => Auth::check() ? null : $this->guest_name,
            'guest_phone' => Auth::check() ? null : $this->guest_phone,
            'guest_email' => Auth::check() ? null : $this->guest_email,
        ]);

        // Queue Emails
        Mail::to($this->profile->user->email)->send(new BookingRequestReceived($booking));

        $clientEmail = Auth::check() ? Auth::user()->email : $this->guest_email;
        if ($clientEmail) {
            Mail::to($clientEmail)->send(new BookingRequestConfirmation($booking));
        }

        $this->success = true;
    }

    public function render()
    {
        return view('livewire.create-booking-request');
    }
}
