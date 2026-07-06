<?php

namespace App\Mail;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRequestReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookingRequest $bookingRequest
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permintaan Tempahan Baru: '.$this->bookingRequest->event_date->format('d M Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking.received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
