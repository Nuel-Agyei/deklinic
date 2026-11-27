@extends('layouts.app')

@section('title', 'Staff | Confirm visits')

@section('content')
@php
    $savedCount = $visits->filter(fn ($v) => ! $v->isConfirmed())->count();
    $doneCount  = $visits->count() - $savedCount;
@endphp

<div class="space-y-6">

    <div class="space-y-1">
        <h1 class="text-3xl font-bold tracking-tight">Confirm a guest's visit</h1>
        <p class="text-lg text-umber">
            Ask the guest for their code, find them below, then press <span class="font-semibold text-ink">Confirm visit</span>.
        </p>
    </div>

    {{-- Search is the main tool here, so it gets the most space --}}
    <form method="GET" action="{{ route('staff.visits') }}" class="panel flex flex-col gap-3 p-4 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label for="search" class="label">Search by code, name or country</label>
            <input id="search" type="text" name="search" value="{{ $search }}" autofocus
                   placeholder="e.g. the guest's code" class="field font-mono">
        </div>

        <div class="sm:w-48">
            <label for="status" class="label">Show</label>
            <select id="status" name="status" class="field">
                <option value="" @selected($statusFilter === '')>Everyone</option>
                <option value="saved" @selected($statusFilter === 'saved')>Not arrived yet</option>
                <option value="confirmed" @selected($statusFilter === 'confirmed')>Already confirmed</option>
            </select>
        </div>

        <button type="submit" class="btn-primary sm:py-2.5">Search</button>
    </form>

    <p class="text-sm text-umber">
        {{ $visits->count() }} {{ \Illuminate\Support\Str::plural('guest', $visits->count()) }} shown:
        {{ $savedCount }} waiting, {{ $doneCount }} confirmed.
    </p>

    <div class="space-y-3">
        @forelse ($visits as $visit)
            <article class="panel flex flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between {{ $visit->isConfirmed() ? 'bg-cream' : '' }}">

                <div class="min-w-0 space-y-1">
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('itinerary.show', $visit->token) }}" class="text-xl font-bold hover:underline">
                            {{ $visit->guest_name }}
                        </a>
                        <span class="rounded border border-parchment bg-white px-2.5 py-0.5 font-mono text-sm font-bold">
                            {{ $visit->referral_code }}
                        </span>
                    </div>

                    <p class="text-base text-umber">
                        Party of {{ $visit->party_size }} · from {{ $visit->origin_region }} · {{ $visit->target_window ?: 'Upcoming trip' }}
                    </p>
                    <p class="text-sm text-umber">Heard about us via {{ $visit->discovery_label }}</p>
                </div>

                <div class="md:w-80 md:shrink-0">
                    @if ($visit->isConfirmed())
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <span class="inline-block rounded border border-forest bg-forestTint px-2.5 py-1 text-sm font-bold text-forest">
                                    ✓ Confirmed
                                </span>
                                <p class="mt-1 text-sm text-umber">
                                    {{ $visit->confirmed_by ?: 'Floor staff' }}
                                    @if ($visit->confirmed_at) · {{ $visit->confirmed_at->format('j M, H:i') }}@endif
                                </p>
                            </div>
                            <a href="{{ route('referral.show', $visit->token) }}" class="btn-secondary !px-4 !py-2 text-sm">
                                Referral
                            </a>
                        </div>
                    @else
                        <form action="{{ route('staff.visits.confirm', $visit) }}" method="POST" class="space-y-2">
                            @csrf
                            <label for="confirmed_by_{{ $visit->id }}" class="text-sm font-semibold">Your name</label>
                            <div class="flex gap-2">
                                <input id="confirmed_by_{{ $visit->id }}" type="text" name="confirmed_by"
                                       placeholder="e.g. Efua" class="field min-w-0 flex-1 !py-2">
                                <button type="submit" class="btn-confirm shrink-0">Confirm visit</button>
                            </div>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="panel px-6 py-10 text-center">
                <p class="text-lg font-semibold">No guests match that search.</p>
                <p class="mt-1 text-base text-umber">
                    Check the code again, or
                    <a href="{{ route('staff.visits') }}" class="font-semibold text-ink underline">clear the search</a>.
                </p>
            </div>
        @endforelse
    </div>
</div>
@endsection