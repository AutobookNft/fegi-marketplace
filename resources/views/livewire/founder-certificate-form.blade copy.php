{{--
    @Oracode Livewire Blade Template for Founder Certificate Form
    🎯 Purpose: FlorenceEGI branded form for certificate issuance with Rinascimento styling
    🧱 Core Logic: Interactive form, real-time feedback, dashboard statistics, GDPR compliance
    🛡️ Security: CSRF protection, input validation, sanitized output

    @package resources/views/livewire
    @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
    @version 1.0.0 (FlorenceEGI - Padri Fondatori Form Template)
    @date 2025-07-05
    @purpose Complete certificate issuance form with FlorenceEGI brand styling
--}}

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-amber-50 to-emerald-50">
    {{-- Header Section with FlorenceEGI Branding --}}
    <header class="bg-white border-b shadow-sm border-amber-200">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
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

                {{-- Network Status Indicator --}}
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-sm font-medium text-slate-600">
                        {{ ucfirst($statistics['round_info']['network'] ?? 'TestNet') }}
                    </span>
                </div>
            </div>
        </div>
    </header>

    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            {{-- Main Form Section --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden bg-white border shadow-xl rounded-2xl border-amber-100">

                    {{-- Form Header --}}
                    <div class="px-8 py-6 bg-gradient-to-r from-amber-500 to-amber-600">
                        <h2 class="mb-2 text-3xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                            Certificato Padre Fondatore
                        </h2>
                        <p class="text-lg text-amber-100">
                            Unisciti ai primi sostenitori del Nuovo Rinascimento
                        </p>
                    </div>

                    {{-- Success Message --}}
                    @if($showSuccess)
                        <div class="p-6 m-6 border-l-4 rounded-r-lg bg-emerald-50 border-emerald-400">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 ml-3">
                                    <h3 class="mb-2 text-lg font-bold text-emerald-800">
                                        🎉 Certificato Emesso con Successo!
                                    </h3>
                                    <div class="space-y-2 text-emerald-700">
                                        <p><strong>Certificato #{{ $successData['certificate_number'] ?? '' }}</strong></p>
                                        <p><strong>ASA ID:</strong> {{ $successData['asa_id'] ?? '' }}</p>
                                        <p><strong>Transaction ID:</strong>
                                            <code class="px-2 py-1 text-xs rounded bg-emerald-100">
                                                {{ Str::limit($successData['transaction_id'] ?? '', 20) }}
                                            </code>
                                        </p>
                                        <p>
                                            <strong>Token:</strong>
                                            @if($successData['token_transferred'] ?? false)
                                                <span class="text-emerald-600">✅ Trasferito al tuo wallet</span>
                                            @else
                                                <span class="text-amber-600">🏦 Conservato nel Treasury (fornisci wallet per trasferimento)</span>
                                            @endif
                                        </p>
                                        @if($successData['pdf_url'] ?? false)
                                            <p>
                                                <a href="{{ $successData['pdf_url'] }}"
                                                   target="_blank"
                                                   class="inline-flex items-center font-medium text-emerald-600 hover:text-emerald-800">
                                                    📄 Scarica Certificato PDF
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4"/>
                                                    </svg>
                                                </a>
                                            </p>
                                        @endif
                                        @if($successData['blockchain_explorer'] ?? false)
                                            <p>
                                                <a href="{{ $successData['blockchain_explorer'] }}"
                                                   target="_blank"
                                                   class="inline-flex items-center font-medium text-blue-600 hover:text-blue-800">
                                                    🔗 Visualizza su Blockchain Explorer
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4"/>
                                                    </svg>
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <button wire:click="resetForm"
                                                class="px-4 py-2 font-medium text-white transition-colors rounded-lg bg-emerald-600 hover:bg-emerald-700">
                                            Emetti Nuovo Certificato
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Certificate Form --}}
                        <form wire:submit="submit" class="p-8 space-y-6">

                            {{-- Error Message --}}
                            @if($errorMessage)
                                <div class="p-4 border-l-4 border-red-400 rounded-r-lg bg-red-50">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
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
                                    <label for="investorName" class="block mb-2 text-sm font-semibold text-slate-700">
                                        Nome Completo *
                                    </label>
                                    <input type="text"
                                           id="investorName"
                                           wire:model.live="investorName"
                                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all @error('investorName') border-red-300 @enderror"
                                           placeholder="Es: Mario Rossi"
                                           maxlength="200">
                                    @error('investorName')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="investorEmail" class="block mb-2 text-sm font-semibold text-slate-700">
                                        Email *
                                    </label>
                                    <input type="email"
                                           id="investorEmail"
                                           wire:model.live="investorEmail"
                                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all @error('investorEmail') border-red-300 @enderror"
                                           placeholder="mario.rossi@example.com"
                                           maxlength="200">
                                    @error('investorEmail')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Telefono --}}
                                <div>
                                    <label for="investorPhone" class="block mb-2 text-sm font-semibold text-slate-700">
                                        Telefono
                                    </label>
                                    <input type="tel"
                                           id="investorPhone"
                                           wire:model.live="investorPhone"
                                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all @error('investorPhone') border-red-300 @enderror"
                                           placeholder="+39 123 456 7890"
                                           maxlength="50">
                                    @error('investorPhone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Indirizzo Spedizione --}}
                                <div class="md:col-span-2">
                                    <label for="investorAddress" class="block mb-2 text-sm font-semibold text-slate-700">
                                        Indirizzo Spedizione Prisma
                                    </label>
                                    <textarea id="investorAddress"
                                              wire:model.live="investorAddress"
                                              rows="3"
                                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all @error('investorAddress') border-red-300 @enderror"
                                              placeholder="Via Roma 123, 00100 Roma (RM), Italia"
                                              maxlength="1000"></textarea>
                                    @error('investorAddress')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @php
                                    $address = config('app.algo_address')
                                @endphp

                                {{-- Wallet Algorand --}}
                                <div class="md:col-span-2">
                                    <label for="investorWallet" class="block mb-2 text-sm font-semibold text-slate-700">
                                        Wallet Algorand (opzionale)
                                    </label>
                                    <input type="text"
                                           id="investorWallet"
                                           wire:model.live="investorWallet"
                                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all font-mono text-sm @error('investorWallet') border-red-300 @enderror"
                                           placeholder={{ $address }}>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Se fornito, il token ASA sarà trasferito immediatamente al tuo wallet.
                                        Se non fornito, il token resterà nel Treasury per trasferimento successivo.
                                    </p>
                                    @error('investorWallet')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            {{-- GDPR Consent --}}
                            <div class="p-6 border-2 rounded-lg bg-slate-50 border-slate-200">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 pt-1">
                                        <input type="checkbox"
                                               id="gdprConsent"
                                               wire:model.live="gdprConsent"
                                               class="w-4 h-4 text-amber-600 border-2 border-slate-300 rounded focus:ring-amber-500 @error('gdprConsent') border-red-300 @enderror">
                                    </div>
                                    <div class="flex-1">
                                        <label for="gdprConsent" class="text-sm cursor-pointer text-slate-700">
                                            Accetto il trattamento dei dati personali come indicato nell'
                                            <button type="button"
                                                    wire:click="toggleGdprModal"
                                                    class="font-medium underline text-amber-600 hover:text-amber-800">
                                                informativa privacy
                                            </button>
                                            per l'emissione del certificato e la spedizione del prisma commemorativo. *
                                        </label>
                                        @error('gdprConsent')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex justify-center pt-6">
                                <button type="submit"
                                        @disabled($isSubmitting)
                                        class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 disabled:from-slate-400 disabled:to-slate-500 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:hover:transform-none transition-all duration-200 text-lg">
                                    @if($isSubmitting)
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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

            {{-- Statistics Sidebar --}}
            <div class="space-y-6">

                {{-- Round Information --}}
                <div class="overflow-hidden bg-white border shadow-lg rounded-xl border-emerald-100">
                    <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600">
                        <h3 class="text-xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                            Round Padri Fondatori
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
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
                        <div class="pt-4 border-t">
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
                @if(isset($statistics['certificates']))
                    <div class="overflow-hidden bg-white border border-blue-100 shadow-lg rounded-xl">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                            <h3 class="text-xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                                Progresso Emissione
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="mb-4 text-center">
                                <div class="text-3xl font-bold text-blue-600">
                                    {{ $statistics['certificates']['total_issued'] ?? 0 }}
                                    <span class="text-lg text-slate-500">/ {{ $statistics['certificates']['total_available'] ?? 40 }}</span>
                                </div>
                                <p class="text-sm text-slate-600">Certificati Emessi</p>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="w-full h-3 mb-4 rounded-full bg-slate-200">
                                <div class="h-3 transition-all duration-500 rounded-full bg-gradient-to-r from-blue-500 to-blue-600"
                                     style="width: {{ $statistics['certificates']['completion_percentage'] ?? 0 }}%"></div>
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
    @if($showGdprConsent)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-slate-600 to-slate-700">
                    <h3 class="text-xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                        Informativa Privacy
                    </h3>
                    <button wire:click="toggleGdprModal"
                            class="text-white transition-colors hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4 text-sm text-slate-700">
                    <div>
                        <h4 class="mb-2 font-semibold text-slate-800">Finalità del Trattamento</h4>
                        <p>I tuoi dati personali vengono trattati per:</p>
                        <ul class="mt-2 space-y-1 list-disc list-inside">
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
                        <p>Hai diritto di accesso, rettifica, cancellazione e opposizione al trattamento dei tuoi dati.</p>
                    </div>
                    <div class="p-3 border-l-4 rounded-r bg-amber-50 border-amber-400">
                        <p class="text-amber-800">
                            <strong>Titolare del Trattamento:</strong> FlorenceEGI<br>
                            <strong>Contatto Privacy:</strong> privacy@florenceegi.it
                        </p>
                    </div>
                </div>
                <div class="flex justify-end px-6 py-4 bg-slate-50">
                    <button wire:click="toggleGdprModal"
                            class="px-4 py-2 font-medium text-white transition-colors rounded-lg bg-slate-600 hover:bg-slate-700">
                        Ho Capito
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

