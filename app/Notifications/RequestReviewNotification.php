<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class RequestReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $request) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->request->guest_name ?? $notifiable->name;

        return (new MailMessage)
            ->subject('Kongsi pengalaman anda dengan jurugambar')
            ->greeting('Hai '.$name.',')
            ->line('Majlis anda dengan **'.$this->request->profile->business_name.'** baru sahaja berlalu.')
            ->line('Bantu pasangan lain cari jurugambar terbaik dengan tinggalkan ulasan ringkas.')
            ->action('Tulis Ulasan', URL::signedRoute('reviews.create', ['bookingRequest' => $this->request->id]))
            ->line('Terima kasih kerana menyokong komuniti CariSnap!');
    }
}
