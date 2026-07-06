<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRequestReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->bookingRequest->client_id ? $this->bookingRequest->client->name : $this->bookingRequest->guest_name;
        $photographer = $this->bookingRequest->profile->business_name;

        return (new MailMessage)
            ->subject('Permintaan Tempahan Anda Diterima')
            ->greeting('Salam '.$name.'!')
            ->line('Kami telah menerima permintaan tempahan anda untuk '.$photographer.'.')
            ->line('Butiran Permintaan:')
            ->line('- Tarikh: '.Carbon::parse($this->bookingRequest->event_date)->format('d M Y'))
            ->line('- Lokasi: '.$this->bookingRequest->location)
            ->line('Pihak jurugambar akan menghubungi anda dalam masa 24 jam untuk pengesahan dan sebut harga.')
            ->line('Terima kasih kerana menggunakan CariSnap!');
    }
}
