@extends('layouts.app')

@section('title', 'De Klinic | Plan your Accra visit')
@section('step', '1')

@section('content')
    <div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-12">

        {{-- LEFT: what is this place? --}}
        <div class="space-y-8 lg:col-span-7">
            <section class="space-y-4">
                <p class="text-sm font-semibold text-ochreDark">{{ $venue->city }}, {{ $venue->country }}</p>

                <h1 class="text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
                    {{ $venue->name }}
                </h1>

                <p class="text-lg leading-relaxed text-ink">
                    {{ $venue->summary }}
                </p>

                <p class="text-base text-umber">
                    Run by <span class="font-semibold text-ink">{{ $venue->owner_name }}</span>.
                </p>
            </section>

            <section class="border-l-4 border-ochre pl-5">
                <h2 class="text-lg font-bold">Why people pass it on</h2>
                <p class="mt-2 text-base leading-relaxed">
                    {{ $venue->community_story }}
                </p>
            </section>

            @php
                $details = $venue->sample_details ?? [];
                $rows = [
                    'Food and drink' => $details['menu_highlights'] ?? 'Local plates and pub drinks',
                    'Prices' => $details['pricing_note'] ?? 'To be confirmed',
                    'Delivery' => $details['delivery_coverage'] ?? 'To be confirmed',
                    'Groups, events' => $details['group_bookings_events'] ?? 'To be confirmed',
                ];
            @endphp

            <section class="panel">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-parchment px-5 py-4">
                    <h2 class="text-lg font-bold">Good to know before you go</h2>
                    <span class="rounded border border-parchment bg-cream px-2.5 py-1 text-xs font-semibold text-umber">
                        Sample info, not confirmed
                    </span>
                </div>
                @php
                    $pubFeatures = $details['pub_features'] ?? [];
                    $sampleMenu = $details['sample_menu'] ?? [];
                @endphp

                <section class="panel">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-parchment px-5 py-4">
                        <div>
                            <h2 class="text-lg font-bold">Local food &amp; pub experience</h2>
                            <p class="text-sm text-umber">Core vibe for your Accra stop, shown with sample items.</p>
                        </div>
                        <span class="rounded border border-parchment bg-cream px-2.5 py-1 text-xs font-semibold text-umber">
                            Sample items · TBC
                        </span>
                    </div>

                    <div class="grid grid-cols-1 divide-y divide-parchment md:grid-cols-2 md:divide-x md:divide-y-0">
                        <div class="p-5 space-y-3">
                            <h3 class="text-sm font-bold uppercase tracking-wide text-umber">Sample menu highlights</h3>
                            <ul class="divide-y divide-parchment border-y border-parchment text-sm">
                                @foreach ($sampleMenu as $item)
                                    <li class="py-2.5 flex items-center justify-between gap-3">
                                        <span class="font-medium text-ink">{{ $item['name'] }}</span>
                                        <span class="text-xs text-umber shrink-0">{{ $item['status'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="p-5 space-y-3">
                            <h3 class="text-sm font-bold uppercase tracking-wide text-umber">Pub &amp; night features</h3>
                            <ul class="space-y-2 text-sm text-ink">
                                @foreach ($pubFeatures as $feature)
                                    <li class="flex items-start gap-2">
                                        <span class="font-bold text-ochreDark">—</span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>

                <dl class="divide-y divide-parchment">
                    @foreach ($rows as $term => $value)
                        <div class="grid grid-cols-1 gap-1 px-5 py-3.5 sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-semibold text-umber">{{ $term }}</dt>
                            <dd class="text-base sm:col-span-2">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>

                @if (!empty($details['data_note']))
                    <p class="border-t border-parchment px-5 py-3 text-sm text-umber">{{ $details['data_note'] }}</p>
                @endif
            </section>

            @if ($demoVisit)
                <section
                    class="flex flex-col gap-3 rounded-md border border-dashed border-umber px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-bold text-forest">Demo shortcut</p>
                        <p class="text-base">
                            See a finished trip for <span class="font-semibold">{{ $demoVisit->guest_name }}</span>
                            <span class="text-umber">from {{ $demoVisit->origin_region }}</span>
                        </p>
                    </div>
                    <a href="{{ route('itinerary.show', $demoVisit->token) }}"
                        class="btn-secondary shrink-0 !py-2 text-sm">
                        Open demo trip
                    </a>
                </section>
            @endif
        </div>

        {{-- RIGHT: the one action --}}
        <aside class="lg:sticky lg:top-6 lg:col-span-5">
            <form action="{{ route('itinerary.store') }}" method="POST" class="panel overflow-hidden">
                @csrf

                <div class="border-b border-parchment bg-cream px-6 py-5">
                    <h2 class="text-2xl font-bold">Save this to your Ghana trip</h2>
                    <p class="mt-1 text-base text-umber">
                        Takes a minute. You get a code to show our staff when you arrive.
                    </p>
                </div>

                <div class="space-y-5 p-6">

                    <div>
                        <label for="guest_name" class="label">Your name</label>
                        <input id="guest_name" name="guest_name" type="text" required autocomplete="name"
                            value="{{ old('guest_name') }}" placeholder="Kwame Boateng" class="field">
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="origin_region" class="label">Where are you travelling from?</label>
                            <select id="origin_region" name="origin_region" class="field">
                                @foreach ($originRegions as $region)
                                    <option value="{{ $region }}" @selected(old('origin_region', $selectedRegion) === $region)>{{ $region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="target_window" class="label">When are you in Ghana?</label>
                            <input id="target_window" name="target_window" type="text"
                                value="{{ old('target_window', 'December 2026') }}" placeholder="December 2026"
                                class="field">
                        </div>
                    </div>

                    <div>
                        <label for="party_size" class="label">How many people?</label>
                        <input id="party_size" name="party_size" type="number" min="1" max="30" required
                            value="{{ old('party_size', 2) }}" class="field sm:w-32">
                    </div>

                    <div>
                        <label for="discovery_source" class="label">How did you hear about De Klinic?</label>
                        <select id="discovery_source" name="discovery_source" class="field">
                            @foreach ($discoverySources as $key => $label)
                                <option value="{{ $key }}" @selected(old('discovery_source', $selectedSource) === $key)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Optional extras stay out of the way until wanted --}}
                    <details class="group rounded border border-parchment bg-cream"
                        @if (old('discovery_label') || old('guest_contact') || old('trip_note')) open @endif>
                        <summary class="cursor-pointer select-none px-4 py-3 text-base font-semibold hover:text-ochreDark">
                            Add more details <span class="font-normal text-umber">(optional)</span>
                        </summary>

                        <div class="space-y-5 border-t border-parchment p-4">
                            <div>
                                <label for="discovery_label" class="label">Who or what told you about us?</label>
                                <input id="discovery_label" name="discovery_label" type="text"
                                    value="{{ old('discovery_label') }}"
                                    placeholder="e.g. London Ghanaians WhatsApp group" class="field">
                            </div>

                            <div>
                                <label for="guest_contact" class="label">Email or phone</label>
                                <input id="guest_contact" name="guest_contact" type="text" autocomplete="email"
                                    value="{{ old('guest_contact') }}" placeholder="kwame@example.com" class="field">
                            </div>
                            <div class="mb-2 flex flex-wrap gap-2">
                                @foreach (['Local food', 'Pub & drinks', 'Live band night', 'Group table'] as $tag)
                                    <button type="button"
                                        onclick="const el = document.getElementById('trip_note'); el.value = el.value ? el.value + ', ' + '{{ $tag }}' : 'Interested in: {{ $tag }}';"
                                        class="rounded border border-parchment bg-white px-2.5 py-1 text-xs font-semibold text-ink hover:border-ink">
                                        + {{ $tag }}
                                    </button>
                                @endforeach
                            </div>
                            <div>
                                <label for="trip_note" class="label">Anything we should know?</label>
                                <textarea id="trip_note" name="trip_note" rows="3"
                                    placeholder="Arriving mid-December, planning dinner with friends." class="field">{{ old('trip_note') }}</textarea>
                            </div>
                        </div>
                    </details>

                    <button type="submit" class="btn-primary w-full">Save to my trip</button>
                </div>
            </form>
        </aside>
    </div>
@endsection
