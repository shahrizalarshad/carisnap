<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class RequestReviewNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public BookingRequest $request)
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
            ->subject('Sila Tinggalkan Ulasan untuk Jurugambar Anda')
            ->greeting('Hai '.($this->request->guest_name ?? $notifiable->name).',')
            ->line('Acara anda bersama '.$this->request->photographerProfile->business_name.' baru sahaja selesai.')
            ->line('Bantu komuniti CariSnap dengan berkongsi pengalaman anda bersama jurugambar ini!')
            ->action('Tinggalkan Ulasan', URL::signedRoute('reviews.create', ['bookingRequest' => $this->request->id]))
            ->line('Terima kasih kerana menyokong jurugambar tempatan.');
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
