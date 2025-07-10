<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard Founders') }}
        </h2>
    </x-slot>
    <div class="p-6">
        {{-- Dashboard Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800" style="font-family: 'Playfair Display', serif;">
                        Dashboard Founders
                    </h1>
                    <p class="mt-1 text-slate-600">
                        Panoramica del sistema certificati Padre Fondatore
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center px-3 py-2 space-x-2 rounded-lg bg-emerald-50">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <span class="text-sm font-medium text-emerald-700">Treasury Connesso</span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">Wallet Treasury</p>
                        <p class="font-mono text-sm text-slate-700">
                            {{ substr(session('wallet_address'), 0, 8) }}...{{ substr(session('wallet_address'), -8) }}
                        </p>
                    </div>

                    {{-- Pulsante Disconnessione Wallet --}}
                    <div class="flex flex-col space-y-2">
                        <form action="{{ route('founders.wallet.disconnect') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="flex items-center px-3 py-2 space-x-2 text-sm font-medium text-red-700 transition-colors bg-red-100 rounded-lg hover:bg-red-200 hover:text-red-800"
                                onclick="return confirm('Sei sicuro di voler disconnettere il wallet?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                <span>Disconnetti</span>
                            </button>
                        </form>

                        {{-- Pulsante Riconnetti (per test) --}}
                        <a href="{{ str_replace(['http://', ':9000'], ['https://', ':8443'], route('founders.wallet.connect')) }}"
                            class="flex items-center px-3 py-2 space-x-2 text-sm font-medium text-blue-700 transition-colors bg-blue-100 rounded-lg hover:bg-blue-200 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <span>Riconnetti</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Certificati Emessi --}}
            <div class="overflow-hidden bg-white border rounded-lg shadow border-slate-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 rounded-md bg-emerald-500">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 w-0 ml-5">
                            <dl>
                                <dt class="text-sm font-medium truncate text-slate-500">
                                    Certificati Emessi
                                </dt>
                                <dd class="text-lg font-medium text-slate-900">
                                    0 / 40
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-slate-50">
                    <div class="text-sm">
                        <a href="{{ route('founders.certificates.index') }}"
                            class="font-medium text-emerald-600 hover:text-emerald-500">
                            Gestisci certificati
                        </a>
                    </div>
                </div>
            </div>

            {{-- Token nel Treasury --}}
            <div class="overflow-hidden bg-white border rounded-lg shadow border-slate-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-500 rounded-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 w-0 ml-5">
                            <dl>
                                <dt class="text-sm font-medium truncate text-slate-500">
                                    Token Treasury
                                </dt>
                                <dd class="text-lg font-medium text-slate-900">
                                    0 ASA
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-slate-50">
                    <div class="text-sm">
                        <a href="{{ route('founders.treasury.index') }}"
                            class="font-medium text-blue-600 hover:text-blue-500">
                            Gestisci treasury
                        </a>
                    </div>
                </div>
            </div>

            {{-- Spedizioni Pending --}}
            <div class="overflow-hidden bg-white border rounded-lg shadow border-slate-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 rounded-md bg-amber-500">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 w-0 ml-5">
                            <dl>
                                <dt class="text-sm font-medium truncate text-slate-500">
                                    Spedizioni Pending
                                </dt>
                                <dd class="text-lg font-medium text-slate-900">
                                    0
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-slate-50">
                    <div class="text-sm">
                        <a href="{{ route('founders.shipping.index') }}"
                            class="font-medium text-amber-600 hover:text-amber-500">
                            Gestisci spedizioni
                        </a>
                    </div>
                </div>
            </div>

            {{-- Revenue Totale --}}
            <div class="overflow-hidden bg-white border rounded-lg shadow border-slate-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-purple-500 rounded-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 w-0 ml-5">
                            <dl>
                                <dt class="text-sm font-medium truncate text-slate-500">
                                    Revenue Totale
                                </dt>
                                <dd class="text-lg font-medium text-slate-900">
                                    €0
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-slate-50">
                    <div class="text-sm">
                        <span class="text-slate-500">Prezzo: €250/certificato</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mb-8">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Azioni Rapide</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                {{-- Emetti Certificato --}}
                <a href="{{ route('founders.certificates.create') }}"
                    class="relative p-6 overflow-hidden text-white transition-all duration-200 rounded-lg shadow-lg group bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 hover:shadow-xl">
                    <div class="relative z-10">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg bg-opacity-20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold">Emetti Certificato</h3>
                                <p class="text-sm text-emerald-100">Nuovo Padre Fondatore</p>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Gestisci Treasury --}}
                <a href="{{ route('founders.treasury.index') }}"
                    class="relative p-6 overflow-hidden text-white transition-all duration-200 rounded-lg shadow-lg group bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-xl">
                    <div class="relative z-10">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg bg-opacity-20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold">Treasury</h3>
                                <p class="text-sm text-blue-100">Gestisci token ASA</p>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Tracking Spedizioni --}}
                <a href="{{ route('founders.shipping.index') }}"
                    class="relative p-6 overflow-hidden text-white transition-all duration-200 rounded-lg shadow-lg group bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl">
                    <div class="relative z-10">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg bg-opacity-20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold">Spedizioni</h3>
                                <p class="text-sm text-amber-100">Tracking prismi</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white border rounded-lg shadow border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800">Attività Recente</h2>
            </div>
            <div class="p-6">
                <div class="py-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">Nessuna attività</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Inizia emettendo il primo certificato Padre Fondatore
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('founders.certificates.create') }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white rounded-md shadow-sm bg-emerald-600 hover:bg-emerald-500">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Emetti Certificato
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script per disconnessione completa wallet e controllo periodico --}}
    <script>
        // Aggiungi handler per disconnessione completa
        document.addEventListener('DOMContentLoaded', function() {
            // Trova il form di disconnessione
            const disconnectForm = document.querySelector('form[action*="wallet/disconnect"]');

            if (disconnectForm) {
                disconnectForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Conferma disconnessione
                    if (!confirm('Sei sicuro di voler disconnettere il wallet?')) {
                        return;
                    }

                    try {
                        // Disconnetti PeraWallet se disponibile
                        if (window.FoundersWallet && window.FoundersWallet.peraWallet) {
                            console.log('🔌 Disconnettendo PeraWallet...');
                            await window.FoundersWallet.peraWallet.disconnect();
                        }

                        // Pulisci session storage
                        sessionStorage.removeItem('connected_wallet');
                        localStorage.removeItem('walletconnect');

                        console.log('✅ Wallet disconnesso completamente');

                        // Invia il form per pulire la sessione Laravel
                        this.submit();

                    } catch (error) {
                        console.error('❌ Errore durante disconnessione:', error);
                        // Procedi comunque con la disconnessione Laravel
                        this.submit();
                    }
                });
            }

            // Controllo periodico dello stato del wallet
            async function checkWalletStatus() {
                try {
                    const response = await fetch('/api/wallet/status');
                    const sessionData = await response.json();

                    if (!sessionData.success || !sessionData.connected) {
                        // Sessione Laravel persa, reindirizza alla pagina di connessione
                        console.log('🔄 Sessione Laravel persa, reindirizzo alla connessione');
                        let connectUrl = '{{ route('founders.wallet.connect') }}';
                        @if (config('app.env') !== 'production')
                            connectUrl = connectUrl.replace('http://', 'https://');
                            connectUrl = connectUrl.replace(':9000', ':8443');
                            if (!connectUrl.includes(':8443')) {
                                connectUrl = connectUrl.replace('https://localhost', 'https://localhost:8443');
                            }
                        @endif
                        window.location.href = connectUrl;
                    }
                } catch (error) {
                    console.error('❌ Errore controllo stato wallet:', error);
                }
            }

            // Avvia controllo periodico ogni 5 secondi
            setInterval(checkWalletStatus, 5000);

            // Controllo iniziale
            checkWalletStatus();
        });
    </script>
</x-app-layout>
