@extends('layouts.app')

@section('title', 'Discover De Klinic — Accra Trip Planner')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    {{-- Left Editorial Column (7 cols) --}}
    <div class="lg:col-span-7 space-y-7">
        <section class="border-b border-parchment pb-6 space-y-3">
            <div class="flex items-center justify-between gap-4">
                <span class="text-xs font-bold uppercase tracking-widest text-ochreDark">
                    Stage 01 · Pre-Trip Discovery
                </span>
                <span class="text-xs font-mono text-umber">
                    {{ $venue->city }}, {{ $venue->country }}
                </span>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-ink">
                {{ $venue->name }}
            </h1>

            <p class="text-sm text-umber">
                Led by <strong class="text-ink font-semibold">{{ $venue->owner_name }}</strong> · Built around local food, pub atmosphere, and community connection for travellers heading to Accra.
            </p>

            <p class="text-base leading-relaxed text-ink pt-1">
                {{ $venue->summary }}
            </p>
        </section>

        <section class="bg-sand border border-parchment border-l-4 border-l-ochre p-5 space-y-2">
            <h2 class="text-xs font-bold uppercase tracking-widest text-ochreDark">
                Why Diaspora Travellers Pay Attention
            </h2>
            <p class="text-sm leading-relaxed text-ink">
                {{ $venue->community_story }}
            </p>
        </section>

        @php
            $details = $venue->sample_details ?? [];
        @endphp

        <section class="bg-sand border border-parchment">
            <div class="px-5 py-3.5 border-b border-parchment flex flex-wrap items-center justify-between gap-2">
                <h2 class="text-xs font-bold uppercase tracking-widest text-ink">
                    Operational &amp; Trip Details Ledger
                </h2>
                <span class="text-xs font-mono uppercase bg-cream border border-parchment px-2 py-0.5 text-umber">
                    Sample / Unconfirmed
                </span>
            </div>

            <p class="px-5 pt-3 text-xs text-umber">
                {{ $details['data_note'] ?? 'Prototype placeholder data — verify live menu, prices, delivery, and event details directly.' }}
            </p>

            <dl class="divide-y divide-parchment text-sm mt-2">
                <div class="px-5 py-3 grid grid-cols-1 sm:grid-cols-3 gap-1">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-umber">Menu &amp; Pub</dt>
                    <dd class="sm:col-span-2 text-ink">{{ $details['menu_highlights'] ?? 'Sample local plates & pub drinks' }}</dd>
                </div>
                <div class="px-5 py-3 grid grid-cols-1 sm:grid-cols-3 gap-1">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-umber">Pricing</dt>
                    <dd class="sm:col-span-2 text-ink">{{ $details['pricing_note'] ?? 'Current prices to be confirmed' }}</dd>
                </div>
                <div class="px-5 py-3 grid grid-cols-1 sm:grid-cols-3 gap-1">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-umber">Delivery</dt>
                    <dd class="sm:col-span-2 text-ink">{{ $details['delivery_coverage'] ?? 'Delivery coverage to be confirmed' }}</dd>
                </div>
                <div class="px-5 py-3 grid grid-cols-1 sm:grid-cols-3 gap-1">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-umber">Groups &amp; Events</dt>
                    <dd class="sm:col-span-2 text-ink">{{ $details['group_bookings_events'] ?? 'Group & event availability to be confirmed' }}</dd>
                </div>
            </dl>
        </section>

        @if ($demoVisit)
            <section class="bg-sand border border-parchment px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-xs">
                    <span class="font-bold uppercase tracking-wider text-forest block mb-0.5">
                        Seeded Walkthrough Record
                    </span>
                    <span class="text-ink font-medium">{{ $demoVisit->guest_name }}</span>
                    <span class="text-umber">({{ $demoVisit->origin_region }} · {{ $demoVisit->target_window }} · Code {{ $demoVisit->referral_code }})</span>
                </div>
                <a href="{{ route('itinerary.show', $demoVisit->token) }}"
                   class="shrink-0 px-3.5 py-2 border border-ink bg-cream hover:bg-ink hover:text-cream text-xs font-semibold uppercase tracking-wide text-ink transition-colors">
                    Inspect Record
                </a>
            </section>
        @endif
    </div>

    {{-- Right Planning Rail (5 cols) --}}
    <aside class="lg:col-span-5 lg:sticky lg:top-6 bg-sand border border-parchment">
        <div class="px-6 py-4 border-b border-parchment bg-parchment/40">
            <span class="text-xs font-bold uppercase tracking-widest text-ochreDark block">
                Stage 02 · Decide Before Flying
            </span>
            <h2 class="text-xl font-bold text-ink mt-0.5">
                Save to Accra Itinerary
            </h2>
            <p class="text-xs text-umber mt-1">
                Creates a trackable itinerary pass and check-in code before departure.
            </p>
        </div>

        <form action="{{ route('itinerary.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="origin_region" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                        Origin Region
                    </label>
                    <select id="origin_region" name="origin_region"
                            class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
                        @foreach ($originRegions as $region)
                            <option value="{{ $region }}" @selected(old('origin_region', $selectedRegion) === $region)>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="discovery_source" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                        Discovery Source
                    </label>
                    <select id="discovery_source" name="discovery_source"
                            class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
                        @foreach ($discoverySources as $key => $label)
                            <option value="{{ $key }}" @selected(old('discovery_source', $selectedSource) === $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="discovery_label" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                    Referral / Curator Context (Optional)
                </label>
                <input id="discovery_label" name="discovery_label" type="text"
                       value="{{ old('discovery_label') }}"
                       placeholder="e.g., London Diaspora Group · December List"
                       class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="guest_name" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                        Traveller Name
                    </label>
                    <input id="guest_name" name="guest_name" type="text" required
                           value="{{ old('guest_name') }}"
                           placeholder="Kwame Boateng"
                           class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
                </div>

                <div>
                    <label for="party_size" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                        Party Size
                    </label>
                    <input id="party_size" name="party_size" type="number" min="1" max="30" required
                           value="{{ old('party_size', 2) }}"
                           class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="target_window" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                        Trip Window
                    </label>
                    <input id="target_window" name="target_window" type="text"
                           value="{{ old('target_window', 'December 2026') }}"
                           placeholder="December 2026"
                           class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
                </div>

                <div>
                    <label for="guest_contact" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                        Contact (Optional)
                    </label>
                    <input id="guest_contact" name="guest_contact" type="text"
                           value="{{ old('guest_contact') }}"
                           placeholder="kwame@example.com"
                           class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
                </div>
            </div>

            <div>
                <label for="trip_note" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                    Trip &amp; Arrival Note
                </label>
                <textarea id="trip_note" name="trip_note" rows="3"
                          placeholder="Arriving in Accra mid-December; planning an evening meal and pub stop with friends."
                          class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">{{ old('trip_note') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full bg-ochre hover:bg-ochreDark text-cream text-xs font-bold uppercase tracking-wider py-3 px-4 border border-ochreDark transition-colors">
                Save to Accra Itinerary
            </button>
        </form>
    </aside>
</div>
@endsection