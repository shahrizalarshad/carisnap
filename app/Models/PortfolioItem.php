<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PortfolioItem extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'profile_id',
        'event_type',
        'caption',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'event_type' => EventType::class,
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(400)
            ->format('webp')
            ->queued();

        $this->addMediaConversion('display')
            ->width(1200)
            ->format('webp')
            ->queued();
    }

    public function profile()
    {
        return $this->belongsTo(PhotographerProfile::class, 'profile_id');
    }
}
