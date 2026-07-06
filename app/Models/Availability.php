<?php

namespace App\Models;

use App\Enums\AvailabilityStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => AvailabilityStatus::class,
        ];
    }

    public function profile()
    {
        return $this->belongsTo(PhotographerProfile::class, 'profile_id');
    }
}
