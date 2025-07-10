{{--
    @Oracode Livewire Blade Template for Founder Certificate Form with Pera Wallet Integration
    🎯 Purpose: FlorenceEGI branded form with wallet connection for certificate issuance
    🧱 Core Logic: Wallet connection, real-time validation, dashboard access, brand styling
    🛡️ Security: Wallet-based authentication, treasury validation, CSRF protection

    @package resources/views/livewire
    @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
    @version 2.0.0 (FlorenceEGI - Founders System with Wallet Integration)
    @date 2025-07-09
    @purpose Wallet-protected certificate issuance form with FlorenceEGI brand styling
--}}

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-amber-50 to-emerald-50">
    {{-- Header Section with FlorenceEGI Branding --}}
    <header class="border-b border-amber-200 bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-amber-600">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800" style="font-family: 'Playfair Display', serif;">
                            FlorenceEGI
                        </h1>
                        <p class="text-sm font-medium text-slate-600">
                            Il Nuovo Rinascimento Ecologico Digitale
                        </p>
                    </div>
                </div>

                {{-- Wallet Connection Status --}}
                <div class="flex items-center space-x-4">
                    <div id="wallet-status" class="flex hidden items-center space-x-2">
                        <div class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></div>
                        <span class="text-sm font-medium text-slate-600">
                            Wallet Connesso
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></div>
                        <span class="text-sm font-medium text-slate-600">
                            {{ ucfirst($statistics['round_info']['network'] ?? 'TestNet') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            {{-- Main Form Section --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-xl">

                    {{-- Wallet Connection Section --}}
                    <div id="wallet-connection-section" class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6">
                        <div class="text-center">
                            <h2 class="mb-2 text-2xl font-bold text-white"
                                style="font-family: 'Playfair Display', serif;">
                                Connessione Wallet Richiesta
                            </h2>
                            <p class="mb-4 text-lg text-blue-100">
                                Connetti il tuo wallet Pera per accedere al sistema Padri Fondatori
                            </p>

                            {{-- Wallet Connection Button --}}
                            <button id="connect-pera-wallet"
                                class="transform rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-3 text-lg font-bold text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl">
                                🔗 Connetti Pera Wallet
                            </button>

                            {{-- Wallet Connection Status --}}
                            <div id="wallet-connecting" class="mt-4 hidden">
                                <div class="flex items-center justify-center space-x-2 text-blue-100">
                                    <svg class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span>Connessione in corso...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Main Form (Hidden until wallet connected) --}}
                    <div id="main-form-section" class="hidden">
                        {{-- Form Header --}}
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-8 py-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="mb-2 text-3xl font-bold text-white"
                                        style="font-family: 'Playfair Display', serif;">
                                        Certificato Padre Fondatore
                                    </h2>
                                    <p class="text-lg text-amber-100">
                                        Unisciti ai primi sostenitori del Nuovo Rinascimento
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-amber-100">Wallet connesso:</p>
                                    <p id="connected-wallet-address"
                                        class="rounded bg-amber-600 px-2 py-1 font-mono text-xs text-amber-50"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Success Message --}}
                        @if ($showSuccess)
                            <div class="m-6 rounded-r-lg border-l-4 border-emerald-400 bg-emerald-50 p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="mb-2 text-lg font-bold text-emerald-800">
                                            🎉 Certificato Emesso con Successo!
                                        </h3>
                                        <div class="space-y-2 text-emerald-700">
                                            <p><strong>Certificato
                                                    #{{ $successData['certificate_number'] ?? '' }}</strong></p>
                                            <p><strong>ASA ID:</strong> {{ $successData['asa_id'] ?? '' }}</p>
                                            <p><strong>Transaction ID:</strong>
                                                <code class="rounded bg-emerald-100 px-2 py-1 text-xs">
                                                    {{ Str::limit($successData['transaction_id'] ?? '', 20) }}
                                                </code>
                                            </p>
                                            <p>
                                                <strong>Token:</strong>
                                                @if ($successData['token_transferred'] ?? false)
                                                    <span class="text-emerald-600">✅ Trasferito al tuo wallet</span>
                                                @else
                                                    <span class="text-amber-600">🏦 Conservato nel Treasury</span>
                                                @endif
                                            </p>
                                            @if ($successData['blockchain_explorer'] ?? false)
                                                <p>
                                                    <a href="{{ $successData['blockchain_explorer'] }}" target="_blank"
                                                        class="inline-flex items-center font-medium text-blue-600 hover:text-blue-800">
                                                        🔗 Visualizza su Blockchain Explorer
                                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4" />
                                                        </svg>
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="mt-4">
                                            <button wire:click="resetForm"
                                                class="rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white transition-colors hover:bg-emerald-700">
                                                Emetti Nuovo Certificato
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Certificate Form --}}
                            <form wire:submit="submit" class="space-y-6 p-8">

                                {{-- Error Message --}}
                                @if ($errorMessage)
                                    <div class="rounded-r-lg border-l-4 border-red-400 bg-red-50 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="font-medium text-red-700">{{ $errorMessage }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Form Fields --}}
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                                    {{-- Nome Investitore --}}
                                    <div class="md:col-span-2">
                                        <label for="investorName"
                                            class="mb-2 block text-sm font-semibold text-slate-700">
                                            Nome Completo *
                                        </label>
                                        <input type="text" id="investorName" wire:model.live="investorName"
                                            class="@error('investorName') border-red-300 @enderror w-full rounded-lg border-2 border-slate-200 px-4 py-3 transition-all focus:border-amber-500 focus:ring focus:ring-amber-200"
                                            placeholder="Es: Mario Rossi" maxlength="200">
                                        @error('investorName')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label for="investorEmail"
                                            class="mb-2 block text-sm font-semibold text-slate-700">
                                            Email *
                                        </label>
                                        <input type="email" id="investorEmail" wire:model.live="investorEmail"
                                            class="@error('investorEmail') border-red-300 @enderror w-full rounded-lg border-2 border-slate-200 px-4 py-3 transition-all focus:border-amber-500 focus:ring focus:ring-amber-200"
                                            placeholder="mario.rossi@example.com" maxlength="200">
                                        @error('investorEmail')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Telefono --}}
                                    <div>
                                        <label for="investorPhone"
                                            class="mb-2 block text-sm font-semibold text-slate-700">
                                            Telefono
                                        </label>
                                        <input type="tel" id="investorPhone" wire:model.live="investorPhone"
                                            class="@error('investorPhone') border-red-300 @enderror w-full rounded-lg border-2 border-slate-200 px-4 py-3 transition-all focus:border-amber-500 focus:ring focus:ring-amber-200"
                                            placeholder="+39 123 456 7890" maxlength="50">
                                        @error('investorPhone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Indirizzo Spedizione --}}
                                    <div class="md:col-span-2">
                                        <label for="investorAddress"
                                            class="mb-2 block text-sm font-semibold text-slate-700">
                                            Indirizzo Spedizione Prisma
                                        </label>
                                        <textarea id="investorAddress" wire:model.live="investorAddress" rows="3"
                                            class="@error('investorAddress') border-red-300 @enderror w-full rounded-lg border-2 border-slate-200 px-4 py-3 transition-all focus:border-amber-500 focus:ring focus:ring-amber-200"
                                            placeholder="Via Roma 123, 00100 Roma (RM), Italia" maxlength="1000"></textarea>
                                        @error('investorAddress')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Tracking Number --}}
                                    <div class="md:col-span-2">
                                        <label for="trackingNumber"
                                            class="mb-2 block text-sm font-semibold text-slate-700">
                                            Tracking Number (opzionale)
                                        </label>
                                        <input type="text" id="trackingNumber" wire:model.live="trackingNumber"
                                            class="@error('trackingNumber') border-red-300 @enderror w-full rounded-lg border-2 border-slate-200 px-4 py-3 transition-all focus:border-amber-500 focus:ring focus:ring-amber-200"
                                            placeholder="Es: 1234567890" maxlength="100">
                                        <p class="mt-1 text-xs text-slate-500">
                                            Inserisci il tracking number quando spedisci il prisma all'investitore
                                        </p>
                                        @error('trackingNumber')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Wallet Algorand (auto-filled) --}}
                                    <div class="md:col-span-2">
                                        <label for="investorWallet"
                                            class="mb-2 block text-sm font-semibold text-slate-700">
                                            Wallet Algorand Investitore (opzionale)
                                        </label>
                                        <input type="text" id="investorWallet" wire:model.live="investorWallet"
                                            class="@error('investorWallet') border-red-300 @enderror w-full rounded-lg border-2 border-slate-200 px-4 py-3 font-mono text-sm transition-all focus:border-amber-500 focus:ring focus:ring-amber-200"
                                            placeholder="Es: ABCDEF1234567890...">
                                        <p class="mt-1 text-xs text-slate-500">
                                            Se fornito, il token ASA sarà trasferito immediatamente al wallet
                                            dell'investitore.
                                            Se non fornito, il token resterà nel Treasury per trasferimento successivo.
                                        </p>
                                        @error('investorWallet')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>

                                {{-- GDPR Consent --}}
                                <div class="rounded-lg border-2 border-slate-200 bg-slate-50 p-6">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 pt-1">
                                            <input type="checkbox" id="gdprConsent" wire:model.live="gdprConsent"
                                                class="@error('gdprConsent') border-red-300 @enderror h-4 w-4 rounded border-2 border-slate-300 text-amber-600 focus:ring-amber-500">
                                        </div>
                                        <div class="flex-1">
                                            <label for="gdprConsent" class="cursor-pointer text-sm text-slate-700">
                                                Accetto il trattamento dei dati personali come indicato nell'
                                                <button type="button" wire:click="toggleGdprModal"
                                                    class="font-medium text-amber-600 underline hover:text-amber-800">
                                                    informativa privacy
                                                </button>
                                                per l'emissione del certificato e la spedizione del prisma
                                                commemorativo. *
                                            </label>
                                            @error('gdprConsent')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="flex justify-center pt-6">
                                    <button type="submit" @disabled($isSubmitting)
                                        class="transform rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-8 py-4 text-lg font-bold text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl disabled:from-slate-400 disabled:to-slate-500 disabled:hover:transform-none">
                                        @if ($isSubmitting)
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 animate-spin text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <span>Emissione in corso...</span>
                                            </div>
                                        @else
                                            🎯 Emetti Certificato Padre Fondatore
                                        @endif
                                    </button>
                                </div>

                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Statistics Sidebar --}}
            <div class="space-y-6">

                {{-- Round Information --}}
                <div class="overflow-hidden rounded-xl border border-emerald-100 bg-white shadow-lg">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                            Round Padri Fondatori
                        </h3>
                    </div>
                    <div class="space-y-4 p-6">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Prezzo per Certificato</p>
                            <p class="text-2xl font-bold text-emerald-600">
                                €{{ number_format($statistics['round_info']['price'] ?? 250, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600">Network Blockchain</p>
                            <p class="text-lg font-semibold text-slate-800">
                                Algorand {{ $statistics['round_info']['network'] ?? 'TestNet' }}
                            </p>
                        </div>
                        <div class="border-t pt-4">
                            <p class="text-xs text-slate-500">
                                Ogni certificato include:
                            </p>
                            <ul class="mt-2 space-y-1 text-sm text-slate-600">
                                <li>• Token ASA unico su Algorand</li>
                                <li>• Certificato PDF firmato digitalmente</li>
                                <li>• Prisma fisico commemorativo</li>
                                <li>• Posizione da Padre Fondatore</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Progress Statistics --}}
                @if (isset($statistics['certificates']))
                    <div class="overflow-hidden rounded-xl border border-blue-100 bg-white shadow-lg">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <h3 class="text-xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                                Progresso Emissione
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="mb-4 text-center">
                                <div class="text-3xl font-bold text-blue-600">
                                    {{ $statistics['certificates']['total_issued'] ?? 0 }}
                                    <span class="text-lg text-slate-500">/
                                        {{ $statistics['certificates']['total_available'] ?? 40 }}</span>
                                </div>
                                <p class="text-sm text-slate-600">Certificati Emessi</p>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="mb-4 h-3 w-full rounded-full bg-slate-200">
                                <div class="h-3 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500"
                                    style="width: {{ $statistics['certificates']['completion_percentage'] ?? 0 }}%">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <div class="text-xl font-bold text-emerald-600">
                                        {{ $statistics['certificates']['remaining'] ?? 40 }}
                                    </div>
                                    <div class="text-xs text-slate-500">Rimanenti</div>
                                </div>
                                <div>
                                    <div class="text-xl font-bold text-blue-600">
                                        {{ $statistics['certificates']['completion_percentage'] ?? 0 }}%
                                    </div>
                                    <div class="text-xs text-slate-500">Completato</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- GDPR Modal --}}
    @if ($showGdprConsent)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="max-h-[90vh] max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                <div class="flex items-center justify-between bg-gradient-to-r from-slate-600 to-slate-700 px-6 py-4">
                    <h3 class="text-xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                        Informativa Privacy
                    </h3>
                    <button wire:click="toggleGdprModal" class="text-white transition-colors hover:text-slate-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-4 p-6 text-sm text-slate-700">
                    <div>
                        <h4 class="mb-2 font-semibold text-slate-800">Finalità del Trattamento</h4>
                        <p>I tuoi dati personali vengono trattati per:</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            <li>Emissione del certificato digitale Padre Fondatore</li>
                            <li>Invio del certificato via email</li>
                            <li>Spedizione del prisma commemorativo fisico</li>
                            <li>Gestione del token ASA su blockchain Algorand</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-2 font-semibold text-slate-800">Base Giuridica</h4>
                        <p>Art. 6(1)(b) GDPR - Esecuzione di un contratto di cui l'interessato è parte.</p>
                    </div>
                    <div>
                        <h4 class="mb-2 font-semibold text-slate-800">Conservazione</h4>
                        <p>I dati saranno conservati per massimo 5 anni dalla data di emissione del certificato.</p>
                    </div>
                    <div>
                        <h4 class="mb-2 font-semibold text-slate-800">I Tuoi Diritti</h4>
                        <p>Hai diritto di accesso, rettifica, cancellazione e opposizione al trattamento dei tuoi dati.
                        </p>
                    </div>
                    <div class="rounded-r border-l-4 border-amber-400 bg-amber-50 p-3">
                        <p class="text-amber-800">
                            <strong>Titolare del Trattamento:</strong> FlorenceEGI<br>
                            <strong>Contatto Privacy:</strong> privacy@florenceegi.it
                        </p>
                    </div>
                </div>
                <div class="flex justify-end bg-slate-50 px-6 py-4">
                    <button wire:click="toggleGdprModal"
                        class="rounded-lg bg-slate-600 px-4 py-2 font-medium text-white transition-colors hover:bg-slate-700">
                        Ho Capito
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Wallet Connection Error Modal --}}
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
                <div class="flex justify-end">
                    <button id="close-error-btn"
                        class="rounded-lg bg-red-600 px-4 py-2 font-medium text-white transition-colors hover:bg-red-700">
                        Chiudi
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Treasury address for JavaScript --}}
    <script>
        window.TREASURY_ADDRESS = '{{ config('founders.algorand.treasury_address') }}';
    </script>

    <style>
        /* Pera Wallet Modal Customization */
        .pera-wallet-modal {
            z-index: 9999 !important;
        }

        /* FlorenceEGI Brand Color Variables */
        :root {
            --florence-gold: #D4A574;
            --florence-green: #2D5016;
            --florence-blue: #1B365D;
            --florence-gray: #6B6B6B;
        }

        /* Custom animations */
        @keyframes pulse-gold {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(212, 165, 116, 0.7);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(212, 165, 116, 0);
            }
        }

        .animate-pulse-gold {
            animation: pulse-gold 2s infinite;
        }

        /* Wallet connection button hover effects */
        #connect-wallet-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 165, 116, 0.4);
        }

        /* Form styling enhancements */
        input:focus,
        textarea:focus {
            border-color: var(--florence-gold);
            ring-color: rgba(212, 165, 116, 0.3);
        }

        /* Success state styling */
        .success-glow {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        }
    </style>
</div>
