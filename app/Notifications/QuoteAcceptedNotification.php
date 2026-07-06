<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteAcceptedNotification extends Notification implements ShouldQueue
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
            ->subject('Sebut harga anda diterima!')
            ->greeting('Tahniah, '.$notifiable->name.'!')
            ->line($clientName.' telah **menerima** sebut harga anda untuk majlis perkahwinan.')
            ->line('Jumlah: RM'.number_format($this->quote->amount))
            ->line('Sila hubungi pelanggan melalui WhatsApp atau e-mel untuk bincang lanjut.')
            ->action('Lihat Permintaan', url('/photographer/booking-requests'))
            ->line('Semoga majlis berjalan lancar!');
    }
}
