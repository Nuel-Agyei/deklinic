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
                        parchment: '#E6D8C5',
                        bark: '#3A2A1D',
                        umber: '#6B533E',
                        ochre: '#C27D28',
                        ochreDark: '#9E6219',
                        sage: '#2F5D46',
                        sageLight: '#E5EFE9',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-cream text-bark antialiased min-h-screen flex flex-col">
    <header class="bg-sand border-b border-parchment">
        <div class="max-w-5xl mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('discover') }}" class="text-lg font-bold tracking-tight text-bark">
                    De Klinic Restaurant &amp; Pub
                </a>
                <p class="text-xs text-umber">
                    Accra, Ghana — Diaspora Discovery to Confirmed Visit Demo
                </p>
            </div>

            <nav class="flex flex-wrap items-center gap-2 text-sm font-medium">
                <a href="{{ route('discover') }}"
                   class="px-3 py-1.5 rounded border {{ request()->routeIs('discover') ? 'bg-bark text-cream border-bark' : 'bg-cream text-bark border-parchment hover:border-umber' }}">
                    01–02. Discover &amp; Save
                </a>
                <a href="{{ route('itinerary.show', 'accra-dec-demo-01') }}"
                   class="px-3 py-1.5 rounded border {{ request()->routeIs('itinerary.show') ? 'bg-bark text-cream border-bark' : 'bg-cream text-bark border-parchment hover:border-umber' }}">
                    02–03. Sample Itinerary
                </a>
                <a href="{{ route('staff.visits') }}"
                   class="px-3 py-1.5 rounded border {{ request()->routeIs('staff.visits') ? 'bg-bark text-cream border-bark' : 'bg-cream text-bark border-parchment hover:border-umber' }}">
                    03. Staff Confirm
                </a>
                <a href="{{ route('referral.show', 'accra-dec-demo-01') }}"
                   class="px-3 py-1.5 rounded border {{ request()->routeIs('referral.show') ? 'bg-bark text-cream border-bark' : 'bg-cream text-bark border-parchment hover:border-umber' }}">
                    04. Refer &amp; Return
                </a>
            </nav>
        </div>
    </header>

    <main class="flex-1 max-w-5xl w-full mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-6 bg-sageLight border border-sage text-sage px-4 py-3 rounded text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-300 text-red-900 px-4 py-3 rounded text-sm">
                <p class="font-semibold mb-1">Please check the form inputs:</p>
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
        <div class="max-w-5xl mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-2">
            <span>Prototype scope: single traceable journey from overseas discovery to confirmed Accra visit &amp; referral.</span>
            <span>Sample/unconfirmed menu, pricing, delivery &amp; event items clearly marked.</span>
        </div>
    </footer>
</body>
</html>