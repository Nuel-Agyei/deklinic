<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'De Klinic | Plan your Accra visit')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#FAF6F0',
                        sand: '#F1E8DC',
                        parchment: '#DFD0BC',
                        ink: '#2B1E14',
                        umber: '#654E3A',
                        ochre: '#B87324',
                        ochreDark: '#945916',
                        forest: '#2B5842',
                        forestTint: '#E5EFE9',
                    },
                    borderRadius: {
                        DEFAULT: '4px',
                        sm: '2px',
                        md: '6px',
                    }
                }
            }
        }
    </script>

    {{-- Shared building blocks. Written once so every page looks and behaves the same. --}}
    @verbatim
    <style type="text/tailwindcss">
        @layer components {
            .label {
                @apply block mb-1.5 text-sm font-semibold text-ink;
            }
            .field {
                @apply block w-full rounded border border-parchment bg-white px-3.5 py-2.5 text-base text-ink placeholder:text-umber/60 focus:border-ink focus:outline-none focus:ring-2 focus:ring-ink/15;
            }
            .btn-primary {
                @apply inline-flex items-center justify-center rounded bg-ochreDark px-5 py-3 text-base font-semibold text-white transition-colors hover:bg-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-ink focus-visible:ring-offset-2 focus-visible:ring-offset-cream;
            }
            .btn-secondary {
                @apply inline-flex items-center justify-center rounded border border-ink bg-transparent px-5 py-3 text-base font-semibold text-ink transition-colors hover:bg-ink hover:text-cream focus:outline-none focus-visible:ring-2 focus-visible:ring-ink focus-visible:ring-offset-2 focus-visible:ring-offset-cream;
            }
            .btn-confirm {
                @apply inline-flex items-center justify-center rounded bg-forest px-5 py-2.5 text-base font-semibold text-white transition-colors hover:bg-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-ink focus-visible:ring-offset-2 focus-visible:ring-offset-cream;
            }
            .panel {
                @apply rounded-md border border-parchment bg-sand;
            }
        }
    </style>
    @endverbatim
</head>
<body class="flex min-h-screen flex-col bg-cream text-base text-ink antialiased selection:bg-ochre selection:text-white">

    @php
        $isStaff = request()->routeIs('staff.*');
        $step = (int) trim($__env->yieldContent('step', '0'));
        $steps = ['Discover', 'Save', 'Visit', 'Share'];
    @endphp

    <header class="border-b border-parchment bg-sand">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-4">
            <a href="{{ route('discover') }}" class="leading-tight">
                <span class="block text-xl font-bold tracking-tight">De Klinic</span>
                <span class="block text-sm text-umber">Restaurant &amp; Pub · Accra</span>
            </a>

            @if ($isStaff)
                <div class="flex items-center gap-3 text-sm">
                    <span class="rounded bg-ink px-2.5 py-1 font-semibold text-cream">Staff view</span>
                    <a href="{{ route('discover') }}" class="font-medium text-umber underline hover:text-ink">Traveller view</a>
                </div>
            @else
                <a href="{{ route('staff.visits') }}" class="text-sm font-medium text-umber underline hover:text-ink">Staff view</a>
            @endif
        </div>

        {{-- Progress: only on traveller pages. Shows where you are in the trip. --}}
        @if (! $isStaff && $step > 0)
            <div class="border-t border-parchment bg-cream">
                <ol class="mx-auto flex max-w-6xl items-center gap-2 px-5 py-3 text-sm" aria-label="Your progress">
                    @foreach ($steps as $i => $label)
                        @php
                            $n = $i + 1;
                            $done = $n < $step;
                            $current = $n === $step;
                        @endphp
                        <li class="flex items-center gap-2 {{ $current ? 'font-bold text-ink' : ($done ? 'font-medium text-forest' : 'text-umber') }}"
                            @if ($current) aria-current="step" @endif>
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border text-xs font-bold
                                {{ $current ? 'border-ink bg-ink text-cream' : ($done ? 'border-forest bg-forest text-white' : 'border-parchment bg-cream text-umber') }}">
                                {{ $done ? '✓' : $n }}
                            </span>
                            <span>{{ $label }}</span>
                        </li>
                        @if (! $loop->last)
                            <li class="h-px flex-1 {{ $done ? 'bg-forest' : 'bg-parchment' }}" aria-hidden="true"></li>
                        @endif
                    @endforeach
                </ol>
            </div>
        @endif
    </header>

    <main class="mx-auto w-full max-w-6xl flex-1 px-5 py-8 sm:py-10">
        @if (session('status'))
            <div class="mb-6 rounded border border-forest bg-forestTint px-4 py-3 text-base font-medium text-forest" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded border border-red-700 bg-red-50 px-4 py-3 text-base text-red-950" role="alert">
                <p class="mb-1 font-semibold">Please fix the following:</p>
                <ul class="list-inside list-disc space-y-0.5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-parchment bg-sand">
        <div class="mx-auto max-w-6xl px-5 py-4 text-sm text-umber">
            Prototype using sample data. Menu, prices, delivery and events are not confirmed.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>