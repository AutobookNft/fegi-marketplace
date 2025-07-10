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
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="mb-2 text-4xl font-bold text-slate-800" style="font-family: 'Playfair Display', serif;">
                Test Menu Sistema Founders
            </h1>
            <p class="text-slate-600">Test dell'implementazione del menu wallet-based per il sistema Padri Fondatori</p>
        </div>

        {{-- Wallet Status --}}
        <div class="mb-8 rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Status Wallet</h2>
            @if (session('wallet_address'))
                <div class="flex items-center space-x-3 rounded-lg bg-emerald-50 p-4">
                    <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
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
                <div class="flex items-center space-x-3 rounded-lg bg-red-50 p-4">
                    <div class="h-3 w-3 rounded-full bg-red-500"></div>
                    <div>
                        <p class="font-medium text-red-800">Wallet Non Connesso</p>
                        <p class="text-sm text-red-600">Connetti il Treasury wallet per accedere al menu</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Menu Test --}}
        <div class="rounded-lg bg-white p-6 shadow-lg">
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
                        <h3 class="mb-3 flex items-center text-lg font-medium text-slate-700">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <div class="mb-2 flex items-center justify-between">
                                        <h4
                                            class="{{ $shouldDisplay ? 'text-emerald-800' : 'text-red-800' }} font-medium">
                                            {{ $item->name }}
                                        </h4>
                                        @if ($shouldDisplay)
                                            <span
                                                class="rounded bg-emerald-100 px-2 py-1 text-xs text-emerald-700">Visibile</span>
                                        @else
                                            <span
                                                class="rounded bg-red-100 px-2 py-1 text-xs text-red-700">Nascosto</span>
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
                                            class="mt-3 inline-block rounded bg-emerald-600 px-3 py-1 text-sm text-white transition-colors hover:bg-emerald-700">
                                            Vai alla pagina
                                        </a>
                                    @else
                                        <button disabled
                                            class="mt-3 inline-block cursor-not-allowed rounded bg-gray-400 px-3 py-1 text-sm text-white">
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
        <div class="mt-8 rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Simulatore Connessione Wallet</h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <form action="{{ route('founders.wallet.connect') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Connetti Treasury Wallet
                        </label>
                        <input type="hidden" name="wallet_address"
                            value="{{ config('founders.algorand.treasury_address') }}">
                        <button type="submit"
                            class="w-full rounded bg-emerald-600 px-4 py-2 text-white transition-colors hover:bg-emerald-700">
                            Connetti Treasury (Autorizzato)
                        </button>
                    </div>
                </form>

                <form action="{{ route('founders.wallet.connect') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Connetti Wallet Non Autorizzato
                        </label>
                        <input type="hidden" name="wallet_address" value="FAKE_WALLET_ADDRESS_NOT_TREASURY">
                        <button type="submit"
                            class="w-full rounded bg-red-600 px-4 py-2 text-white transition-colors hover:bg-red-700">
                            Connetti Wallet Falso (Non Autorizzato)
                        </button>
                    </div>
                </form>
            </div>

            @if (session('wallet_address'))
                <div class="mt-4 border-t pt-4">
                    <form action="{{ route('founders.wallet.disconnect') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="rounded bg-gray-600 px-4 py-2 text-white transition-colors hover:bg-gray-700">
                            Disconnetti Wallet
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Configuration Info --}}
        <div class="mt-8 rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Informazioni Configurazione</h2>
            <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                <div>
                    <p class="font-medium text-slate-700">Treasury Address:</p>
                    <p class="break-all font-mono text-slate-600">{{ config('founders.algorand.treasury_address') }}
                    </p>
                </div>
                <div>
                    <p class="font-medium text-slate-700">Wallet Connesso:</p>
                    <p class="break-all font-mono text-slate-600">{{ session('wallet_address') ?? 'Nessuno' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
