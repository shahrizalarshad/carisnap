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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portfolio')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(400)
            ->format('webp')
            ->performOnCollections('portfolio')
            ->queued();

        $this->addMediaConversion('display')
            ->width(1200)
            ->format('webp')
            ->performOnCollections('portfolio')
            ->queued();
    }

    public function profile()
    {
        return $this->belongsTo(PhotographerProfile::class, 'profile_id');
    }

    public static function mediaUrl(?Media $media, string $conversion = 'display'): ?string
    {
        if (! $media) {
            return null;
        }

        if ($media->hasGeneratedConversion($conversion)) {
            return $media->getUrl($conversion);
        }

        return $media->getUrl();
    }

    public static function mediaSrcset(?Media $media): ?string
    {
        if (! $media) {
            return null;
        }

        $parts = [];

        if ($media->hasGeneratedConversion('thumbnail')) {
            $parts[] = $media->getUrl('thumbnail').' 400w';
        }

        if ($media->hasGeneratedConversion('display')) {
            $parts[] = $media->getUrl('display').' 1200w';
        }

        return $parts !== [] ? implode(', ', $parts) : null;
    }
}
