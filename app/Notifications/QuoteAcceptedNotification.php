<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteAcceptedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Quote $quote)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Sebut Harga Anda Telah Diterima! 🥳')
            ->greeting('Tahniah '.$notifiable->name.'!')
            ->line('Klien ('.($this->quote->bookingRequest->guest_name ?? $this->quote->bookingRequest->client->name).') telah menerima sebut harga anda untuk tempahan '.$this->quote->bookingRequest->event_type->value.'.')
            ->line('Sila hubungi klien segera melalui WhatsApp atau E-mel untuk mengesahkan perincian akhir.')
            ->action('Lihat Tempahan', url('/photographer/booking-requests'))
            ->line('Semoga berjaya!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
