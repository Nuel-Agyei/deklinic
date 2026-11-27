@extends('layouts.app')

@section('title', 'Share your visit | ' . $visit->guest_name)
@section('step', '4')

@section('content')
@php
    $venueName = $visit->venue?->name ?? 'De Klinic Restaurant & Pub';
    $channels = [
        'Diaspora Community / Group' => 'A community or group chat',
        'Travel Curator / Guide'     => 'A travel guide or curator',
        'Accra Host / Friends'       => 'Friends or hosts in Accra',
        'Return Trip List'           => 'My own list for next trip',
    ];
@endphp

<div class="space-y-8">

    <div class="space-y-3">
        <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">
            Tell others about {{ $venueName }}
        </h1>
        <p class="max-w-2xl text-lg text-muted">
            Your word helps the next traveller from {{ $visit->origin_region }} decide. Edit the message, pick who it's for, and save it.
        </p>
    </div>

    @if ($visit->shared_at)
        <div class="rounded border border-ok bg-okTint px-5 py-4 text-ok" role="status">
            <p class="font-bold">✓ Shared</p>
            <p class="mt-0.5 text-base">
                Sent to {{ $channels[$visit->shared_with_channel] ?? $visit->shared_with_channel }}
                on {{ $visit->shared_at->format('j M Y, H:i') }}.
                Your code: <span class="font-mono font-bold">{{ $visit->referral_code }}</span>
            </p>
        </div>
    @endif

    {{-- One form, one column. Nothing to compare, so no split layout. --}}
    <form action="{{ route('referral.share', $visit->token) }}" method="POST" class="panel max-w-2xl space-y-6 p-6">
        @csrf

        <fieldset>
            <legend class="label">Who is this for?</legend>
            <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($channels as $value => $text)
                    <label class="flex cursor-pointer items-center gap-3 rounded border border-edge bg-white px-4 py-3 text-base has-[:checked]:border-ink has-[:checked]:bg-paper has-[:checked]:font-semibold">
                        <input type="radio" name="shared_with_channel" value="{{ $value }}"
                               class="h-4 w-4 accent-ink"
                               @checked(old('shared_with_channel', $visit->shared_with_channel ?? array_key_first($channels)) === $value)>
                        <span>{{ $text }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>

        <div>
            <div class="flex items-end justify-between gap-3">
                <label for="referral_note" class="label !mb-0">Your message</label>
                <button type="button" id="copy-note" class="text-sm font-semibold underline hover:text-link">
                    Copy message
                </button>
            </div>

            @if ($visit->ai_referral_draft)
                <p class="mt-1 text-sm text-muted">We started this for you. Change anything you like.</p>
            @endif

            <textarea id="referral_note" name="referral_note" rows="6" required
                      class="field mt-2">{{ old('referral_note', $visit->referral_note ?? $visit->ai_referral_draft) }}</textarea>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit" class="btn-primary">Save and share</button>
            <a href="{{ route('discover') }}" class="text-base font-semibold underline hover:text-link">
                Start a new trip
            </a>
        </div>
    </form>

    <p class="text-sm text-muted">
        {{ $visit->guest_name }} · {{ $visit->target_window ?: 'Accra trip' }} ·
        {{ $visit->isConfirmed() ? 'Visit confirmed' : 'Visit not yet confirmed' }}
    </p>
</div>
@endsection

@push('scripts')
<script>
    // Copy button: gives visible feedback so people know it worked.
    document.getElementById('copy-note')?.addEventListener('click', async function () {
        const text = document.getElementById('referral_note').value;
        const original = this.textContent;
        try {
            await navigator.clipboard.writeText(text);
            this.textContent = 'Copied ✓';
        } catch (e) {
            this.textContent = 'Press Ctrl+C to copy';
            document.getElementById('referral_note').select();
        }
        setTimeout(() => (this.textContent = original), 2000);
    });
</script>
@endpush