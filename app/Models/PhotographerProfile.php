<?php

namespace App\Models;

use App\Enums\ProfileTier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotographerProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug',
        'business_name',
        'bio',
        'location_area',
        'coverage_areas',
        'instagram_handle',
        'whatsapp_number',
        'tier',
        'verified_at',
        'featured_until',
    ];

    protected function casts(): array
    {
        return [
            'coverage_areas' => 'array',
            'tier' => ProfileTier::class,
            'verified_at' => 'datetime',
            'featured_until' => 'datetime',
        ];
    }

    public function scopeVisible(Builder $query): void
    {
        $query->whereNotNull('verified_at');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'profile_id');
    }

    public function portfolioItems()
    {
        return $this->hasMany(PortfolioItem::class, 'profile_id');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'profile_id');
    }

    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class, 'profile_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'profile_id');
    }

    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, BookingRequest::class, 'profile_id', 'booking_request_id');
    }

    /**
     * Get the formatted WhatsApp URL for the photographer.
     */
    public function getWhatsappUrlAttribute(): string
    {
        if (! $this->whatsapp_number) {
            return '#';
        }

        // Remove all non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $this->whatsapp_number);

        // If it starts with 0, replace with 60 (Malaysia country code)
        if (str_starts_with($number, '0')) {
            $number = '6'.$number;
        }

        return "https://wa.me/{$number}";
    }

    public function lowestActivePackage()
    {
        return $this->hasOne(Package::class, 'profile_id')
            ->where('is_active', true)
            ->orderBy('price_from');
    }

    public function coverPortfolioItem()
    {
        return $this->hasOne(PortfolioItem::class, 'profile_id')
            ->orderBy('sort_order');
    }
}
