<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItineraryVisit extends Model
{
    protected $fillable = [
        'venue_profile_id',
        'token',
        'referral_code',
        'discovery_source',
        'discovery_label',
        'origin_region',
        'guest_name',
        'guest_contact',
        'target_window',
        'party_size',
        'trip_note',
        'status',
        'confirmed_at',
        'confirmed_by',
        'referral_note',
        'shared_with_channel',
        'shared_at',
        'ai_itinerary_brief',
        'ai_referral_draft',
    ];

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
            'shared_at' => 'datetime',
        ];
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(VenueProfile::class, 'venue_profile_id');
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }
}