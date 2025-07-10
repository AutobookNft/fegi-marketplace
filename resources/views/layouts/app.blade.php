<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="florenceegi"> {{-- APPLICATO TEMA DAISYUI --}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{--
            @oracode-dimension technical
            @value-flow Core layout infrastructure - distributes UI value across all platform pages
            @community-impact Primary navigation and accessibility foundation for all users
            @transparency-level Full structural transparency - semantic HTML5 landmarks
            @sustainability-factor High reuse efficiency - single layout serves entire platform
            @narrative-coherence Embodies FlorenceEGI's commitment to accessible, dignified user experience
        --}}

        {{-- Oracode 3.0: SEO & Metadata (Pillar #1 - Explicitly Intentional) --}}
        <title>{{ isset($pageTitle) ? $pageTitle . ' - ' . config('app.name') : config('app.name', 'FlorenceEGI') }}</title>
        <meta name="description" content="{{ $pageDescription ?? 'FlorenceEGI - Digital Renaissance Platform for Ecological, Goods & Inventive Projects. Transparent marketplace bridging Web2 to Web3 with dignity and sustainability.' }}">
        <meta name="robots" content="{{ $robotsContent ?? 'index, follow' }}">
        <meta name="author" content="FlorenceEGI">

        {{-- Open Graph / Social Media Meta Tags --}}
        <meta property="og:type" content="{{ $ogType ?? 'website' }}">
        <meta property="og:title" content="{{ isset($pageTitle) ? $pageTitle . ' - ' . config('app.name') : config('app.name') }}">
        <meta property="og:description" content="{{ $pageDescription ?? 'Digital Renaissance Platform for sustainable innovation' }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        @if(isset($ogImage))
            <meta property="og:image" content="{{ $ogImage }}">
        @endif

        {{-- Twitter Card Meta Tags --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ isset($pageTitle) ? $pageTitle . ' - ' . config('app.name') : config('app.name') }}">
        <meta name="twitter:description" content="{{ $pageDescription ?? 'Digital Renaissance Platform for sustainable innovation' }}">

        {{-- Fonts (FlorenceEGI Brand Guidelines) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
        {{-- RIMOSSO Figtree da fonts.bunny.net --}}

        {{-- Icon Libraries (Mantenute) --}}
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" media="print" onload="this.media='all'">

        {{-- Flag Icons for Internationalization (Mantenute) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css" media="print" onload="this.media='all'">

        {{-- Application Assets --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Oracode 3.0: Allow child views to inject custom styles (Pillar #5 - Predisposed to Variation) --}}
        @stack('styles')

        {{-- Livewire Styles --}}
        @livewireStyles

        {{-- Schema.org Structured Data (Pillar #2 - Semantically Coherent) --}}
        @if(isset($schemaData))
            <script type="application/ld+json">
                {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
            </script>
        @else
            <script type="application/ld+json">
                {
                    "@context": "https://schema.org",
                    "@type": "WebApplication",
                    "name": "{{ config('app.name') }}",
                    "description": "Digital Renaissance Platform for Ecological, Goods & Inventive Projects",
                    "url": "{{ config('app.url') }}",
                    "applicationCategory": "BusinessApplication",
                    "operatingSystem": "Web Browser",
                    "offers": {
                        "@type": "Offer",
                        "price": "0",
                        "priceCurrency": "EUR"
                    }
                }
            </script>
        @endif
    </head>

    {{--
        Oracode 3.0: Semantic HTML Structure (Pillar #2 - Semantically Coherent)
        Using proper HTML5 landmarks for accessibility (Pillar #4 - Interpretable by Assistive Tech)
    --}}
    {{-- MODIFICHE AL BODY: font-body per Source Sans Pro, text-base-content e bg-base-100 dal tema DaisyUI --}}
    <body class="antialiased font-body text-base-content bg-base-100" itemscope itemtype="https://schema.org/WebPage">
        {{-- Skip to main content for accessibility (WCAG 2.1) --}}
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary focus:text-primary-content focus:rounded-md">
            {{ __('Skip to main content') }}
        </a>

        {{--
            Main Application Container
            Oracode 3.0: Maintains existing drawer structure for backward compatibility
        --}}
        <div class="drawer lg:drawer-open" role="application" aria-label="FlorenceEGI Application Interface">
            {{-- Drawer toggle checkbox (DaisyUI pattern) --}}
            <input id="main-drawer" type="checkbox" class="drawer-toggle" aria-label="Toggle navigation menu">

            {{-- Main Content Area --}}
            <div class="flex flex-col min-h-screen drawer-content">
                {{-- Navigation Header --}}
                {{-- MODIFICA: Aggiunto stile base per header, i componenti Livewire interni dovranno adattarsi --}}
                <header role="banner" aria-label="Main navigation" class="border-b shadow-sm bg-base-200 border-base-300">
                    <livewire:navigation-menu />
                </header>

                {{-- Page Header Section (Optional) --}}
                @if (isset($header))
                    {{-- MODIFICA: Stile base per la sezione header, il contenuto $header userà font-display e colori chiari --}}
                    <section class="shadow bg-base-200" role="complementary" aria-label="Page header">
                        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content"> {{-- Usare text-base-content per coerenza --}}
                            {{ $header }}
                        </div>
                    </section>
                @endif

                {{--
                    Main Content Area
                    Oracode 3.0: Semantic main landmark with proper ARIA labeling
                --}}
                <main id="main-content" class="flex-1 p-4 lg:p-8" role="main" aria-label="Main content" tabindex="-1">
                    {{--
                        Content Slot - All page content goes here
                        Oracode 3.0: Preserves existing slot mechanism for backward compatibility
                    --}}
                    {{ $slot }}

                </main>
            </div>

            {{--
                Navigation Sidebar
                Oracode 3.0: Semantic navigation landmark (DaisyUI drawer structure preserved)
                MODIFICA: La sidebar di DaisyUI usa bg-base-100, potremmo volerla bg-base-200/300 per stacco.
                          Questa personalizzazione va fatta nel componente livewire:sidebar o con CSS specifici.
            --}}
            {{-- <livewire:sidebar />  Esempio: <aside class="drawer-side"><label for="main-drawer" class="drawer-overlay"></label><ul class="min-h-full p-4 menu w-80 bg-base-200 text-base-content">...</aside> --}}

            {{-- Modal Stack (for Livewire/Alpine modals) --}}
            @stack('modals')

            {{-- Development Debug Info (Local Environment Only) --}}
            @if(app()->environment('local'))
                {{-- MODIFICA: Stile per tema scuro --}}
                <aside role="complementary" aria-label="Debug information" class="fixed bottom-0 right-0 z-50 max-w-xs p-2 m-2 overflow-auto text-xs text-gray-400 bg-gray-900 border border-gray-700 rounded shadow-xl debug-info max-h-32">
                    {!! $debugInfo ?? '' !!}
                </aside>
            @endif
        </div>

         {{-- Footer --}}
        <footer class="mt-auto border-t gdpr-header border-gray-200/50" role="contentinfo">
            <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex items-center justify-between text-sm gdpr-subtitle">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('profile.all_rights_reserved') }}</p>
                    <div class="flex space-x-4">
                        {{-- <a href="{{ route('gdpr.privacy-policy') }}" class="gdpr-link">{{ __('profile.privacy_policy') }}</a>
                        <a href="{{ route('gdpr.terms') }}" class="gdpr-link">{{ __('profile.terms_of_service') }}</a> --}}
                    </div>
                </div>
            </div>
        </footer>

        {{--
            Application Configuration
            Oracode 3.0: Contextually autonomous - provides necessary config to client-side
        --}}
        <script>
            // Global app configuration for client-side scripts
            window.appConfig = @json(config('app')); // Mantenuto com'era

            // Accessibility enhancement: Focus management (Mantenuto com'era)
            document.addEventListener('DOMContentLoaded', function() {
                const skipLink = document.querySelector('a[href="#main-content"]');
                if (skipLink) {
                    skipLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        const mainContent = document.getElementById('main-content');
                        if (mainContent) {
                            mainContent.focus();
                        }
                    });
                }
            });
        </script>

         <!-- OS1 Modals Section -->
        {{-- @include('components.create-collection-modal') --}}

        <!-- OS1 User Collection Data for Dashboard Context -->
        @auth
        <script type="application/json" id="user-collection-data">
        {
            "total_collections": {{ auth()->user()->collections()->count() }},
            "max_allowed": {{ config('egi.max_collections_per_user', 10) }},
            "context": "dashboard"
        }
        </script>
        @endauth

        {{-- Oracode 3.0: Allow child views to inject custom scripts --}}
        @stack('scripts')

        {{-- Livewire Scripts --}}
        @livewireScripts
    </body>
</html>
