<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'De Klinic — Diaspora to Visit')</title>
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
                        DEFAULT: '3px',
                        sm: '2px',
                        md: '4px',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-cream text-ink antialiased min-h-screen flex flex-col selection:bg-ochre selection:text-cream">
    <header class="bg-sand border-b border-parchment">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-baseline gap-3">
                <a href="{{ route('discover') }}" class="text-lg font-bold tracking-tight text-ink">
                    De Klinic Restaurant &amp; Pub
                </a>
                <span class="text-xs uppercase tracking-wider text-umber border-l border-parchment pl-3">
                    Accra · Diaspora Visit Flow
                </span>
            </div>

            <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wide">
                <a href="{{ route('discover') }}"
                   class="px-3 py-2 border {{ request()->routeIs('discover') ? 'bg-ink text-cream border-ink' : 'bg-cream text-ink border-parchment hover:border-ink' }}">
                    01–02 Discover &amp; Save
                </a>
                <a href="{{ route('itinerary.show', 'accra-dec-demo-01') }}"
                   class="px-3 py-2 border {{ request()->routeIs('itinerary.show') ? 'bg-ink text-cream border-ink' : 'bg-cream text-ink border-parchment hover:border-ink' }}">
                    02–03 Itinerary Pass
                </a>
                <a href="{{ route('staff.visits') }}"
                   class="px-3 py-2 border {{ request()->routeIs('staff.visits') ? 'bg-ink text-cream border-ink' : 'bg-cream text-ink border-parchment hover:border-ink' }}">
                    03 Staff Confirm
                </a>
                <a href="{{ route('referral.show', 'accra-dec-demo-01') }}"
                   class="px-3 py-2 border {{ request()->routeIs('referral.show') ? 'bg-ink text-cream border-ink' : 'bg-cream text-ink border-parchment hover:border-ink' }}">
                    04 Refer &amp; Return
                </a>
            </nav>
        </div>

        {{-- Intuitive 4-Step Flow Strip --}}
        <div class="border-t border-parchment bg-cream/70">
            <div class="max-w-7xl mx-auto px-6 py-2.5 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <div class="flex items-center gap-2 {{ request()->routeIs('discover') ? 'text-ink font-bold' : 'text-umber' }}">
                    <span class="font-mono text-ochreDark font-bold">01</span>
                    <span>Discover abroad</span>
                </div>
                <div class="flex items-center gap-2 {{ request()->routeIs('discover', 'itinerary.show') ? 'text-ink font-bold' : 'text-umber' }}">
                    <span class="font-mono text-ochreDark font-bold">02</span>
                    <span>Save trip plan before flying</span>
                </div>
                <div class="flex items-center gap-2 {{ request()->routeIs('staff.visits', 'itinerary.show') ? 'text-ink font-bold' : 'text-umber' }}">
                    <span class="font-mono text-ochreDark font-bold">03</span>
                    <span>Confirm visit in Accra</span>
                </div>
                <div class="flex items-center gap-2 {{ request()->routeIs('referral.show') ? 'text-ink font-bold' : 'text-umber' }}">
                    <span class="font-mono text-ochreDark font-bold">04</span>
                    <span>Refer circle &amp; return</span>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
        @if (session('status'))
            <div class="mb-6 bg-forestTint border-l-4 border-forest text-forest px-4 py-3 text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-700 text-red-950 px-4 py-3 text-sm">
                <p class="font-semibold mb-1">Please review the form fields:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-sand border-t border-parchment text-xs text-umber">
        <div class="max-w-7xl mx-auto px-6 py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <span>Single-journey prototype: overseas discovery -> saved itinerary -> confirmed Accra visit -> referral.</span>
            <span>Menu, pricing, delivery &amp; event items shown as sample/unconfirmed placeholders.</span>
        </div>
    </footer>
</body>
</html>