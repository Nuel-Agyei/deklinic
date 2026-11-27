<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VenueProfile extends Model
{
    protected $fillable = [
        'name',
        'owner_name',
        'city',
        'country',
        'summary',
        'community_story',
        'sample_details',
    ];

    protected function casts(): array
    {
        return [
            'sample_details' => 'array',
        ];
    }

    public function visits(): HasMany
    {
        return $table = $this->hasMany(ItineraryVisit::class);
    }
}