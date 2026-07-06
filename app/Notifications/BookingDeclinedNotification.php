<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingDeclinedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $request) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kemaskini permintaan tempahan anda')
            ->greeting('Hai!')
            ->line($this->request->profile->business_name.' tidak dapat menerima permintaan tempahan anda buat masa ini.')
            ->line('Jangan risau — masih banyak jurugambar hebat di CariSnap yang mungkin available untuk tarikh anda.')
            ->action('Cari Jurugambar Lain', route('photographers.index'))
            ->line('Terima kasih kerana menggunakan CariSnap.');
    }
}
