<?php

namespace App\Services;

use App\Models\ItineraryVisit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GeminiNarrativeService
{
    private string $apiKey;
    private string $model;
    private int $timeout;

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.key', '');
        $this->model = (string) config('services.gemini.model', 'gemini-3.8-flash');
        $this->timeout = (int) config('services.gemini.timeout', 12);
    }

    public function generateItineraryBrief(ItineraryVisit $visit): string
    {
        $fallback = $this->fallbackItineraryBrief($visit);

        if ($this->apiKey === '') {
            return $fallback;
        }

        $venue = $visit->venue;
        $sampleDetails = json_encode($venue?->sample_details ?? [], JSON_PRETTY_PRINT);

        $systemPrompt = <<<'PROMPT'
You are a concise travel-intent assistant for De Klinic Restaurant & Pub in Accra, Ghana.
Write a helpful pre-trip itinerary note (3 to 4 short sentences max, plain text, no markdown bullets or emojis).
Strict rules:
- Ground every claim in the provided venue facts and traveller details.
- Never invent specific menu items, prices, delivery zones, or group/event booking policies; if asked or relevant, note those details are sample/to-be-confirmed.
- Highlight authentic connection, local food/pub atmosphere, and community warmth (such as supporter-funded meals for five followers).
PROMPT;

        $userPrompt = <<<PROMPT
Venue: {$venue?->name} (Owner/Host: {$venue?->owner_name}, {$venue?->city}, {$venue?->country})
Core Summary: {$venue?->summary}
Community Story: {$venue?->community_story}
Sample / Unconfirmed Notes: {$sampleDetails}

Traveller Details:
- Guest: {$visit->guest_name} (Party size: {$visit->party_size})
- Origin Region: {$visit->origin_region}
- Discovery Source: {$visit->discovery_label} ({$visit->discovery_source})
- Target Window: {$visit->target_window}
- Trip Note: {$visit->trip_note}
PROMPT;

        return $this->callGemini($systemPrompt, $userPrompt, $fallback);
    }

    public function generateReferralDraft(ItineraryVisit $visit): string
    {
        $fallback = $this->fallbackReferralDraft($visit);

        if ($this->apiKey === '') {
            return $fallback;
        }

        $venue = $visit->venue;

        $systemPrompt = <<<'PROMPT'
You are drafting a warm, natural post-visit referral note for a traveller who visited De Klinic Restaurant & Pub in Accra, Ghana.
Write 2 to 3 short sentences suitable for sharing with a diaspora group, travel curator, or host.
Strict rules:
- Plain text only, no hashtags, no emojis, no invented prices or menu claims.
- Mention the confirmed Accra visit, referral code, and invite others planning a trip to check it out.
PROMPT;

        $userPrompt = <<<PROMPT
Venue: {$venue?->name} ({$venue?->city}, {$venue?->country})
Guest Name: {$visit->guest_name}
Origin Region: {$visit->origin_region}
Target Window: {$visit->target_window}
Referral Code: {$visit->referral_code}
Confirmed By Staff: {$visit->confirmed_by}
PROMPT;

        return $this->callGemini($systemPrompt, $userPrompt, $fallback);
    }

    private function callGemini(string $systemPrompt, string $userPrompt, string $fallback): string
    {
        try {
            $url = sprintf(
                'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
                $this->model
            );

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'x-goog-api-key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'systemInstruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => $userPrompt]],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'maxOutputTokens' => 220,
                    ],
                ]);

            if (! $response->successful()) {
                Log::warning('Gemini API non-success response', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return $fallback;
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (! is_string($text) || trim($text) === '') {
                return $fallback;
            }

            return trim($text);
        } catch (Throwable $e) {
            Log::warning('Gemini API request failed; using fallback narrative', [
                'message' => $e->getMessage(),
            ]);

            return $fallback;
        }
    }

    private function fallbackItineraryBrief(ItineraryVisit $visit): string
    {
        $venueName = $visit->venue?->name ?? 'De Klinic Restaurant & Pub';
        $window = $visit->target_window ?: 'your upcoming trip';

        return "{$visit->guest_name} ({$visit->origin_region}) saved {$venueName} in Accra via {$visit->discovery_label} for {$window} with a party of {$visit->party_size}. "
            . "It pairs local food and pub energy with genuine community spirit—including Portia Boateng's story of meals bought by a supporter for five followers. "
            . "Specific menu items, prices, delivery coverage, and group/event details remain sample/to-be-confirmed placeholders for this prototype.";
    }

    private function fallbackReferralDraft(ItineraryVisit $visit): string
    {
        $venueName = $visit->venue?->name ?? 'De Klinic Restaurant & Pub';

        return "We confirmed our visit to {$venueName} in Accra during {$visit->target_window}. "
            . "If you are heading to Accra from {$visit->origin_region} and want local food, pub atmosphere, and an authentic community spot, put it on your list and use referral code {$visit->referral_code}.";
    }
}