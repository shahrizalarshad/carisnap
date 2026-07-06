<?php

namespace App\Models;

use App\Enums\QuoteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_request_id',
        'amount',
        'message',
        'valid_until',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'status' => QuoteStatus::class,
        ];
    }

    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class);
    }
}
