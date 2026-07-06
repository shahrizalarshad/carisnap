<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class QuoteReceivedNotification extends Notification implements ShouldQueue
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
            ->line($this->quote->bookingRequest->profile->business_name.' telah hantar sebut harga untuk permintaan tempahan anda.')
            ->line('Jumlah: **RM'.number_format($this->quote->amount).'**')
            ->line('Sah sehingga: '.$this->quote->valid_until->format('d/m/Y'))
            ->action('Semak & Balas Sebut Harga', URL::signedRoute('quotes.show', ['quote' => $this->quote->id]))
            ->line('Sila balas sebelum tarikh tamat tempoh.');
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
