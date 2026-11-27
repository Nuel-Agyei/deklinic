<?php

namespace App\Http\Controllers;

use App\Models\ItineraryVisit;
use App\Services\GeminiNarrativeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffVisitController extends Controller
{
    public function index(Request $request): View
    {
        $query = ItineraryVisit::with('venue')->latest();

        if ($request->filled('search')) {
            $term = $request->string('search')->trim();
            $query->where(function ($q) use ($term) {
                $q->where('guest_name', 'like', "%{$term}%")
                    ->orWhere('referral_code', 'like', "%{$term}%")
                    ->orWhere('token', 'like', "%{$term}%")
                    ->orWhere('origin_region', 'like', "%{$term}%");
            });
        }

        if (in_array($request->query('status'), ['saved', 'confirmed'], true)) {
            $query->where('status', $request->query('status'));
        }

        return view('staff.visits', [
            'visits' => $query->get(),
            'search' => $request->query('search', ''),
            'statusFilter' => $request->query('status', ''),
        ]);
    }

    public function confirm(Request $request, ItineraryVisit $visit): RedirectResponse
    {
        $validated = $request->validate([
            'confirmed_by' => ['nullable', 'string', 'max:100'],
        ]);

        $visit->update([
            'status' => 'confirmed',
            'confirmed_at' => $visit->confirmed_at ?? now(),
            'confirmed_by' => filled($validated['confirmed_by'] ?? null)
                ? $validated['confirmed_by']
                : 'Accra Floor Staff',
        ]);
        $visit->load('venue');
        $referralDraft = $visit->ai_referral_draft
            ?? app(GeminiNarrativeService::class)->generateReferralDraft($visit);

        $visit->update([
            'status' => 'confirmed',
            'confirmed_at' => $visit->confirmed_at ?? now(),
            'confirmed_by' => filled($validated['confirmed_by'] ?? null)
                ? $validated['confirmed_by']
                : 'Accra Floor Staff',
            'ai_referral_draft' => $referralDraft,
            'referral_note' => $visit->referral_note ?: $referralDraft,
        ]);

        return redirect()
            ->route('staff.visits')
            ->with('status', "Confirmed visit for {$visit->guest_name} ({$visit->referral_code}).");
    }
}
