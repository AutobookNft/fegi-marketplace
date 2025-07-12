<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Menu Founders - FlorenceEGI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap');
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="container px-4 py-8 mx-auto">
        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="mb-2 text-4xl font-bold text-slate-800" style="font-family: 'Playfair Display', serif;">
                Test Menu Sistema Founders
            </h1>
            <p class="text-slate-600">Test dell'implementazione del menu wallet-based per il sistema Padri Fondatori</p>
        </div>

        {{-- Wallet Status --}}
        <div class="p-6 mb-8 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Status Wallet</h2>
            @if (session('wallet_address'))
                <div class="flex items-center p-4 space-x-3 rounded-lg bg-emerald-50">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <div>
                        <p class="font-medium text-emerald-800">Wallet Connesso</p>
                        <p class="font-mono text-sm text-emerald-600">{{ session('wallet_address') }}</p>
                        @if (session('wallet_address') === config('founders.algorand.treasury_address'))
                            <p class="mt-1 text-xs text-emerald-500">✓ Treasury Wallet Autorizzato</p>
                        @else
                            <p class="mt-1 text-xs text-red-500">✗ Wallet Non Autorizzato</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="flex items-center p-4 space-x-3 rounded-lg bg-red-50">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <div>
                        <p class="font-medium text-red-800">Wallet Non Connesso</p>
                        <p class="text-sm text-red-600">Connetti il Treasury wallet per accedere al menu</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Menu Test --}}
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Menu Sistema Founders</h2>

            @php
                use App\Services\Menu\ContextMenus;
                use App\Services\Menu\MenuConditionEvaluator;

                $menus = ContextMenus::getMenusForContext('founders');
                $evaluator = new MenuConditionEvaluator();
            @endphp

            @if (count($menus) > 0)
                @foreach ($menus as $menuGroup)
                    <div class="mb-6">
                        <h3 class="flex items-center mb-3 text-lg font-medium text-slate-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            {{ $menuGroup->name }}
                        </h3>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($menuGroup->items as $item)
                                @php
                                    $shouldDisplay = $evaluator->shouldDisplay($item);
                                @endphp

                                <div
                                    class="{{ $shouldDisplay ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }} rounded-lg border p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4
                                            class="{{ $shouldDisplay ? 'text-emerald-800' : 'text-red-800' }} font-medium">
                                            {{ $item->name }}
                                        </h4>
                                        @if ($shouldDisplay)
                                            <span
                                                class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-700">Visibile</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs text-red-700 bg-red-100 rounded">Nascosto</span>
                                        @endif
                                    </div>

                                    <div
                                        class="{{ $shouldDisplay ? 'text-emerald-600' : 'text-red-600' }} space-y-1 text-sm">
                                        <p><strong>Route:</strong> {{ $item->route }}</p>
                                        <p><strong>Richiede Wallet:</strong> {{ $item->requiresWallet ? 'Sì' : 'No' }}
                                        </p>
                                        <p><strong>Icona:</strong> {{ $item->icon ?? 'N/A' }}</p>
                                    </div>

                                    @if ($shouldDisplay)
                                        <a href="{{ $item->getHref() }}"
                                            class="inline-block px-3 py-1 mt-3 text-sm text-white transition-colors rounded bg-emerald-600 hover:bg-emerald-700">
                                            Vai alla pagina
                                        </a>
                                    @else
                                        <button disabled
                                            class="inline-block px-3 py-1 mt-3 text-sm text-white bg-gray-400 rounded cursor-not-allowed">
                                            Accesso negato
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="py-8 text-center text-slate-500">
                    <p>Nessun menu configurato per il contesto 'founders'</p>
                </div>
            @endif
        </div>

        {{-- Wallet Connection Simulator --}}
        <div class="p-6 mt-8 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Simulatore Connessione Wallet</h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <form action="{{ route('founders.wallet.connect') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700">
                            Connetti Treasury Wallet
                        </label>
                        <input type="hidden" name="wallet_address"
                            value="{{ config('founders.algorand.treasury_address') }}">
                        <button type="submit"
                            class="w-full px-4 py-2 text-white transition-colors rounded bg-emerald-600 hover:bg-emerald-700">
                            Connetti Treasury (Autorizzato)
                        </button>
                    </div>
                </form>

                <form action="{{ route('founders.wallet.connect') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700">
                            Connetti Wallet Non Autorizzato
                        </label>
                        <input type="hidden" name="wallet_address" value="FAKE_WALLET_ADDRESS_NOT_TREASURY">
                        <button type="submit"
                            class="w-full px-4 py-2 text-white transition-colors bg-red-600 rounded hover:bg-red-700">
                            Connetti Wallet Falso (Non Autorizzato)
                        </button>
                    </div>
                </form>
            </div>

            @if (session('wallet_address'))
                <div class="pt-4 mt-4 border-t">
                    <form action="{{ route('founders.wallet.disconnect') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-white transition-colors bg-gray-600 rounded hover:bg-gray-700">
                            Disconnetti Wallet
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Configuration Info --}}
        <div class="p-6 mt-8 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Informazioni Configurazione</h2>
            <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                <div>
                    <p class="font-medium text-slate-700">Treasury Address:</p>
                    <p class="font-mono break-all text-slate-600">{{ config('founders.algorand.treasury_address') }}
                    </p>
                </div>
                <div>
                    <p class="font-medium text-slate-700">Wallet Connesso:</p>
                    <p class="font-mono break-all text-slate-600">{{ session('wallet_address') ?? 'Nessuno' }}</p>
                </div>
            </div>
        </div>

        {{-- PDF Test Section --}}
        <div class="p-6 mt-8 border rounded-lg shadow-lg border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50">
            <h2 class="flex items-center mb-4 text-xl font-semibold text-amber-800">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                🏛️ Test PDF Rinascimentale
            </h2>

            <div class="mb-4">
                <p class="mb-2 text-amber-700">
                    <strong>Design B - Pergamena Rinascimentale</strong> con elementi eleganti fiorentini:
                </p>
                <ul class="ml-4 space-y-1 text-sm text-amber-600">
                    <li>• ⚜️ Simboli rinascimentali e ornamenti ❦</li>
                    <li>• 🎨 Tipografia Cinzel, Playfair Display, EB Garamond</li>
                    <li>• 💎 Sigillo digitale dorato con certificazione</li>
                    <li>• 🏆 Sezione benefici dinamica con emoji</li>
                    <li>• 🔐 QR Code e hash blockchain per verifica</li>
                </ul>
            </div>

            <div class="flex space-x-4">
                @if (session('wallet_address') === config('founders.algorand.treasury_address'))
                    <a href="{{ route('founders.test.pdf') }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 text-white transition-colors rounded-lg bg-amber-600 hover:bg-amber-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        📜 Visualizza PDF Pergamena
                    </a>
                @else
                    <div
                        class="inline-flex items-center px-4 py-2 text-white bg-gray-400 rounded-lg cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        Connetti Treasury per testare
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
