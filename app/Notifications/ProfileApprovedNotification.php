<?php

namespace App\Notifications;

use App\Models\PhotographerProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public PhotographerProfile $profile) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Profil CariSnap anda telah disahkan!')
            ->greeting('Tahniah, '.$notifiable->name.'!')
            ->line('Profil **'.$this->profile->business_name.'** telah disemak dan disahkan oleh pasukan CariSnap.')
            ->line('Pelanggan kini boleh cari studio anda, lihat portfolio, dan hantar permintaan tempahan.')
            ->action('Lihat Profil Public', route('photographers.show', $this->profile->slug))
            ->line('Tip: pastikan portfolio, pakej, dan tarikh kekosongan sentiasa dikemaskini untuk lebih banyak tempahan.');
    }
}
