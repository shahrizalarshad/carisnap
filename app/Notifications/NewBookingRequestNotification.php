<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->bookingRequest->client_id ? $this->bookingRequest->client->name : $this->bookingRequest->guest_name;

        return (new MailMessage)
            ->subject('Permintaan Tempahan Baru dari '.$name)
            ->greeting('Salam '.$notifiable->name.'!')
            ->line('Anda telah menerima satu permintaan tempahan baru.')
            ->line('Tarikh Majlis: '.Carbon::parse($this->bookingRequest->event_date)->format('d M Y'))
            ->line('Lokasi: '.$this->bookingRequest->location)
            ->line('Bajet Pelanggan: RM'.number_format($this->bookingRequest->budget_from).' - RM'.number_format($this->bookingRequest->budget_to))
            ->action('Lihat Butiran', url('/photographer/booking-requests/'.$this->bookingRequest->id))
            ->line('Sila maklum balas kepada pelanggan dengan memberikan sebut harga atau menolak permintaan ini.');
    }
}
