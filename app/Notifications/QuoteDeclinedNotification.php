<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteDeclinedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Quote $quote) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $clientName = $this->quote->bookingRequest->guest_name
            ?? $this->quote->bookingRequest->client?->name
            ?? 'Pelanggan';

        return (new MailMessage)
            ->subject('Kemaskini sebut harga anda')
            ->greeting('Hai '.$notifiable->name.',')
            ->line($clientName.' telah menolak sebut harga anda untuk majlis perkahwinan ini.')
            ->line('Jangan putus asa — masih ada permintaan tempahan lain yang menunggu respons anda.')
            ->action('Lihat Permintaan', url('/photographer/booking-requests'));
    }
}
