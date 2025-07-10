<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connessione Wallet - FlorenceEGI Founders</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/founder-certificate-wallet.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 via-amber-50 to-emerald-50">

    {{-- Header con branding FlorenceEGI --}}
    <header class="border-b border-amber-200 bg-white shadow-sm">
        <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-amber-600">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-slate-800" style="font-family: 'Playfair Display', serif;">
                            FlorenceEGI Founders
                        </h1>
                        <p class="text-sm font-medium text-slate-600">
                            Sistema Gestione Certificati Padre Fondatore
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">

        {{-- Error Message --}}
        @if (session('error'))
            <div class="mb-6 rounded-lg border-l-4 border-red-400 bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Wallet Connection Card --}}
        <div class="overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-xl">

            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6 text-center">
                <div class="mx-auto max-w-md">
                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white bg-opacity-20">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="mb-2 text-2xl font-bold text-white" style="font-family: 'Playfair Display', serif;"
                        id="page-title">
                        Accesso Richiesto
                    </h2>
                    <p class="text-lg text-blue-100" id="page-subtitle">
                        Connetti il wallet Treasury per accedere alla dashboard Founders
                    </p>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="px-8 py-8">

                {{-- Wallet Status --}}
                <div id="wallet-status" class="mb-6 rounded-lg border-2 border-slate-200 bg-slate-50 p-4 text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <div class="h-3 w-3 rounded-full bg-slate-400"></div>
                        <span class="font-medium text-slate-600">Wallet non connesso</span>
                    </div>
                </div>

                {{-- Wallet Info (Hidden initially) --}}
                <div id="wallet-info" class="mb-6 hidden rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                    <div class="text-center">
                        <div class="mb-2 flex items-center justify-center space-x-2">
                            <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                            <span class="font-medium text-emerald-700">Wallet Treasury Connesso</span>
                        </div>
                        <p class="font-mono text-sm text-emerald-600" id="wallet-address"></p>
                        <p class="mt-1 text-xs text-emerald-500">Network: TestNet (416002)</p>
                    </div>
                </div>

                {{-- Connection Button --}}
                <div class="text-center">
                    <button id="connect-pera-wallet"
                        class="transform rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-8 py-4 text-lg font-bold text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl">
                        🔗 Connetti Pera Wallet
                    </button>

                    {{-- Loading State --}}
                    <div id="wallet-connecting" class="mt-4 hidden">
                        <div class="flex items-center justify-center space-x-2 text-blue-600">
                            <svg class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="font-medium">Connessione in corso...</span>
                        </div>
                    </div>

                    {{-- Success State --}}
                    <div id="wallet-success" class="mt-4 hidden">
                        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                            <div class="mb-3 flex items-center justify-center space-x-2 text-emerald-700">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                <span class="font-medium">Connessione completata!</span>
                            </div>
                            <button id="access-dashboard"
                                class="w-full rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white transition-colors hover:bg-emerald-700">
                                🚀 Accedi alla Dashboard
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Info Section --}}
                <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <h3 class="mb-2 font-semibold text-amber-800">ℹ️ Informazioni Accesso</h3>
                    <ul class="space-y-1 text-sm text-amber-700">
                        <li>• Solo il wallet Treasury autorizzato può accedere</li>
                        <li>• La connessione avviene tramite PeraWallet</li>
                        <li>• Network richiesto: Algorand TestNet</li>
                        <li>• La sessione rimane attiva fino alla disconnessione</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    {{-- Error Modal --}}
    <div id="wallet-error-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                    Errore Connessione
                </h3>
                <button id="close-error-modal" class="text-white transition-colors hover:text-red-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <p id="wallet-error-message" class="mb-4 text-slate-700"></p>
                <div class="flex justify-end space-x-3">
                    <button id="close-error-btn"
                        class="rounded-lg bg-slate-600 px-4 py-2 font-medium text-white transition-colors hover:bg-slate-700">
                        Chiudi
                    </button>
                    <button id="retry-connection"
                        class="rounded-lg bg-red-600 px-4 py-2 font-medium text-white transition-colors hover:bg-red-700">
                        Riprova
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Treasury address and session status for JavaScript --}}
    <script>
        window.TREASURY_ADDRESS = '{{ config('founders.algorand.treasury_address') }}';
        window.WALLET_SESSION_ACTIVE = {{ session('wallet_address') ? 'true' : 'false' }};
        window.WALLET_SESSION_ADDRESS = '{{ session('wallet_address', '') }}';
    </script>

    {{-- Page-specific JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusDiv = document.getElementById('wallet-status');
            const infoDiv = document.getElementById('wallet-info');
            const addressSpan = document.getElementById('wallet-address');
            const connectBtn = document.getElementById('connect-pera-wallet');
            const connectingDiv = document.getElementById('wallet-connecting');
            const successDiv = document.getElementById('wallet-success');
            const accessBtn = document.getElementById('access-dashboard');
            const errorModal = document.getElementById('wallet-error-modal');
            const errorMessage = document.getElementById('wallet-error-message');
            const closeErrorModal = document.getElementById('close-error-modal');
            const closeErrorBtn = document.getElementById('close-error-btn');
            const retryBtn = document.getElementById('retry-connection');
            const pageTitle = document.getElementById('page-title');
            const pageSubtitle = document.getElementById('page-subtitle');

            // Update UI based on wallet state
            function updateUI(connected, address = null) {
                if (connected && address) {
                    statusDiv.classList.add('hidden');
                    infoDiv.classList.remove('hidden');
                    addressSpan.textContent = address.slice(0, 8) + '...' + address.slice(-8);
                    connectBtn.classList.add('hidden');
                    connectingDiv.classList.add('hidden');
                    successDiv.classList.remove('hidden');

                    // Update page title and subtitle
                    pageTitle.textContent = 'Wallet Connesso';
                    pageSubtitle.textContent = 'Accedi alla dashboard Founders o riconnetti il wallet';
                } else {
                    statusDiv.classList.remove('hidden');
                    infoDiv.classList.add('hidden');
                    connectBtn.classList.remove('hidden');
                    connectingDiv.classList.add('hidden');
                    successDiv.classList.add('hidden');

                    // Reset page title and subtitle
                    pageTitle.textContent = 'Accesso Richiesto';
                    pageSubtitle.textContent = 'Connetti il wallet Treasury per accedere alla dashboard Founders';
                }
            }

            // Show error modal
            function showError(message) {
                errorMessage.textContent = message;
                errorModal.classList.remove('hidden');
                errorModal.classList.add('flex');
            }

            // Hide error modal
            function hideError() {
                errorModal.classList.remove('flex');
                errorModal.classList.add('hidden');
            }

            // Handle wallet connection
            async function handleConnection() {
                if (!window.FoundersWallet) {
                    showError('Sistema wallet non disponibile. Ricarica la pagina.');
                    return;
                }

                connectBtn.classList.add('hidden');
                connectingDiv.classList.remove('hidden');

                try {
                    await window.FoundersWallet.connect();

                    if (window.FoundersWallet.isConnected()) {
                        const address = window.FoundersWallet.getAddress();
                        updateUI(true, address);
                    }
                } catch (error) {
                    console.error('Connection error:', error);
                    showError(error.message || 'Errore durante la connessione del wallet');
                    connectBtn.classList.remove('hidden');
                    connectingDiv.classList.add('hidden');
                }
            }

            // Event listeners
            connectBtn.addEventListener('click', handleConnection);
            retryBtn.addEventListener('click', () => {
                hideError();
                handleConnection();
            });
            closeErrorModal.addEventListener('click', hideError);
            closeErrorBtn.addEventListener('click', hideError);

            // Access dashboard button
            accessBtn.addEventListener('click', function() {
                // Force HTTPS with port 8443 for PeraWallet compatibility
                let dashboardUrl = '{{ route('founders.dashboard') }}';
                @if (config('app.env') !== 'production')
                    dashboardUrl = dashboardUrl.replace('http://', 'https://');
                    dashboardUrl = dashboardUrl.replace(':9000', ':8443');
                    if (!dashboardUrl.includes(':8443')) {
                        dashboardUrl = dashboardUrl.replace('https://localhost', 'https://localhost:8443');
                    }
                @endif
                window.location.href = dashboardUrl;
            });



            // Check initial wallet status
            function checkInitialStatus() {
                // Wait for FoundersWallet to be available
                if (window.FoundersWallet) {
                    if (window.FoundersWallet.isConnected()) {
                        const address = window.FoundersWallet.getAddress();
                        updateUI(true, address);
                    } else {
                        updateUI(false);
                    }
                } else {
                    // Check if Laravel session is active
                    if (window.WALLET_SESSION_ACTIVE && window.WALLET_SESSION_ADDRESS) {
                        updateUI(true, window.WALLET_SESSION_ADDRESS);
                    } else {
                        updateUI(false);
                    }
                }
            }

            // Wait a bit for the FoundersWallet to initialize, then check status
            setTimeout(checkInitialStatus, 1000);
        });
    </script>
</body>

</html>
