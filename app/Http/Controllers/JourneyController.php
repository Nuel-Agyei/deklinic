<?php

namespace App\Http\Controllers;

use App\Models\ItineraryVisit;
use App\Models\VenueProfile;
use App\Services\GeminiNarrativeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JourneyController extends Controller
{
    private const DISCOVERY_SOURCES = [
        'diaspora_referral' => 'Diaspora Referral',
        'ai_travel_answer' => 'AI Travel Answer',
        'curated_itinerary' => 'Curated Accra Itinerary',
        'search' => 'Search',
    ];

    private const ORIGIN_REGIONS = [
        'UK',
        'North America',
        'Europe',
        'Africa',
    ];

    public function discover(Request $request): View
    {
        $venue = VenueProfile::firstOrFail();

        $selectedSource = array_key_exists($request->query('source'), self::DISCOVERY_SOURCES)
            ? $request->query('source')
            : 'diaspora_referral';

        $selectedRegion = in_array($request->query('region'), self::ORIGIN_REGIONS, true)
            ? $request->query('region')
            : 'UK';

        $demoVisit = ItineraryVisit::where('token', 'accra-dec-demo-01')->first();

        return view('journey.discover', [
            'venue' => $venue,
            'discoverySources' => self::DISCOVERY_SOURCES,
            'originRegions' => self::ORIGIN_REGIONS,
            'selectedSource' => $selectedSource,
            'selectedRegion' => $selectedRegion,
            'demoVisit' => $demoVisit,
        ]);
    }

    public function storeItinerary(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'discovery_source' => ['required', 'string', 'in:diaspora_referral,ai_travel_answer,curated_itinerary,search'],
            'discovery_label' => ['nullable', 'string', 'max:120'],
            'origin_region' => ['required', 'string', 'in:UK,North America,Europe,Africa'],
            'guest_name' => ['required', 'string', 'max:100'],
            'guest_contact' => ['nullable', 'string', 'max:120'],
            'target_window' => ['nullable', 'string', 'max:80'],
            'party_size' => ['required', 'integer', 'min:1', 'max:30'],
            'trip_note' => ['nullable', 'string', 'max:600'],
        ]);

        $venue = VenueProfile::firstOrFail();

        $sourceName = self::DISCOVERY_SOURCES[$validated['discovery_source']];
        $label = filled($validated['discovery_label'])
            ? $validated['discovery_label']
            : "{$sourceName} ({$validated['origin_region']} -> {$venue->city})";

        $visit = ItineraryVisit::create([
            'venue_profile_id' => $venue->id,
            'token' => Str::lower(Str::random(10)),
            'referral_code' => 'KLINIC-'.Str::upper(Str::random(6)),
            'discovery_source' => $validated['discovery_source'],
            'discovery_label' => $label,
            'origin_region' => $validated['origin_region'],
            'guest_name' => $validated['guest_name'],
            'guest_contact' => $validated['guest_contact'] ?? null,
            'target_window' => $validated['target_window'] ?? 'December 2026',
            'party_size' => $validated['party_size'],
            'trip_note' => $validated['trip_note'] ?? null,
            'status' => 'saved',
        ]);

        $visit->load('venue');
        $visit->update([
            'ai_itinerary_brief' => app(GeminiNarrativeService::class)->generateItineraryBrief($visit),
        ]);

        return redirect()
            ->route('itinerary.show', $visit->token)
            ->with('status', 'Saved to your Accra trip itinerary.');
    }

    public function showItinerary(string $token): View
    {
        $visit = ItineraryVisit::with('venue')
            ->where('token', $token)
            ->firstOrFail();

        return view('journey.itinerary', [
            'visit' => $visit,
        ]);
    }

    public function showReferral(string $token): View
    {
        $visit = ItineraryVisit::with('venue')
            ->where('token', $token)
            ->firstOrFail();

        return view('staff.referral', [
            'visit' => $visit,
        ]);
    }

    public function shareReferral(Request $request, string $token): RedirectResponse
    {
        $visit = ItineraryVisit::where('token', $token)->firstOrFail();

        $validated = $request->validate([
            'shared_with_channel' => ['required', 'string', 'max:100'],
            'referral_note' => ['required', 'string', 'max:500'],
        ]);

        $visit->update([
            'shared_with_channel' => $validated['shared_with_channel'],
            'referral_note' => $validated['referral_note'],
            'shared_at' => now(),
        ]);

        return redirect()
            ->route('referral.show', $visit->token)
            ->with('status', 'Referral note ready to share with your circle.');
    }
}
