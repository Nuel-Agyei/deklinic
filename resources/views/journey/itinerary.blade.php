@extends('layouts.app')

@section('title', 'Your Accra trip | ' . $visit->guest_name)
@section('step', $visit->isConfirmed() ? '4' : '3')

@section('content')
@php
    $venueName = $visit->venue?->name ?? 'De Klinic Restaurant & Pub';
    $people = $visit->party_size . ' ' . \Illuminate\Support\Str::plural('person', $visit->party_size);
@endphp

<div class="space-y-8">

    {{-- Headline: says plainly what state the trip is in --}}
    <div class="space-y-3">
        @if ($visit->isConfirmed())
            <span class="inline-block rounded border border-ok bg-okTint px-3 py-1 text-sm font-bold text-ok">
                ✓ Visit confirmed
            </span>
            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">
                Thank you for visiting, {{ $visit->guest_name }}.
            </h1>
            <p class="text-lg text-muted">
                {{ $visit->confirmed_by ?: 'Our floor staff' }} confirmed your visit
                @if ($visit->confirmed_at) on {{ $visit->confirmed_at->format('j M Y, H:i') }}@endif.
            </p>
        @else
            <span class="inline-block rounded border border-ink bg-surface px-3 py-1 text-sm font-bold">
                Saved, waiting for your arrival
            </span>
            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">
                You're all set, {{ $visit->guest_name }}.
            </h1>
            <p class="text-lg text-muted">
                Show the code below to our staff when you get to {{ $venueName }}.
            </p>
        @endif
    </div>

    <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">

        {{-- LEFT: the pass. This is the thing the traveller keeps. --}}
        <section class="panel lg:col-span-7">
            <div class="border-b border-edge bg-paper px-6 py-6 text-center">
                <p class="text-sm font-semibold text-muted">Your check-in code</p>
                <p class="mt-1 break-all font-mono text-4xl font-bold tracking-widest sm:text-5xl">
                    {{ $visit->referral_code }}
                </p>
            </div>

            <dl class="divide-y divide-edge">
                <div class="grid grid-cols-3 gap-3 px-6 py-3.5">
                    <dt class="text-sm font-semibold text-muted">Where</dt>
                    <dd class="col-span-2">{{ $venueName }}, Accra</dd>
                </div>
                <div class="grid grid-cols-3 gap-3 px-6 py-3.5">
                    <dt class="text-sm font-semibold text-muted">Who</dt>
                    <dd class="col-span-2">{{ $visit->guest_name }}, party of {{ $visit->party_size }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-3 px-6 py-3.5">
                    <dt class="text-sm font-semibold text-muted">When</dt>
                    <dd class="col-span-2">{{ $visit->target_window ?: 'Upcoming trip' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-3 px-6 py-3.5">
                    <dt class="text-sm font-semibold text-muted">Travelling from</dt>
                    <dd class="col-span-2">{{ $visit->origin_region }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-3 px-6 py-3.5">
                    <dt class="text-sm font-semibold text-muted">Heard about us via</dt>
                    <dd class="col-span-2">{{ $visit->discovery_label }}</dd>
                </div>
                @if ($visit->trip_note)
                    <div class="grid grid-cols-3 gap-3 px-6 py-3.5">
                        <dt class="text-sm font-semibold text-muted">Your note</dt>
                        <dd class="col-span-2">{{ $visit->trip_note }}</dd>
                    </div>
                @endif
            </dl>
        </section>

        {{-- RIGHT: what happens next --}}
        <div class="space-y-5 lg:col-span-5">

            @if ($visit->ai_itinerary_brief)
                <section class="border-l-4 border-ok pl-5">
                    <h2 class="text-lg font-bold">A quick note for your trip</h2>
                    <p class="mt-2 text-base leading-relaxed">{{ $visit->ai_itinerary_brief }}</p>
                </section>
            @endif

            <section class="panel space-y-4 p-6">
                <h2 class="text-lg font-bold">What happens next</h2>

                <ol class="space-y-3 text-base">
                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-ok text-xs font-bold text-white">✓</span>
                        <span>Trip saved</span>
                    </li>
                    <li class="flex gap-3">
                        @if ($visit->isConfirmed())
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-ok text-xs font-bold text-white">✓</span>
                            <span>Visit confirmed by staff</span>
                        @else
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 border-ink text-xs font-bold">2</span>
                            <span class="font-semibold">Arrive and show your code to staff</span>
                        @endif
                    </li>
                    <li class="flex gap-3 {{ $visit->isConfirmed() ? '' : 'text-muted' }}">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 {{ $visit->isConfirmed() ? 'border-ink font-bold' : 'border-edge' }} text-xs">3</span>
                        <span class="{{ $visit->isConfirmed() ? 'font-semibold' : '' }}">Tell friends about your visit</span>
                    </li>
                </ol>

                <div class="space-y-3 border-t border-edge pt-4">
                    @if ($visit->isConfirmed())
                        <a href="{{ route('referral.show', $visit->token) }}" class="btn-primary w-full">
                            Share your experience
                        </a>
                    @else
                        <a href="{{ route('staff.visits', ['search' => $visit->referral_code]) }}" class="btn-primary w-full">
                            Staff: confirm this visit
                        </a>
                        <p class="text-center text-sm text-muted">
                            For the demo, this opens the staff screen with this code filled in.
                        </p>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection