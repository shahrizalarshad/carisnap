<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'name',
        'event_type',
        'price_from',
        'deliverables',
        'duration_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'event_type' => EventType::class,
            'is_active' => 'boolean',
        ];
    }

    public function profile()
    {
        return $this->belongsTo(PhotographerProfile::class, 'profile_id');
    }
}
