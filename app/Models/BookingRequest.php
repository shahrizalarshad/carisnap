<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'guest_name',
        'guest_phone',
        'guest_email',
        'profile_id',
        'package_id',
        'event_type',
        'event_date',
        'location',
        'budget_from',
        'budget_to',
        'message',
        'status',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'event_type' => EventType::class,
            'event_date' => 'date',
            'status' => BookingStatus::class,
            'responded_at' => 'datetime',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function profile()
    {
        return $this->belongsTo(PhotographerProfile::class, 'profile_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function latestQuote(): HasOne
    {
        return $this->hasOne(Quote::class)->latestOfMany();
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
