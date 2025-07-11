<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Founders System</title>

    <!-- Polyfill per global (fix Vite + PeraWallet) -->
    <script>
        if (typeof global === "undefined") {
            window.global = window;
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Page specific scripts -->
    @stack('head')

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="drawer lg:drawer-open">
        <!-- Drawer Toggle (Mobile) -->
        <input id="main-drawer" type="checkbox" class="drawer-toggle" />

        <!-- Page Content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-100 shadow-sm">
                <div class="flex-none lg:hidden">
                    <label for="main-drawer" class="btn btn-square btn-ghost">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>

                <div class="flex-1">
                    <!-- Page Header -->
                    @if (isset($header))
                        <div class="px-4">
                            {{ $header }}
                        </div>
                    @endif
                </div>

                <div class="flex-none">
                    <!-- Wallet Status -->
                    <div class="flex items-center space-x-3 px-4">
                        <div class="flex items-center space-x-2 rounded-lg bg-emerald-50 px-3 py-2">
                            <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                            <span class="text-sm font-medium text-emerald-700">Treasury Connesso</span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500">Wallet Treasury</p>
                            <p class="font-mono text-sm text-slate-700">
                                {{ substr(session('wallet_address'), 0, 8) }}...{{ substr(session('wallet_address'), -8) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>

        </div>

        <!-- Sidebar -->
        <livewire:sidebar />
        @stack('modals')

    </div>

    {{-- Footer --}}
    <footer class="gdpr-header mt-auto border-t border-gray-200/50" role="contentinfo">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="gdpr-subtitle flex items-center justify-between text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('profile.all_rights_reserved') }}</p>
                <div class="flex space-x-4">
                    {{-- <a href="{{ route('gdpr.privacy-policy') }}" class="gdpr-link">{{ __('profile.privacy_policy') }}</a>
                    <a href="{{ route('gdpr.terms') }}" class="gdpr-link">{{ __('profile.terms_of_service') }}</a> --}}
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
