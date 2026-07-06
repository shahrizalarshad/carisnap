<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use App\Notifications\RequestReviewNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

#[Signature('reviews:request')]
#[Description('Send review requests to clients one day after their event.')]
class RequestReviewsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $requests = BookingRequest::where('status', BookingStatus::Accepted)
            ->whereDate('event_date', '=', now()->subDay()->toDateString())
            ->doesntHave('review')
            ->get();

        $count = 0;
        foreach ($requests as $request) {
            $email = $request->client_id ? $request->client->email : $request->guest_email;
            if ($email) {
                // Since this might not be tied to an Eloquent User model if guest, we use Notification facade
                Notification::route('mail', $email)
                    ->notify(new RequestReviewNotification($request));
                $count++;
            }
        }

        $this->info("Sent {$count} review requests.");
    }
}
