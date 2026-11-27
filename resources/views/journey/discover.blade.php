@extends('layouts.app')

@section('title', 'Discover De Klinic — Accra Trip Planner')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    {{-- Left Column: Venue Proof & Sample Details --}}
    <div class="lg:col-span-7 space-y-6">
        <section class="bg-sand border border-parchment rounded p-6 space-y-4">
            <div class="text-xs font-semibold uppercase tracking-wider text-ochreDark">
                Stages 01–02 · Discover Abroad &amp; Decide Before Flying
            </div>
            <h1 class="text-2xl font-bold text-bark">
                {{ $venue->name }}
            </h1>
            <p class="text-sm text-umber">
                Hosted by <span class="font-semibold text-bark">{{ $venue->owner_name }}</span> · {{ $venue->city }}, {{ $venue->country }}
            </p>
            <p class="text-base leading-relaxed text-bark">
                {{ $venue->summary }}
            </p>

            <blockquote class="bg-cream border-l-4 border-ochre p-4 rounded-r text-sm text-bark">
                <p class="font-semibold text-ochreDark mb-1">Community &amp; Follower Proof</p>
                <p>{{ $venue->community_story }}</p>
            </blockquote>
        </section>

        @php
            $details = $venue->sample_details ?? [];
        @endphp

        <section class="bg-sand border border-parchment rounded p-6 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <h2 class="text-base font-bold text-bark">Venue &amp; Trip Details (Prototype Sample)</h2>
                <span class="text-xs font-medium px-2 py-0.5 rounded bg-parchment text-bark">
                    To Be Confirmed
                </span>
            </div>
            <p class="text-xs text-umber">
                {{ $details['data_note'] ?? 'Sample hackathon placeholder data — confirm live operational details directly.' }}
            </p>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm pt-2">
                <div class="bg-cream border border-parchment rounded p-3">
                    <dt class="text-xs text-umber font-medium">Menu &amp; Pub Offerings</dt>
                    <dd class="text-bark mt-0.5">{{ $details['menu_highlights'] ?? 'Sample local plates & pub drinks' }}</dd>
                </div>
                <div class="bg-cream border border-parchment rounded p-3">
                    <dt class="text-xs text-umber font-medium">Pricing</dt>
                    <dd class="text-bark mt-0.5">{{ $details['pricing_note'] ?? 'Current prices to be confirmed' }}</dd>
                </div>
                <div class="bg-cream border border-parchment rounded p-3">
                    <dt class="text-xs text-umber font-medium">Delivery Coverage</dt>
                    <dd class="text-bark mt-0.5">{{ $details['delivery_coverage'] ?? 'Delivery coverage to be confirmed' }}</dd>
                </div>
                <div class="bg-cream border border-parchment rounded p-3">
                    <dt class="text-xs text-umber font-medium">Group Bookings &amp; Events</dt>
                    <dd class="text-bark mt-0.5">{{ $details['group_bookings_events'] ?? 'Group & event availability to be confirmed' }}</dd>
                </div>
            </dl>
        </section>

        @if ($demoVisit)
            <section class="bg-cream border border-parchment rounded p-4 flex flex-wrap items-center justify-between gap-3 text-sm">
                <div>
                    <span class="font-semibold text-bark">Pre-seeded Demo Stop:</span>
                    <span class="text-umber">{{ $demoVisit->guest_name }} ({{ $demoVisit->origin_region }}) — {{ $demoVisit->target_window }}</span>
                </div>
                <a href="{{ route('itinerary.show', $demoVisit->token) }}"
                   class="px-3 py-1.5 rounded bg-parchment hover:bg-ochre hover:text-cream text-bark font-medium transition-colors">
                    Open Sample Journey →
                </a>
            </section>
        @endif
    </div>

    {{-- Right Column: Save to Trip Itinerary Form --}}
    <div class="lg:col-span-5">
        <section class="bg-sand border border-parchment rounded p-6">
            <h2 class="text-lg font-bold text-bark mb-1">Save to Your Accra Itinerary</h2>
            <p class="text-xs text-umber mb-5">
                Capture your discovery source and trip plans before flying so staff in Accra can confirm your visit.
            </p>

            <form action="{{ route('itinerary.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="origin_region" class="block text-xs font-semibold uppercase tracking-wide text-bark mb-1">
                        Travelling From (Diaspora / Region)
                    </label>
                    <select id="origin_region" name="origin_region"
                            class="w-full bg-cream border border-parchment rounded px-3 py-2 text-sm text-bark focus:outline-none focus:border-ochre">
                        @foreach ($originRegions as $region)
                            <option value="{{ $region }}" @selected(old('origin_region', $selectedRegion) === $region)>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="discovery_source" class="block text-xs font-semibold uppercase tracking-wide text-bark mb-1">
                        How You Discovered De Klinic
                    </label>
                    <select id="discovery_source" name="discovery_source"
                            class="w-full bg-cream border border-parchment rounded px-3 py-2 text-sm text-bark focus:outline-none focus:border-ochre">
                        @foreach ($discoverySources as $key => $label)
                            <option value="{{ $key }}" @selected(old('discovery_source', $selectedSource) === $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="discovery_label" class="block text-xs font-semibold uppercase tracking-wide text-bark mb-1">
                        Specific Source / Curator Note (Optional)
                    </label>
                    <input id="discovery_label" name="discovery_label" type="text"
                           value="{{ old('discovery_label') }}"
                           placeholder="e.g., London Diaspora Group · December List"
                           class="w-full bg-cream border border-parchment rounded px-3 py-2 text-sm text-bark focus:outline-none focus:border-ochre">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="guest_name" class="block text-xs font-semibold uppercase tracking-wide text-bark mb-1">
                            Your Name
                        </label>
                        <input id="guest_name" name="guest_name" type="text" required
                               value="{{ old('guest_name') }}"
                               placeholder="Kwame Boateng"
                               class="w-full bg-cream border border-parchment rounded px-3 py-2 text-sm text-bark focus:outline-none focus:border-ochre">
                    </div>

                    <div>
                        <label for="party_size" class="block text-xs font-semibold uppercase tracking-wide text-bark mb-1">
                            Party Size
                        </label>
                        <input id="party_size" name="party_size" type="number" min="1" max="30" required
                               value="{{ old('party_size', 2) }}"
                               class="w-full bg-cream border border-parchment rounded px-3 py-2 text-sm text-bark focus:outline-none focus:border-ochre">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="target_window" class="block text-xs font-semibold uppercase tracking-wide text-bark mb-1">
                            Target Trip Window
                        </label>
                        <input id="target_window" name="target_window" type="text"
                               value="{{ old('target_window', 'December 2026') }}"
                               placeholder="December 2026"
                               class="w-full bg-cream border border-parchment rounded px-3 py-2 text-sm text-bark focus:outline-none focus:border-ochre">
                    </div>

                    <div>
                        <label for="guest_contact" class="block text-xs font-semibold uppercase tracking-wide text-bark mb-1">
                            Email / Handle (Optional)
                        </label>
                        <input id="guest_contact" name="guest_contact" type="text"
                               value="{{ old('guest_contact') }}"
                               placeholder="kwame@example.com"
                               class="w-full bg-cream border border-parchment rounded px-3 py-2 text-sm text-bark focus:outline-none focus:border-ochre">
                    </div>
                </div>

                <div>
                    <label for="trip_note" class="block text-xs font-semibold uppercase tracking-wide text-bark mb-1">
                        Pre-Trip Note / Arrival Intent
                    </label>
                    <textarea id="trip_note" name="trip_note" rows="3"
                              placeholder="Landing in Accra mid-December; hoping to grab local food and pub drinks with friends."
                              class="w-full bg-cream border border-parchment rounded px-3 py-2 text-sm text-bark focus:outline-none focus:border-ochre">{{ old('trip_note') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-ochre hover:bg-ochreDark text-cream font-semibold py-2.5 px-4 rounded transition-colors">
                    Save to Accra Itinerary
                </button>
            </form>
        </section>
    </div>
</div>
@endsection