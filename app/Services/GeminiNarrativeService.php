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
        $this->model = (string) config('services.gemini.model', 'gemini-2.5-flash');
        $this->timeout = (int) config('services.gemini.timeout', 12);
    }

    public function hasApiKey(): bool
    {
        return trim($this->apiKey) !== '';
    }

    public function generateItineraryBrief(ItineraryVisit $visit): string
    {
        $fallback = $this->fallbackItineraryBrief($visit);

        if (! $this->hasApiKey()) {
            return $fallback;
        }

        $venue = $visit->venue;
        $sampleDetails = json_encode($venue?->sample_details ?? [], JSON_PRETTY_PRINT);

        $systemPrompt = <<<'PROMPT'
You write short, grounded pre-trip notes for travellers saving De Klinic Restaurant & Pub in Accra, Ghana to their trip itinerary.
Output rules:
- Write 2 to 3 clear sentences in plain text only.
- Do not use bullet points, headings, emojis, or markdown asterisks/quotes.
- Connect the traveller's origin/trip window/note to De Klinic's local food and pub experience and its community warmth (including meals bought by a supporter for five followers).
- Never invent menu items, prices, delivery areas, or group/event policies. Treat those as unconfirmed/sample details only.
PROMPT;

        $userPrompt = <<<PROMPT
Venue: {$venue?->name} (Host/Owner: {$venue?->owner_name}, {$venue?->city}, {$venue?->country})
Summary: {$venue?->summary}
Community Story: {$venue?->community_story}
Sample / Unconfirmed Notes: {$sampleDetails}

Traveller:
- Name: {$visit->guest_name}
- From: {$visit->origin_region}
- Party Size: {$visit->party_size}
- Discovery Source: {$visit->discovery_label} ({$visit->discovery_source})
- Target Window: {$visit->target_window}
- Trip Note: {$visit->trip_note}
PROMPT;

        return $this->callGemini($systemPrompt, $userPrompt, $fallback);
    }

    public function generateReferralDraft(ItineraryVisit $visit): string
    {
        $fallback = $this->fallbackReferralDraft($visit);

        if (! $this->hasApiKey()) {
            return $fallback;
        }

        $venue = $visit->venue;

        $systemPrompt = <<<'PROMPT'
You write a natural, concise referral note that a guest who visited De Klinic Restaurant & Pub in Accra can share with a friend, diaspora group, host, or travel curator.
Output rules:
- Write 2 short sentences in plain text only.
- Do not use hashtags, emojis, bullet points, or markdown formatting.
- Reference the Accra visit and include the referral code naturally so other travellers can look it up.
- Do not invent prices, menu items, or unconfirmed booking/delivery claims.
PROMPT;

        $userPrompt = <<<PROMPT
Venue: {$venue?->name} ({$venue?->city}, {$venue?->country})
Guest: {$visit->guest_name} from {$visit->origin_region}
Target Window: {$visit->target_window}
Referral Code: {$visit->referral_code}
Confirmed By: {$visit->confirmed_by}
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
                        'temperature' => 0.35,
                        'maxOutputTokens' => 200,
                    ],
                ]);

            if (! $response->successful()) {
                Log::warning('Gemini API returned non-200 response', [
                    'model' => $this->model,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return $fallback;
            }

            $rawText = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (! is_string($rawText) || trim($rawText) === '') {
                return $fallback;
            }

            return $this->cleanPlainText($rawText) ?: $fallback;
        } catch (Throwable $e) {
            Log::warning('Gemini API call failed; falling back to deterministic text', [
                'model' => $this->model,
                'error' => $e->getMessage(),
            ]);

            return $fallback;
        }
    }

    private function cleanPlainText(string $text): string
    {
        $cleaned = preg_replace('/[*#`_]+/', '', $text) ?? $text;
        $cleaned = trim($cleaned, " \t\n\r\0\x0B\"'");
        $cleaned = preg_replace('/\s+/', ' ', $cleaned) ?? $cleaned;

        return trim($cleaned);
    }

    private function fallbackItineraryBrief(ItineraryVisit $visit): string
    {
        $venueName = $visit->venue?->name ?? 'De Klinic Restaurant & Pub';
        $window = $visit->target_window ?: 'your upcoming trip';

        return "{$visit->guest_name} travelling from {$visit->origin_region} saved {$venueName} in Accra via {$visit->discovery_label} for {$window} with a party of {$visit->party_size}. "
            . "It brings together local food, pub energy, and community spirit—including Portia Boateng's story of meals bought by a supporter for five followers. "
            . "Specific menu items, prices, delivery coverage, and group or event details remain unconfirmed sample items in this prototype.";
    }

    private function fallbackReferralDraft(ItineraryVisit $visit): string
    {
        $venueName = $visit->venue?->name ?? 'De Klinic Restaurant & Pub';

        return "We confirmed our visit to {$venueName} in Accra during {$visit->target_window} and loved the local food, pub atmosphere, and community feel. "
            . "If you are heading to Accra from {$visit->origin_region}, check it out and use referral code {$visit->referral_code}.";
    }
}