@extends('layouts.app')

@section('title', 'Refer & Return — ' . $visit->guest_name)

@section('content')
<div class="space-y-6">
    <div class="border-b border-parchment pb-4 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-ochreDark block">
                Stage 04 · Refer and Return
            </span>
            <h1 class="text-2xl font-bold text-ink mt-0.5">
                Share Back — {{ $visit->venue?->name ?? 'De Klinic Restaurant & Pub' }}
            </h1>
        </div>
        <div class="text-xs font-mono bg-sand border border-parchment px-3 py-1.5 text-ink">
            Code: <strong>{{ $visit->referral_code }}</strong>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Left Pane: Visit Proof & Draft (6 cols) --}}
        <section class="lg:col-span-6 bg-sand border border-parchment p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-parchment pb-3">
                <h2 class="text-xs font-bold uppercase tracking-widest text-ink">
                    Visit &amp; Referral Draft
                </h2>
                <span class="text-xs font-semibold uppercase {{ $visit->isConfirmed() ? 'text-forest' : 'text-umber' }}">
                    {{ $visit->isConfirmed() ? 'Confirmed Visit' : 'Saved Itinerary' }}
                </span>
            </div>

            <p class="text-sm text-umber">
                Traveller: <strong class="text-ink">{{ $visit->guest_name }}</strong> ({{ $visit->origin_region }}) · Target: <strong class="text-ink">{{ $visit->target_window ?: 'Accra Trip' }}</strong>
            </p>

            @if ($visit->ai_referral_draft)
                <div class="bg-cream border border-parchment border-l-4 border-l-ochre p-4 space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-ochreDark">
                            Generated Referral Draft
                        </span>
                        <button type="button"
                                onclick="navigator.clipboard.writeText(document.getElementById('referral_note').value)"
                                class="text-xs font-semibold uppercase tracking-wider underline text-ink hover:text-ochreDark">
                            Copy Note
                        </button>
                    </div>
                    <p class="text-sm leading-relaxed text-ink">
                        {{ $visit->ai_referral_draft }}
                    </p>
                </div>
            @endif

            @if ($visit->shared_at)
                <div class="bg-forestTint border-l-4 border-forest p-4 text-sm text-forest">
                    <p class="font-bold text-xs uppercase tracking-wider">Logged Referral Share</p>
                    <p class="mt-1">
                        Shared with <strong>{{ $visit->shared_with_channel }}</strong> on {{ $visit->shared_at->format('M j, Y · H:i') }} using code <span class="font-mono font-bold">{{ $visit->referral_code }}</span>.
                    </p>
                </div>
            @endif
        </section>

        {{-- Right Pane: Share-Back Form (6 cols) --}}
        <section class="lg:col-span-6 bg-sand border border-parchment p-6">
            <h2 class="text-xs font-bold uppercase tracking-widest text-ink mb-1">
                Log Referral Back to Circle
            </h2>
            <p class="text-xs text-umber mb-4">
                Record who receives this recommendation so future travellers from {{ $visit->origin_region }} can trace the path.
            </p>

            <form action="{{ route('referral.share', $visit->token) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="shared_with_channel" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                        Share Channel / Recipient
                    </label>
                    <select id="shared_with_channel" name="shared_with_channel"
                            class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
                        @foreach (['Diaspora Community / Group', 'Travel Curator / Guide', 'Accra Host / Friends', 'Return Trip List'] as $channel)
                            <option value="{{ $channel }}" @selected(old('shared_with_channel', $visit->shared_with_channel) === $channel)>
                                {{ $channel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="referral_note" class="block text-xs font-bold uppercase tracking-wider text-umber mb-1">
                        Message to Share
                    </label>
                    <textarea id="referral_note" name="referral_note" rows="5" required
                              class="w-full bg-cream border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">{{ old('referral_note', $visit->referral_note ?? $visit->ai_referral_draft) }}</textarea>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                    <button type="submit"
                            class="bg-ochre hover:bg-ochreDark text-cream text-xs font-bold uppercase tracking-wider px-5 py-2.5 transition-colors">
                        Save &amp; Log Referral
                    </button>

                    <a href="{{ route('discover') }}"
                       class="text-xs font-bold uppercase tracking-wider text-ink underline hover:text-ochreDark">
                        Start New Journey
                    </a>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection