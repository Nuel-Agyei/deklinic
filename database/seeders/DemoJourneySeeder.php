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
                    'data_note' => 'Sample prototype data — confirm live menu, prices, delivery, live band nights, and group/event bookings directly.',
                    'menu_highlights' => 'Sample local plates, grilled sides & pub drinks (exact menu to be confirmed)',
                    'pricing_note' => 'Current prices to be confirmed',
                    'delivery_coverage' => 'Delivery coverage to be confirmed',
                    'group_bookings_events' => 'Group bookings & event availability to be confirmed',
                    'pub_features' => [
                        'Live band / music nights (sample feature — schedule to be confirmed)',
                        'Casual pub & evening hangout atmosphere in Accra',
                        'Welcoming group tables for diaspora friends & visitors',
                    ],
                    'sample_menu' => [
                        ['name' => 'Sample Local Main & Sides', 'category' => 'Local Food', 'status' => 'Sample · Price TBC'],
                        ['name' => 'Pub Drinks & Evening Refreshments', 'category' => 'Pub Bar', 'status' => 'Sample · Price TBC'],
                        ['name' => 'Shared Plates for Groups', 'category' => 'Table / Group', 'status' => 'Sample · Availability TBC'],
                    ],
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
