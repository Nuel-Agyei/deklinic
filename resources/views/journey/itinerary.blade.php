@extends('layouts.app')

@section('title', 'Accra Itinerary — ' . $visit->guest_name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-parchment pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-ochreDark block">
                Stages 02–03 · Saved Itinerary &amp; Accra Arrival
            </span>
            <h1 class="text-2xl font-bold text-ink mt-0.5">
                {{ $visit->guest_name }} — {{ $visit->venue?->name ?? 'De Klinic Restaurant & Pub' }}
            </h1>
        </div>

        @if ($visit->isConfirmed())
            <span class="self-start sm:self-auto px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-forestTint text-forest border border-forest">
                Confirmed in Accra
            </span>
        @else
            <span class="self-start sm:self-auto px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-sand text-ink border border-ink">
                Saved — Awaiting Arrival
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Left Trip Pass Ledger (7 cols) --}}
        <section class="lg:col-span-7 bg-sand border border-parchment">
            <div class="px-5 py-3.5 border-b border-parchment flex items-center justify-between">
                <h2 class="text-xs font-bold uppercase tracking-widest text-ink">
                    Itinerary &amp; Arrival Pass
                </h2>
                <span class="text-xs font-mono text-umber">Token: {{ $visit->token }}</span>
            </div>

            <dl class="divide-y divide-parchment text-sm">
                <div class="px-5 py-3 grid grid-cols-3 gap-2">
                    <dt class="text-xs font-bold uppercase tracking-wide text-umber">Guest</dt>
                    <dd class="col-span-2 font-semibold text-ink">{{ $visit->guest_name }} ({{ $visit->party_size }} {{ \Illuminate\Support\Str::plural('person', $visit->party_size) }})</dd>
                </div>
                <div class="px-5 py-3 grid grid-cols-3 gap-2">
                    <dt class="text-xs font-bold uppercase tracking-wide text-umber">Travelling From</dt>
                    <dd class="col-span-2 text-ink">{{ $visit->origin_region }}</dd>
                </div>
                <div class="px-5 py-3 grid grid-cols-3 gap-2">
                    <dt class="text-xs font-bold uppercase tracking-wide text-umber">Discovery Source</dt>
                    <dd class="col-span-2 text-ink">{{ $visit->discovery_label }}</dd>
                </div>
                <div class="px-5 py-3 grid grid-cols-3 gap-2">
                    <dt class="text-xs font-bold uppercase tracking-wide text-umber">Trip Window</dt>
                    <dd class="col-span-2 text-ink">{{ $visit->target_window ?: 'Upcoming Trip' }}</dd>
                </div>
                <div class="px-5 py-3 grid grid-cols-3 gap-2">
                    <dt class="text-xs font-bold uppercase tracking-wide text-umber">Check-In Code</dt>
                    <dd class="col-span-2 font-mono font-bold text-ink">{{ $visit->referral_code }}</dd>
                </div>
                @if ($visit->trip_note)
                    <div class="px-5 py-3 grid grid-cols-3 gap-2">
                        <dt class="text-xs font-bold uppercase tracking-wide text-umber">Guest Note</dt>
                        <dd class="col-span-2 text-ink">{{ $visit->trip_note }}</dd>
                    </div>
                @endif
            </dl>
        </section>

        {{-- Right Context & Next Steps (5 cols) --}}
        <div class="lg:col-span-5 space-y-5">
            @if ($visit->ai_itinerary_brief)
                <section class="bg-sand border border-parchment border-l-4 border-l-forest p-5 space-y-2">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-forest">
                        Pre-Trip Context Summary
                    </h2>
                    <p class="text-sm leading-relaxed text-ink">
                        {{ $visit->ai_itinerary_brief }}
                    </p>
                </section>
            @endif

            <section class="bg-sand border border-parchment p-5 space-y-4">
                @if ($visit->isConfirmed())
                    <div class="space-y-1">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-forest">
                            Visit Confirmed in Accra
                        </h2>
                        <p class="text-sm text-ink">
                            Confirmed by <strong>{{ $visit->confirmed_by ?: 'Accra Floor Staff' }}</strong>
                            @if ($visit->confirmed_at)
                                on {{ $visit->confirmed_at->format('M j, Y · H:i') }}
                            @endif.
                        </p>
                    </div>
                @else
                    <div class="space-y-1">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-ochreDark">
                            Next Step: Confirm Arrival
                        </h2>
                        <p class="text-sm text-umber">
                            When the guest arrives at De Klinic in Accra, staff match code <span class="font-mono font-semibold text-ink">{{ $visit->referral_code }}</span> and mark the visit confirmed.
                        </p>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 pt-1">
                    <a href="{{ route('staff.visits', ['search' => $visit->referral_code]) }}"
                       class="flex-1 text-center px-4 py-2.5 bg-ink hover:bg-umber text-cream text-xs font-bold uppercase tracking-wider transition-colors">
                        Staff Confirmation View
                    </a>
                    <a href="{{ route('referral.show', $visit->token) }}"
                       class="flex-1 text-center px-4 py-2.5 bg-ochre hover:bg-ochreDark text-cream text-xs font-bold uppercase tracking-wider transition-colors">
                        Referral &amp; Share View
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection