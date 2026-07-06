<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class QuoteReceivedNotification extends Notification
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
            ->subject('Sebut Harga Baharu dari CariSnap')
            ->greeting('Hai!')
            ->line('Anda telah menerima sebut harga baharu daripada '.$this->quote->bookingRequest->profile->business_name.'.')
            ->line('Sila klik butang di bawah untuk menyemak dan memberi maklum balas.')
            ->action('Semak Sebut Harga', URL::signedRoute('quotes.show', ['quote' => $this->quote->id]))
            ->line('Sebut harga ini sah sehingga '.$this->quote->valid_until->format('d/m/Y').'.');
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
