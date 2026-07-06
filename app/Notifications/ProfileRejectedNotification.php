<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ?string $reason = null) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Kemaskini profil CariSnap anda')
            ->greeting('Hai '.$notifiable->name.',')
            ->line('Profil jurugambar anda belum dapat disahkan buat masa ini.')
            ->line('Sila semak semula bio, portfolio, maklumat hubungan, dan kawasan liputan anda.');

        if ($this->reason) {
            $message->line('**Maklum balas pasukan:** '.$this->reason);
        }

        return $message
            ->action('Kemaskini di Panel Pro', url('/photographer'))
            ->line('Selepas dikemaskini, profil akan disemak semula oleh pasukan kami.');
    }
}
