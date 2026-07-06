<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingDeclinedNotification extends Notification
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
            ->subject('Kemaskini Permohonan Tempahan CariSnap')
            ->greeting('Hai!')
            ->line('Dukacita dimaklumkan bahawa permohonan tempahan anda dengan '.$this->request->photographerProfile->business_name.' telah ditolak.')
            ->line('Ini mungkin kerana jurugambar tidak tersedia pada tarikh tersebut atau faktor lain.')
            ->line('Jangan risau, masih ada ramai lagi jurugambar hebat di CariSnap!')
            ->action('Cari Jurugambar Lain', url('/photographers'))
            ->line('Terima kasih kerana menggunakan CariSnap.');
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
