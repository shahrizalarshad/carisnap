<?php

namespace App\Livewire;

use App\Actions\CreateBookingRequest as CreateBookingRequestAction;
use App\Actions\CreateBookingRequestData;
use App\Enums\AvailabilityStatus;
use App\Enums\EventType;
use App\Models\PhotographerProfile;
use Illuminate\Support\Facades\Auth;
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

    public string $client_phone = '';

    public bool $requiresPhone = false;

    public $showAvailabilityWarning = false;

    public $success = false;

    public function mount(): void
    {
        if (Auth::check() && blank(Auth::user()->phone)) {
            $this->requiresPhone = true;
        }
    }

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
        } elseif ($this->requiresPhone) {
            $rules['client_phone'] = ['required', 'string', 'regex:/^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'guest_phone.regex' => 'Sila masukkan nombor telefon Malaysia yang sah (contoh: 0123456789).',
            'client_phone.regex' => 'Sila masukkan nombor telefon Malaysia yang sah (contoh: 0123456789).',
        ];
    }

    public function submit(CreateBookingRequestAction $createBookingRequest)
    {
        $this->validate();

        if ($this->requiresPhone) {
            Auth::user()->update(['phone' => $this->client_phone]);
        }

        [$budgetFrom, $budgetTo] = $this->parseBudgetRange();

        $createBookingRequest->execute(new CreateBookingRequestData(
            profile: $this->profile,
            eventDate: $this->event_date,
            location: $this->location,
            budgetFrom: $budgetFrom,
            budgetTo: $budgetTo,
            eventType: EventType::Wedding,
            message: $this->message,
            clientId: Auth::id(),
            guestName: Auth::check() ? null : $this->guest_name,
            guestPhone: Auth::check() ? null : $this->guest_phone,
            guestEmail: Auth::check() ? null : $this->guest_email,
        ));

        $this->success = true;
    }

    /**
     * @return array{0: int, 1: int}
     */
    protected function parseBudgetRange(): array
    {
        $budgetParts = explode('-', $this->budget_range);
        $budgetFrom = isset($budgetParts[0]) ? (int) $budgetParts[0] : 0;
        $budgetTo = isset($budgetParts[1]) ? (int) $budgetParts[1] : 0;

        if ($budgetTo === 0 && count($budgetParts) === 1 && str_contains($this->budget_range, '+')) {
            $budgetTo = 99999;
        }

        return [$budgetFrom, $budgetTo];
    }

    public function render()
    {
        return view('livewire.create-booking-request');
    }
}
