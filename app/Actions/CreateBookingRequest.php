<?php

namespace App\Actions;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use App\Notifications\BookingRequestReceivedNotification;
use App\Notifications\NewBookingRequestNotification;
use Illuminate\Support\Facades\Notification;

class CreateBookingRequest
{
    public function execute(CreateBookingRequestData $data): BookingRequest
    {
        $bookingRequest = BookingRequest::create([
            'profile_id' => $data->profile->id,
            'client_id' => $data->clientId,
            'package_id' => $data->packageId,
            'guest_name' => $data->guestName,
            'guest_phone' => $data->guestPhone,
            'guest_email' => $data->guestEmail,
            'event_type' => $data->eventType,
            'event_date' => $data->eventDate,
            'location' => $data->location,
            'budget_from' => $data->budgetFrom,
            'budget_to' => $data->budgetTo,
            'message' => $data->message,
            'status' => BookingStatus::Pending,
        ]);

        $data->profile->user->notify(new NewBookingRequestNotification($bookingRequest));

        $clientEmail = $data->clientId
            ? $bookingRequest->client?->email
            : $data->guestEmail;

        if ($clientEmail) {
            Notification::route('mail', $clientEmail)
                ->notify(new BookingRequestReceivedNotification($bookingRequest));
        }

        return $bookingRequest;
    }
}
