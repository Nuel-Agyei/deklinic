@extends('layouts.app')

@section('title', 'Accra Itinerary — ' . $visit->guest_name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-sand border border-parchment rounded p-6 space-y-5">
        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-parchment pb-4">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ochreDark">
                    Stages 02–03 · Saved Itinerary &amp; Accra Arrival
                </div>
                <h1 class="text-2xl font-bold text-bark mt-1">
                    {{ $visit->venue?->name ?? 'De Klinic Restaurant & Pub' }}
                </h1>
                <p class="text-sm text-umber mt-0.5">
                    Guest: <span class="font-semibold text-bark">{{ $visit->guest_name }}</span> · Party of {{ $visit->party_size }} · From {{ $visit->origin_region }}
                </p>
            </div>

            @if ($visit->isConfirmed())
                <span class="px-3 py-1 rounded text-xs font-bold uppercase bg-sageLight text-sage border border-sage">
                    Visit Confirmed
                </span>
            @else
                <span class="px-3 py-1 rounded text-xs font-bold uppercase bg-parchment text-bark border border-umber">
                    Saved for Trip
                </span>
            @endif
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
            <div class="bg-cream border border-parchment rounded p-3">
                <dt class="text-xs text-umber">Discovery Source</dt>
                <dd class="font-semibold text-bark mt-0.5">{{ $visit->discovery_label }}</dd>
            </div>
            <div class="bg-cream border border-parchment rounded p-3">
                <dt class="text-xs text-umber">Target Window</dt>
                <dd class="font-semibold text-bark mt-0.5">{{ $visit->target_window ?: 'Upcoming Trip' }}</dd>
            </div>
            <div class="bg-cream border border-parchment rounded p-3">
                <dt class="text-xs text-umber">Referral / Check-in Code</dt>
                <dd class="font-mono font-semibold text-bark mt-0.5">{{ $visit->referral_code }}</dd>
            </div>
        </dl>

        @if ($visit->trip_note)
            <div class="bg-cream border border-parchment rounded p-4 text-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-umber mb-1">Traveller Note</p>
                <p class="text-bark">{{ $visit->trip_note }}</p>
            </div>
        @endif

        @if ($visit->ai_itinerary_brief)
            <div class="bg-cream border-l-4 border-sage rounded-r p-4 text-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-sage mb-1">Pre-Trip Context Summary</p>
                <p class="text-bark leading-relaxed">{{ $visit->ai_itinerary_brief }}</p>
            </div>
        @endif

        @if ($visit->isConfirmed())
            <div class="bg-sageLight border border-sage rounded p-4 text-sm text-sage">
                <p class="font-semibold">Confirmed in Accra</p>
                <p class="mt-0.5">
                    Confirmed by {{ $visit->confirmed_by ?: 'Accra Floor Staff' }}
                    @if ($visit->confirmed_at)
                        on {{ $visit->confirmed_at->format('M j, Y · H:i') }}
                    @endif
                    .
                </p>
            </div>
        @else
            <div class="bg-cream border border-parchment rounded p-4 text-sm text-umber">
                <p class="font-semibold text-bark">Arrival Step in Accra</p>
                <p class="mt-0.5">
                    Present code <span class="font-mono font-semibold text-bark">{{ $visit->referral_code }}</span> or itinerary token <span class="font-mono font-semibold text-bark">{{ $visit->token }}</span> to staff at De Klinic so they can confirm your visit.
                </p>
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
            <a href="{{ route('staff.visits', ['search' => $visit->referral_code]) }}"
               class="px-4 py-2 rounded bg-bark hover:bg-umber text-cream text-sm font-semibold transition-colors">
                Go to Staff Confirmation View →
            </a>

            <a href="{{ route('referral.show', $visit->token) }}"
               class="px-4 py-2 rounded bg-ochre hover:bg-ochreDark text-cream text-sm font-semibold transition-colors">
                Open Referral &amp; Share View →
            </a>
        </div>
    </div>
</div>
@endsection