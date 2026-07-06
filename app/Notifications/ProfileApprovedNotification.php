<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileApprovedNotification extends Notification
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
            ->subject('Profil CariSnap Anda Telah Disahkan!')
            ->greeting('Tahniah '.$notifiable->name.'!')
            ->line('Profil jurugambar anda di CariSnap telah disemak dan disahkan oleh admin.')
            ->line('Klien kini boleh melihat profil anda dan membuat tempahan.')
            ->action('Lihat Profil', url('/'.$notifiable->photographerProfile->slug))
            ->line('Selamat maju jaya!');
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
