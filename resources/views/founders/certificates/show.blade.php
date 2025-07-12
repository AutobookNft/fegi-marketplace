<x-founders-layout>
    @push('head')
        @vite(['resources/js/founder-certificate-wallet.js'])
    @endpush
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Certificato #{{ str_pad($certificate->index, 3, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Certificate Header --}}
            <div class="mb-6 bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-white bg-opacity-20">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h1 class="text-3xl font-bold text-white"
                                    style="font-family: 'Playfair Display', serif;">
                                    Certificato Padre Fondatore
                                </h1>
                                <p class="text-lg text-emerald-100">
                                    #{{ str_pad($certificate->index, 3, '0', STR_PAD_LEFT) }} -
                                    {{ $certificate->collection->name ?? 'Collection non specificata' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span
                                class="{{ $certificate->getStatusBadgeColor() }} rounded-full px-4 py-2 text-sm font-medium">
                                {{ $certificate->getStatusLabel() }}
                            </span>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('founders.certificates.edit', $certificate) }}"
                                    class="inline-flex items-center rounded-md bg-white bg-opacity-20 px-4 py-2 text-white transition-all duration-150 hover:bg-opacity-30">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Modifica
                                </a>
                                <a href="{{ route('founders.certificates.index') }}"
                                    class="inline-flex items-center rounded-md bg-white bg-opacity-20 px-4 py-2 text-white transition-all duration-150 hover:bg-opacity-30">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Torna alla Lista
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Main Certificate Details --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-800"
                                style="font-family: 'Playfair Display', serif;">
                                Dettagli Certificato
                            </h2>
                        </div>

                        <div class="space-y-6 p-6">
                            {{-- Certificate Info --}}
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Titolo
                                        Certificato</label>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $certificate->certificate_title ?: 'Titolo non specificato' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Collection</label>
                                    <p class="text-lg text-gray-900">
                                        @if ($certificate->collection)
                                            <a href="{{ route('founders.collections.show', $certificate->collection) }}"
                                                class="font-medium text-emerald-600 hover:text-emerald-500">
                                                {{ $certificate->collection->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-500">Non specificata</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Prezzo Base</label>
                                    <p class="text-lg text-gray-900">
                                        <span
                                            class="font-semibold">{{ number_format($certificate->base_price, 2) }}</span>
                                        <span class="ml-1 text-gray-500">{{ $certificate->currency }}</span>
                                    </p>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Stato</label>
                                    <span
                                        class="{{ $certificate->getStatusBadgeColor() }} inline-flex items-center rounded-full px-3 py-1 text-sm font-medium">
                                        {{ $certificate->getStatusLabel() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Investor Information --}}
                            @if ($certificate->investor_name)
                                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-6">
                                    <h3 class="mb-4 text-lg font-semibold text-emerald-800">
                                        Informazioni Investitore
                                    </h3>
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-emerald-700">Nome
                                                Completo</label>
                                            <p class="font-medium text-emerald-900">{{ $certificate->investor_name }}
                                            </p>
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-emerald-700">Email</label>
                                            <p class="text-emerald-900">{{ $certificate->investor_email }}</p>
                                        </div>
                                        @if ($certificate->investor_phone)
                                            <div>
                                                <label
                                                    class="mb-1 block text-sm font-medium text-emerald-700">Telefono</label>
                                                <p class="text-emerald-900">{{ $certificate->investor_phone }}</p>
                                            </div>
                                        @endif
                                        @if ($certificate->investor_wallet)
                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-emerald-700">Wallet
                                                    Algorand</label>
                                                <p class="break-all font-mono text-sm text-emerald-900">
                                                    {{ $certificate->investor_wallet }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    @if ($certificate->investor_address)
                                        <div class="mt-4">
                                            <label
                                                class="mb-1 block text-sm font-medium text-emerald-700">Indirizzo</label>
                                            <p class="text-emerald-900">{{ $certificate->investor_address }}</p>
                                        </div>
                                    @endif
                                    @if ($certificate->issued_at)
                                        <div class="mt-4">
                                            <label class="mb-1 block text-sm font-medium text-emerald-700">Data
                                                Emissione</label>
                                            <p class="text-emerald-900">
                                                {{ $certificate->issued_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Blockchain Information --}}
                            @if ($certificate->asa_id || $certificate->tx_id)
                                <div class="rounded-lg border border-purple-200 bg-purple-50 p-6">
                                    <h3 class="mb-4 text-lg font-semibold text-purple-800">
                                        Informazioni Blockchain
                                    </h3>
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        @if ($certificate->asa_id)
                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-purple-700">ASA
                                                    ID</label>
                                                <p class="font-mono text-purple-900">{{ $certificate->asa_id }}</p>
                                            </div>
                                        @endif
                                        @if ($certificate->tx_id)
                                            <div>
                                                <label
                                                    class="mb-1 block text-sm font-medium text-purple-700">Transaction
                                                    ID</label>
                                                <p class="break-all font-mono text-sm text-purple-900">
                                                    {{ $certificate->tx_id }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Metadata --}}
                            @if ($certificate->metadata)
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Metadata NFT</label>
                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <pre class="whitespace-pre-wrap text-sm text-gray-900">{{ json_encode($certificate->metadata, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar Actions --}}
                <div class="space-y-6">

                    {{-- Quick Actions --}}
                    <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-800">Azioni Rapide</h3>
                        </div>
                        <div class="space-y-4 p-6">

                            @if ($certificate->status === 'draft')
                                <form action="{{ route('founders.certificates.mark-ready', $certificate) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center justify-center rounded-md bg-blue-600 px-4 py-3 text-white transition-colors hover:bg-blue-700">
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Marca come Pronto
                                    </button>
                                </form>
                            @endif

                            @if ($certificate->status === 'ready' && !$certificate->investor_name)
                                <button type="button"
                                    onclick="document.getElementById('assign-investor-modal').classList.remove('hidden')"
                                    class="flex w-full items-center justify-center rounded-md bg-emerald-600 px-4 py-3 text-white transition-colors hover:bg-emerald-700">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Assegna Investitore
                                </button>
                            @endif

                            @if ($certificate->status === 'issued')
                                <button type="button" onclick="openMintModal()"
                                    class="flex w-full items-center justify-center rounded-md bg-gradient-to-r from-purple-600 to-indigo-600 px-4 py-3 text-white shadow-lg transition-all duration-200 hover:from-purple-700 hover:to-indigo-700">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    🚀 Minta su Blockchain
                                </button>
                            @endif

                            {{-- Certificate Actions --}}
                            <div class="space-y-2">
                                <button type="button" onclick="generatePublicLink()"
                                    class="flex w-full items-center justify-center rounded-md bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-3 text-white transition-colors hover:from-green-700 hover:to-emerald-700">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                    🌐 Genera Link Pubblico
                                </button>

                                <a href="{{ route('founders.certificates.generate-pdf', $certificate) }}"
                                    class="flex w-full items-center justify-center rounded-md bg-amber-600 px-4 py-3 text-white transition-colors hover:bg-amber-700">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    📜 Scarica PDF Pergamena
                                </a>

                                <a href="{{ route('founders.certificates.stream-pdf', $certificate) }}"
                                    target="_blank"
                                    class="flex w-full items-center justify-center rounded-md bg-purple-600 px-4 py-3 text-white transition-colors hover:bg-purple-700">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    ⚜️ Visualizza PDF Rinascimentale
                                </a>
                            </div>

                            @if (!$certificate->asa_id && $certificate->status !== 'minted')
                                <form action="{{ route('founders.certificates.destroy', $certificate) }}"
                                    method="POST"
                                    onsubmit="return confirm('Sei sicuro di voler eliminare questo certificato?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex w-full items-center justify-center rounded-md bg-red-600 px-4 py-3 text-white transition-colors hover:bg-red-700">
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Elimina Certificato
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Certificate Stats --}}
                    <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-800">Statistiche</h3>
                        </div>
                        <div class="space-y-4 p-6">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Creato</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ $certificate->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Aggiornato</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ $certificate->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if ($certificate->issued_at)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Emesso</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $certificate->issued_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mint Certificate Modal --}}
    <div id="mint-modal" class="fixed inset-0 z-50 hidden h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50">
        <div class="relative top-20 mx-auto w-96 rounded-md border bg-white p-5 shadow-lg">
            <div class="mt-3">
                <div class="mb-4 flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Minting Certificato su Blockchain</h3>
                        <p class="text-sm text-gray-500">{{ $certificate->investor_name }} -
                            #{{ str_pad($certificate->index, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="mb-4 text-sm text-gray-600">
                        L'investitore <strong>{{ $certificate->investor_name }}</strong> ha un wallet Algorand per
                        ricevere il token NFT?
                    </p>

                    <div class="space-y-3">
                        <button type="button" onclick="showWalletInput()"
                            class="flex w-full items-center justify-center rounded-md bg-emerald-600 px-4 py-3 text-white transition-colors hover:bg-emerald-700">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            ✅ SÌ - Ha un wallet Algorand
                        </button>

                        <button type="button" onclick="mintToTreasury()"
                            class="flex w-full items-center justify-center rounded-md bg-amber-600 px-4 py-3 text-white transition-colors hover:bg-amber-700">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            💰 NO - Invia al Treasury Wallet
                        </button>
                    </div>
                </div>

                <div id="wallet-input-section" class="mb-6 hidden">
                    <div class="mb-4 text-center">
                        <h4 class="mb-2 text-sm font-medium text-gray-700">Scegli Metodo Connessione Wallet</h4>

                        <div class="space-y-3">
                            <!-- Opzione 1: Connessione PeraWallet -->
                            <button type="button" onclick="connectInvestorPeraWallet()"
                                class="flex w-full items-center justify-center rounded-md bg-indigo-600 px-4 py-3 text-white transition-colors hover:bg-indigo-700">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                📱 Connetti con PeraWallet
                            </button>

                            <!-- Opzione 2: Inserimento Manuale -->
                            <button type="button" onclick="showManualWalletInput()"
                                class="flex w-full items-center justify-center rounded-md bg-emerald-600 px-4 py-3 text-white transition-colors hover:bg-emerald-700">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                ✏️ Inserisci Manualmente
                            </button>
                        </div>
                    </div>

                    <!-- Stato Connessione PeraWallet -->
                    <div id="perawallet-connection-status" class="mb-4 hidden">
                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-center">
                            <div class="mx-auto mb-2 h-8 w-8 animate-spin rounded-full border-b-2 border-indigo-600">
                            </div>
                            <h4 class="text-sm font-medium text-blue-900">Connessione PeraWallet</h4>
                            <p class="mt-1 text-xs text-blue-700">Apri PeraWallet sul telefono e scansiona il QR code
                            </p>
                        </div>
                    </div>

                    <!-- Wallet Connesso -->
                    <div id="perawallet-connected" class="mb-4 hidden">
                        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-center">
                            <div class="mb-2 text-green-600">
                                <svg class="mx-auto h-8 w-8" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-green-900">PeraWallet Connesso</h4>
                            <p class="mt-1 text-xs text-green-700" id="connected-wallet-address">Indirizzo: ...</p>
                        </div>
                    </div>

                    <!-- Input Manuale -->
                    <div id="manual-wallet-input" class="hidden">
                        <label for="investor_wallet" class="mb-2 block text-sm font-medium text-gray-700">
                            Indirizzo Wallet Algorand dell'Investitore
                        </label>
                        <input type="text" id="investor_wallet"
                            placeholder="Inserisci l'indirizzo wallet Algorand (58 caratteri)"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            maxlength="58">
                        <p class="mt-1 text-xs text-gray-500">L'indirizzo Algorand deve contenere esattamente 58
                            caratteri Base32 (A-Z, 2-7). Il server verificherà anche il checksum.</p>
                    </div>

                    <div class="mt-4 space-y-2">
                        <button type="button" onclick="mintToInvestorWallet()" id="mint-to-wallet-btn"
                            class="w-full rounded-md bg-purple-600 px-4 py-2 text-white hover:bg-purple-700">
                            🚀 Minta al Wallet Investitore
                        </button>
                        <button type="button" onclick="hideWalletSection()"
                            class="w-full rounded-md bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200">
                            ← Torna alle opzioni
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeMintModal()"
                        class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Annulla
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Modal --}}
    <div id="loading-modal" class="fixed inset-0 z-50 hidden h-full w-full overflow-y-auto bg-gray-600 bg-opacity-75">
        <div class="relative top-1/2 mx-auto w-96 -translate-y-1/2 transform rounded-md border bg-white p-8 shadow-lg">
            <div class="text-center">
                <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-b-2 border-purple-600"></div>
                <h3 class="mb-2 text-lg font-medium text-gray-900">Minting in corso...</h3>
                <p class="text-sm text-gray-600">
                    Stiamo creando il token NFT su blockchain Algorand.<br>
                    <strong>Non chiudere questa finestra.</strong>
                </p>
            </div>
        </div>
    </div>

    {{-- Assign Investor Modal --}}
    <div id="assign-investor-modal"
        class="fixed inset-0 z-50 hidden h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50">
        <div class="relative top-20 mx-auto w-96 rounded-md border bg-white p-5 shadow-lg">
            <div class="mt-3">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Assegna Investitore</h3>
                <form action="{{ route('founders.certificates.assign-investor', $certificate) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="investor_name" class="block text-sm font-medium text-gray-700">Nome
                                Completo</label>
                            <input type="text" name="investor_name" id="investor_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label for="investor_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="investor_email" id="investor_email" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label for="investor_phone"
                                class="block text-sm font-medium text-gray-700">Telefono</label>
                            <input type="text" name="investor_phone" id="investor_phone"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label for="investor_wallet" class="block text-sm font-medium text-gray-700">Wallet
                                Algorand</label>
                            <input type="text" name="investor_wallet" id="investor_wallet"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label for="investor_address"
                                class="block text-sm font-medium text-gray-700">Indirizzo</label>
                            <textarea name="investor_address" id="investor_address" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <button type="button"
                            onclick="document.getElementById('assign-investor-modal').classList.add('hidden')"
                            class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                            Annulla
                        </button>
                        <button type="submit"
                            class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                            Assegna Certificato
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');
    </style>

    {{-- JavaScript for Mint Modal --}}
    <script>
        // Certificate data
        const certificateData = {
            id: {{ $certificate->id }},
            index: {{ $certificate->index }},
            investor_name: @json($certificate->investor_name),
            investor_email: @json($certificate->investor_email),
            investor_phone: @json($certificate->investor_phone ?? ''),
            investor_address: @json($certificate->investor_address ?? '')
        };

        // PeraWallet instance per investitori
        let investorPeraWallet = null;
        let connectedInvestorWallet = null;

        // Modal functions
        function openMintModal() {
            document.getElementById('mint-modal').classList.remove('hidden');
        }

        function closeMintModal() {
            document.getElementById('mint-modal').classList.add('hidden');
            document.getElementById('wallet-input-section').classList.add('hidden');
            document.getElementById('investor_wallet').value = '';
            // Reset wallet states
            resetWalletStates();
        }

        function showWalletInput() {
            document.getElementById('wallet-input-section').classList.remove('hidden');
        }

        function hideWalletSection() {
            document.getElementById('wallet-input-section').classList.add('hidden');
            document.getElementById('investor_wallet').value = '';
            // Reset wallet states
            resetWalletStates();
        }

        function showManualWalletInput() {
            document.getElementById('manual-wallet-input').classList.remove('hidden');
            document.getElementById('perawallet-connection-status').classList.add('hidden');
            document.getElementById('perawallet-connected').classList.add('hidden');
        }

        function resetWalletStates() {
            document.getElementById('manual-wallet-input').classList.add('hidden');
            document.getElementById('perawallet-connection-status').classList.add('hidden');
            document.getElementById('perawallet-connected').classList.add('hidden');
            document.getElementById('investor_wallet').value = '';
            connectedInvestorWallet = null;
            if (investorPeraWallet) {
                investorPeraWallet.disconnect().catch(console.error);
            }
        }

        // PeraWallet Functions per Investitori
        function initializeInvestorPeraWallet() {
            if (investorPeraWallet) return true;

            try {
                // Usa la STESSA libreria del sistema Treasury (già importata)
                // Controlla se PeraWalletConnect è disponibile globalmente
                if (typeof PeraWalletConnect === 'undefined') {
                    console.error('PeraWalletConnect non disponibile. Verifica che sia caricato.');
                    return false;
                }

                // Crea istanza con la STESSA configurazione del sistema Treasury
                investorPeraWallet = new PeraWalletConnect({
                    chainId: 4160, // All networks per massima compatibilità
                    shouldShowSignTxnToast: false, // Disabilita toast per evitare interferenze
                    compactMode: true // UI compatta per modal (diverso da Treasury)
                });

                console.log('PeraWallet per investitori inizializzato');
                return true;
            } catch (error) {
                console.error('Errore inizializzazione PeraWallet investitori:', error);
                return false;
            }
        }

        async function connectInvestorPeraWallet() {
            try {
                // Mostra stato connessione
                document.getElementById('perawallet-connection-status').classList.remove('hidden');
                document.getElementById('manual-wallet-input').classList.add('hidden');
                document.getElementById('perawallet-connected').classList.add('hidden');

                // Inizializza PeraWallet se necessario
                if (!initializeInvestorPeraWallet()) {
                    throw new Error('Impossibile inizializzare PeraWallet');
                }

                // Disconnetti sessioni precedenti
                try {
                    await investorPeraWallet.disconnect();
                } catch (e) {
                    console.log('Nessuna sessione precedente da disconnettere');
                }

                console.log('Avvio connessione PeraWallet investitore...');

                // Avvia connessione (stesso meccanismo del Treasury)
                const accounts = await investorPeraWallet.connect();

                console.log('Risultato connect() investitore:', accounts);

                if (accounts && accounts.length > 0) {
                    console.log('Connessione investitore riuscita immediatamente:', accounts);
                    handleInvestorWalletConnected(accounts[0]);
                } else {
                    // Nessun account immediatamente - avvia polling avanzato (come Treasury)
                    console.log('Nessun account ricevuto, avvio polling avanzato...');
                    const pollingResult = await startInvestorConnectionPolling();

                    if (pollingResult) {
                        console.log('Connessione investitore riuscita tramite polling:', pollingResult);
                        handleInvestorWalletConnected(pollingResult);
                    } else {
                        throw new Error('Timeout durante l\'attesa della connessione');
                    }
                }

            } catch (error) {
                console.error('Errore connessione PeraWallet investitore:', error);

                // Mostra errore e fallback a input manuale
                document.getElementById('perawallet-connection-status').classList.add('hidden');
                document.getElementById('manual-wallet-input').classList.remove('hidden');

                alert('Errore connessione PeraWallet: ' + error.message +
                    '\nUsa l\'inserimento manuale come alternativa.');
            }
        }

        function handleInvestorWalletConnected(walletAddress) {
            connectedInvestorWallet = walletAddress;

            // Aggiorna UI
            document.getElementById('perawallet-connection-status').classList.add('hidden');
            document.getElementById('perawallet-connected').classList.remove('hidden');
            document.getElementById('connected-wallet-address').textContent =
                `Indirizzo: ${walletAddress.substring(0, 6)}...${walletAddress.substring(52)}`;

            console.log('PeraWallet investitore connesso:', walletAddress);
        }

        // Polling avanzato per connessione investitore (replica del sistema Treasury)
        async function startInvestorConnectionPolling() {
            return new Promise((resolve, reject) => {
                let attempts = 0;
                const MAX_POLLING_ATTEMPTS = 30;
                const POLLING_INTERVAL = 2000;
                let pollingInterval;

                const checkConnection = async () => {
                    attempts++;

                    try {
                        console.log(`Polling investitore attempt ${attempts}/${MAX_POLLING_ATTEMPTS}`);

                        // Controlla stato PeraWallet
                        const isConnected = investorPeraWallet.isConnected;
                        console.log('Stato PeraWallet investitore:', {
                            isConnected
                        });

                        // Tenta reconnectSession per ottenere gli account
                        const accounts = await investorPeraWallet.reconnectSession();
                        console.log('Risultato reconnectSession investitore:', accounts);

                        // Verifica se abbiamo account validi
                        if (accounts && accounts.length > 0) {
                            console.log('Account investitore trovati tramite polling:', accounts);
                            clearInterval(pollingInterval);
                            resolve(accounts[0]);
                            return;
                        }

                        // Se abbiamo raggiunto il limite, fermati
                        if (attempts >= MAX_POLLING_ATTEMPTS) {
                            console.log('Raggiunto limite tentativi polling investitore');
                            clearInterval(pollingInterval);
                            reject(new Error('Timeout durante l\'attesa della connessione'));
                        }

                    } catch (error) {
                        console.error('Errore durante il polling investitore:', error);
                        // Continua il polling anche in caso di errore
                    }
                };

                // Avvia il polling
                pollingInterval = setInterval(checkConnection, POLLING_INTERVAL);

                // Primo check immediato
                checkConnection();
            });
        }

        function showLoading() {
            document.getElementById('loading-modal').classList.remove('hidden');
            document.getElementById('mint-modal').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-modal').classList.add('hidden');
        }

        // Mint to Treasury Wallet
        async function mintToTreasury() {
            if (!confirm(
                    `Confermi il mint del certificato #${certificateData.index} per ${certificateData.investor_name} al Treasury Wallet?`
                )) {
                return;
            }

            showLoading();

            try {
                const response = await fetch(`/api/founders/${certificateData.id}/mint`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        // Nessun wallet = rimane al treasury
                    })
                });

                const result = await response.json();

                if (result.success) {
                    hideLoading();
                    showSuccessMessage(
                        `🎉 Certificato mintato con successo!\n\nASA ID: ${result.data.asa_id}\nTransaction ID: ${result.data.transaction_id}\n\nToken inviato al Treasury Wallet.`
                    );
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    throw new Error(result.message || 'Errore durante il mint');
                }
            } catch (error) {
                hideLoading();
                closeMintModal();
                showErrorMessage(`❌ Errore durante il mint:\n${error.message}`);
            }
        }

        // Mint to Investor Wallet
        async function mintToInvestorWallet() {
            // Determina quale wallet usare: connesso via PeraWallet o inserito manualmente
            let walletAddress;
            let walletSource;

            if (connectedInvestorWallet) {
                walletAddress = connectedInvestorWallet;
                walletSource = 'PeraWallet';
            } else {
                walletAddress = document.getElementById('investor_wallet').value.trim();
                walletSource = 'Inserimento manuale';
            }

            if (!walletAddress) {
                alert('Inserisci l\'indirizzo wallet dell\'investitore o connetti PeraWallet');
                return;
            }

            if (!isValidAlgorandAddress(walletAddress)) {
                alert(
                    'Indirizzo wallet non valido. Deve essere di 58 caratteri e contenere solo caratteri Base32 (A-Z, 2-7).'
                    );
                return;
            }

            if (!confirm(
                    `Confermi il mint del certificato #${certificateData.index} per ${certificateData.investor_name} al wallet:\n\n${walletAddress}\n\nMetodo: ${walletSource}`
                )) {
                return;
            }

            showLoading();

            try {
                const response = await fetch(`/api/founders/${certificateData.id}/mint`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        investor_wallet: walletAddress
                    })
                });

                const result = await response.json();

                if (result.success) {
                    hideLoading();
                    showSuccessMessage(
                        `🎉 Certificato mintato con successo!\n\nASA ID: ${result.data.asa_id}\nTransaction ID: ${result.data.transaction_id}\n\nToken inviato al wallet dell'investitore:\n${walletAddress}\n\nMetodo: ${walletSource}`
                    );
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    throw new Error(result.message || 'Errore durante il mint');
                }
            } catch (error) {
                hideLoading();
                closeMintModal();
                showErrorMessage(`❌ Errore durante il mint:\n${error.message}`);
            }
        }

        // Utility functions
        function isValidAlgorandAddress(address) {
            // Algorand address validation: 58 characters, Base32 encoding (A-Z, 2-7)
            // NOTA: Questa validazione controlla solo formato, il server farà anche checksum
            console.log('Validating address:', address);
            console.log('Address length:', address.length);

            // Controllo preliminare di formato
            if (!address || typeof address !== 'string') {
                console.log('Invalid input: not a string');
                return false;
            }

            if (address.length !== 58) {
                console.log('Invalid length:', address.length);
                return false;
            }

            const regex = /^[A-Z2-7]{58}$/;
            const isValid = regex.test(address);

            console.log('Format validation result:', isValid);
            if (!isValid) {
                console.log('Failed format validation - check characters');
                // Mostra caratteri non validi
                const invalidChars = address.split('').filter(char => !/[A-Z2-7]/.test(char));
                if (invalidChars.length > 0) {
                    console.log('Invalid characters found:', invalidChars);
                }
            }

            return isValid;
        }

        function showSuccessMessage(message) {
            alert(message);
        }

        function showErrorMessage(message) {
            alert(message);
        }

        // Add CSRF token to page head if not present
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }

        // Generate Public Link
        function generatePublicLink() {
            const certificateId = {{ $certificate->id }};
            const investorName = '{{ $certificate->investor_name ?? 'Certificato' }}';

            // Generate the public URL (we'll make an AJAX call to get the proper hash)
            fetch(`/founders/certificates/${certificateId}/public-url`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.url) {
                        // Show modal with link
                        showPublicLinkModal(data.url, investorName);
                    } else {
                        alert('Errore nella generazione del link');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Errore nella generazione del link');
                });
        }

        function showPublicLinkModal(url, investorName) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-gray-600 bg-opacity-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900">Link Pubblico Generato</h3>
                            <p class="text-sm text-gray-500">Certificato di ${investorName}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL del Certificato</label>
                        <div class="flex">
                            <input type="text" id="publicUrl" value="${url}" readonly
                                class="flex-1 rounded-l-md border-gray-300 bg-gray-50 px-3 py-2 text-sm">
                            <button type="button" onclick="copyPublicUrl()"
                                class="px-4 py-2 bg-green-600 text-white rounded-r-md hover:bg-green-700 text-sm">
                                Copia
                            </button>
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Questo link è sicuro e può essere condiviso pubblicamente.
                                    Mostra il certificato in una pagina web elegante,
                                    ottimizzata per stampa e condivisione.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="window.open('${url}', '_blank')"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            🔗 Visualizza
                        </button>
                        <button type="button" onclick="closePublicLinkModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Chiudi
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Close on background click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closePublicLinkModal();
                }
            });
        }

        function copyPublicUrl() {
            const urlInput = document.getElementById('publicUrl');
            urlInput.select();
            urlInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(urlInput.value).then(() => {
                // Show success feedback
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copiato!';
                button.className = button.className.replace('bg-green-600', 'bg-emerald-600');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.className = button.className.replace('bg-emerald-600', 'bg-green-600');
                }, 2000);
            });
        }

        function closePublicLinkModal() {
            const modal = document.querySelector('.fixed.inset-0.z-50');
            if (modal) {
                modal.remove();
            }
        }
    </script>
</x-founders-layout>
