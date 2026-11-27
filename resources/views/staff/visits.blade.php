@extends('layouts.app')

@section('title', 'Staff Visit Confirmation — De Klinic')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 border-b border-parchment pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-ochreDark block">
                Stage 03 · Visit in Ghana
            </span>
            <h1 class="text-2xl font-bold text-ink mt-0.5">
                Staff Visit Confirmation Desk
            </h1>
            <p class="text-sm text-umber mt-0.5">
                Search saved diaspora itineraries and confirm physical visits at De Klinic in Accra.
            </p>
        </div>

        <form method="GET" action="{{ route('staff.visits') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Guest, code, or origin..."
                   class="bg-sand border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">

            <select name="status"
                    class="bg-sand border border-parchment px-3 py-2 text-sm text-ink focus:outline-none focus:border-ink">
                <option value="" @selected($statusFilter === '')>All Statuses</option>
                <option value="saved" @selected($statusFilter === 'saved')>Saved</option>
                <option value="confirmed" @selected($statusFilter === 'confirmed')>Confirmed</option>
            </select>

            <button type="submit"
                    class="bg-ink hover:bg-umber text-cream text-xs font-bold uppercase tracking-wider px-4 py-2.5 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-sand border border-parchment overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="border-b border-parchment bg-parchment/40 text-xs font-bold uppercase tracking-wider text-umber">
                    <th class="py-3 px-4">Guest &amp; Origin</th>
                    <th class="py-3 px-4">Discovery &amp; Window</th>
                    <th class="py-3 px-4">Code</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-parchment">
                @forelse ($visits as $visit)
                    <tr class="align-top hover:bg-cream/60">
                        <td class="py-3.5 px-4">
                            <a href="{{ route('itinerary.show', $visit->token) }}" class="font-bold text-ink hover:underline">
                                {{ $visit->guest_name }}
                            </a>
                            <div class="text-xs text-umber mt-0.5">
                                {{ $visit->origin_region }} · Party of {{ $visit->party_size }}
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            <div class="text-ink font-medium">{{ $visit->discovery_label }}</div>
                            <div class="text-xs text-umber mt-0.5">{{ $visit->target_window ?: 'Upcoming Trip' }}</div>
                        </td>

                        <td class="py-3.5 px-4 font-mono text-xs font-bold text-ink whitespace-nowrap">
                            {{ $visit->referral_code }}
                        </td>

                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if ($visit->isConfirmed())
                                <span class="inline-block px-2.5 py-1 text-xs font-bold uppercase tracking-wider bg-forestTint text-forest border border-forest">
                                    Confirmed
                                </span>
                                <div class="text-xs text-umber mt-1">
                                    {{ $visit->confirmed_by ?: 'Floor Staff' }}
                                    @if ($visit->confirmed_at)
                                        · {{ $visit->confirmed_at->format('M j, H:i') }}
                                    @endif
                                </div>
                            @else
                                <span class="inline-block px-2.5 py-1 text-xs font-bold uppercase tracking-wider bg-cream text-ink border border-ink">
                                    Saved
                                </span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            @if (! $visit->isConfirmed())
                                <form action="{{ route('staff.visits.confirm', $visit) }}" method="POST" class="inline-flex flex-wrap justify-end items-center gap-2">
                                    @csrf
                                    <input type="text" name="confirmed_by" placeholder="Staff initial/name"
                                           class="bg-cream border border-parchment px-2.5 py-1.5 text-xs text-ink w-36 focus:outline-none focus:border-ink">
                                    <button type="submit"
                                            class="px-3 py-1.5 bg-forest hover:opacity-90 text-cream text-xs font-bold uppercase tracking-wider transition-opacity">
                                        Confirm Visit
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('referral.show', $visit->token) }}"
                                   class="inline-block px-3 py-1.5 bg-ochre hover:bg-ochreDark text-cream text-xs font-bold uppercase tracking-wider transition-colors">
                                    Open Referral
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-sm text-umber">
                            No itineraries match this filter. <a href="{{ route('discover') }}" class="underline font-semibold text-ink">Start from Discovery</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection