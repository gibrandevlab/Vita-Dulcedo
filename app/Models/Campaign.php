<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'collected_amount',
        'deadline',
        'status',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'integer',
            'collected_amount' => 'integer',
            'deadline' => 'date',
        ];
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
