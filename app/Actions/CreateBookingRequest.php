<?php

namespace App\Actions;

use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Models\BookingRequest;
use App\Models\User;
use App\Notifications\BookingRequestReceivedNotification;
use App\Notifications\NewBookingRequestNotification;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Notification;

class CreateBookingRequest
{
    public function execute(CreateBookingRequestData $data): BookingRequest
    {
        $clientId = $data->clientId ?? $this->resolveClientIdFromGuestPhone($data->guestPhone);
        $guestPhone = $clientId ? null : PhoneNumber::normalize($data->guestPhone);
        $guestName = $clientId ? null : $data->guestName;
        $guestEmail = $clientId ? null : $data->guestEmail;

        $bookingRequest = BookingRequest::create([
            'profile_id' => $data->profile->id,
            'client_id' => $clientId,
            'package_id' => $data->packageId,
            'guest_name' => $guestName,
            'guest_phone' => $guestPhone,
            'guest_email' => $guestEmail,
            'event_type' => $data->eventType,
            'event_date' => $data->eventDate,
            'location' => $data->location,
            'budget_from' => $data->budgetFrom,
            'budget_to' => $data->budgetTo,
            'message' => $data->message,
            'status' => BookingStatus::Pending,
        ]);

        $data->profile->user->notify(new NewBookingRequestNotification($bookingRequest));

        $clientEmail = $clientId
            ? $bookingRequest->client?->email
            : $guestEmail;

        if ($clientEmail) {
            Notification::route('mail', $clientEmail)
                ->notify(new BookingRequestReceivedNotification($bookingRequest));
        }

        return $bookingRequest;
    }

    protected function resolveClientIdFromGuestPhone(?string $guestPhone): ?int
    {
        $normalizedGuestPhone = PhoneNumber::normalize($guestPhone);

        if ($normalizedGuestPhone === null) {
            return null;
        }

        return User::query()
            ->where('role', UserRole::Client)
            ->whereNotNull('phone')
            ->get()
            ->first(fn (User $user): bool => PhoneNumber::matches($user->phone, $normalizedGuestPhone))
            ?->id;
    }
}
