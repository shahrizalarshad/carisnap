<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileRejectedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
            ->subject('Status Profil CariSnap Anda')
            ->greeting('Hai '.$notifiable->name.',')
            ->line('Dukacita dimaklumkan bahawa profil jurugambar anda di CariSnap tidak memenuhi kriteria kami buat masa ini, atau telah ditarik balik kelulusannya.')
            ->line('Sila semak semula butiran profil, portfolio, dan maklumat perniagaan anda.')
            ->action('Log Masuk & Kemaskini', url('/photographer'))
            ->line('Jika anda mempunyai sebarang soalan, sila balas e-mel ini.');
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
