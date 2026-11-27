<?php

namespace Database\Seeders;

use App\Models\ItineraryVisit;
use App\Models\VenueProfile;
use Illuminate\Database\Seeder;

class DemoJourneySeeder extends Seeder
{
    public function run(): void
    {
        $venue = VenueProfile::updateOrCreate(
            ['name' => 'De Klinic Restaurant & Pub'],
            [
                'owner_name' => 'Portia Boateng',
                'city' => 'Accra',
                'country' => 'Ghana',
                'summary' => 'Local Ghanaian dishes and an inviting pub atmosphere in Accra for travellers, groups, and locals.',
                'community_story' => 'Known for genuine community warmth—including serving meals bought by a supporter for five followers.',
                'sample_details' => [
                    'data_note' => 'Sample hackathon placeholder data — confirm live details before production.',
                    'menu_highlights' => 'Sample local plates & pub drinks (to be confirmed)',
                    'pricing_note' => 'Current prices to be confirmed',
                    'delivery_coverage' => 'Delivery coverage to be confirmed',
                    'group_bookings_events' => 'Group bookings & event availability to be confirmed',
                ],
            ]
        );

        ItineraryVisit::updateOrCreate(
            ['token' => 'accra-dec-demo-01'],
            [
                'venue_profile_id' => $venue->id,
                'referral_code' => 'KLINIC-UK-DEC01',
                'discovery_source' => 'diaspora_referral',
                'discovery_label' => 'Diaspora Group Recommendation (London -> Accra)',
                'origin_region' => 'UK',
                'guest_name' => 'Ama Mensah',
                'guest_contact' => 'ama.sample@example.com',
                'target_window' => 'December 2026',
                'party_size' => 4,
                'trip_note' => 'Landing in Accra mid-December with three friends; looking for local food, pub energy, and a welcoming spot.',
                'status' => 'saved',
            ]
        );
    }
}