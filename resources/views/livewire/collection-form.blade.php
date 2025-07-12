{{--
    @Oracode Collection Form Template
    🎯 Purpose: Interactive form for creating/editing collections with FlorenceEGI styling
    🧱 Core Logic: Real-time validation, collapsible sections, metadata handling
    🛡️ Security: CSRF protection, input validation, proper escaping

    @package resources/views/livewire
    @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
    @version 1.0.0 (FlorenceEGI - Collections Management)
    @date 2025-07-11
    @purpose Interactive collection form with FlorenceEGI branding
--}}

<div class="mx-auto max-w-4xl rounded-lg bg-white p-6 shadow-lg">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="mb-2 text-3xl font-bold text-slate-800">
            {{ $isEditing ? 'Modifica Collection' : 'Crea Nuova Collection' }}
        </h1>
        <p class="text-slate-600">
            {{ $isEditing ? 'Aggiorna i dettagli della collection esistente' : 'Configura una nuova collection di certificati Padri Fondatori' }}
        </p>
    </div>

    <form wire:submit.prevent="save">
        {{-- Basic Information --}}
        <div class="mb-6 rounded-lg bg-slate-50 p-6">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Informazioni Base</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Name --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                        Nome Collection *
                    </label>
                    <input type="text" id="name" wire:model="name"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="es. Padri Fondatori - Round 1">
                    @error('name')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                        Stato *
                    </label>
                    <select id="status" wire:model="status"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="draft">Bozza</option>
                        <option value="active">Attiva</option>
                        <option value="paused">In Pausa</option>
                        <option value="completed">Completata</option>
                        <option value="cancelled">Annullata</option>
                    </select>
                    @error('status')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="mb-2 block text-sm font-medium text-slate-700">
                        Descrizione
                    </label>
                    <textarea id="description" wire:model="description" rows="3"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="Descrizione dell'evento o della collection..."></textarea>
                    @error('description')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Token Configuration --}}
        <div class="mb-6 rounded-lg bg-slate-50 p-6">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Configurazione Token</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                {{-- Total Tokens --}}
                <div>
                    <label for="total_tokens" class="mb-2 block text-sm font-medium text-slate-700">
                        Numero Totale Token *
                    </label>
                    <input type="number" id="total_tokens" wire:model="total_tokens" min="1" max="1000"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('total_tokens')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Base Price --}}
                <div>
                    <label for="base_price" class="mb-2 block text-sm font-medium text-slate-700">
                        Prezzo Base *
                    </label>
                    <input type="number" id="base_price" wire:model="base_price" step="0.01" min="0.01"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('base_price')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Currency --}}
                <div>
                    <label for="currency" class="mb-2 block text-sm font-medium text-slate-700">
                        Valuta *
                    </label>
                    <select id="currency" wire:model="currency"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="EUR">EUR (€)</option>
                        <option value="USD">USD ($)</option>
                        <option value="ALGO">ALGO</option>
                    </select>
                    @error('currency')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Event & Sale Dates --}}
        <div class="mb-6 rounded-lg bg-slate-50 p-6">
            <h2 class="mb-4 text-xl font-semibold text-slate-800">Date Evento e Vendita</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                {{-- Event Date --}}
                <div>
                    <label for="event_date" class="mb-2 block text-sm font-medium text-slate-700">
                        Data Evento
                    </label>
                    <input type="datetime-local" id="event_date" wire:model="event_date"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('event_date')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Sale Start Date --}}
                <div>
                    <label for="sale_start_date" class="mb-2 block text-sm font-medium text-slate-700">
                        Inizio Vendita
                    </label>
                    <input type="datetime-local" id="sale_start_date" wire:model="sale_start_date"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('sale_start_date')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Sale End Date --}}
                <div>
                    <label for="sale_end_date" class="mb-2 block text-sm font-medium text-slate-700">
                        Fine Vendita
                    </label>
                    <input type="datetime-local" id="sale_end_date" wire:model="sale_end_date"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('sale_end_date')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Advanced Options Toggle --}}
        <div class="mb-6">
            <button type="button" wire:click="toggleAdvancedOptions"
                class="flex w-full items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50 p-4 transition-colors hover:bg-emerald-100">
                <span class="text-lg font-medium text-emerald-800">Opzioni Avanzate</span>
                <svg class="{{ $showAdvancedOptions ? 'rotate-180' : '' }} h-5 w-5 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        {{-- Advanced Options Content --}}
        @if ($showAdvancedOptions)
            <div class="mb-6 rounded-lg bg-slate-50 p-6">
                <h2 class="mb-4 text-xl font-semibold text-slate-800">Configurazione Avanzata</h2>

                {{-- Payment Options --}}
                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" wire:model="allow_wallet_payments"
                                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm font-medium text-slate-700">Permetti pagamenti Wallet</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" wire:model="allow_fiat_payments"
                                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm font-medium text-slate-700">Permetti pagamenti FIAT</span>
                        </label>
                    </div>
                </div>

                {{-- Treasury Address --}}
                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="treasury_address" class="mb-2 block text-sm font-medium text-slate-700">
                            Treasury Address
                        </label>
                        <input type="text" id="treasury_address" wire:model="treasury_address"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Indirizzo wallet treasury">
                        @error('treasury_address')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="min_symbolic_price" class="mb-2 block text-sm font-medium text-slate-700">
                            Prezzo Simbolico (ALGO)
                        </label>
                        <input type="number" id="min_symbolic_price" wire:model="min_symbolic_price"
                            step="0.000001" min="0.000001"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('min_symbolic_price')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Shipping Option --}}
                <div class="mb-6">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" wire:model="requires_shipping"
                            class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm font-medium text-slate-700">Richiede spedizione prismi</span>
                    </label>
                </div>

                {{-- Metadata Toggle --}}
                <div class="mb-4">
                    <button type="button" wire:click="toggleMetadata"
                        class="flex items-center space-x-2 text-emerald-600 hover:text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>{{ $showMetadata ? 'Nascondi' : 'Configura' }} Metadata NFT</span>
                    </button>
                </div>

                {{-- Metadata Section --}}
                @if ($showMetadata)
                    <div class="mb-6 rounded-lg border border-slate-200 bg-white p-4">
                        <h3 class="mb-4 text-lg font-medium text-slate-800">Metadata NFT</h3>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="metadata_title" class="mb-2 block text-sm font-medium text-slate-700">
                                    Titolo NFT
                                </label>
                                <input type="text" id="metadata_title" wire:model="metadata_title"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label for="metadata_image" class="mb-2 block text-sm font-medium text-slate-700">
                                    URL Immagine
                                </label>
                                <input type="url" id="metadata_image" wire:model="metadata_image"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="metadata_description"
                                    class="mb-2 block text-sm font-medium text-slate-700">
                                    Descrizione NFT
                                </label>
                                <textarea id="metadata_description" wire:model="metadata_description" rows="3"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Shipping Info Toggle --}}
                @if ($requires_shipping)
                    <div class="mb-4">
                        <button type="button" wire:click="toggleShippingInfo"
                            class="flex items-center space-x-2 text-emerald-600 hover:text-emerald-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>{{ $showShippingInfo ? 'Nascondi' : 'Configura' }} Info Spedizione</span>
                        </button>
                    </div>

                    {{-- Shipping Info Section --}}
                    @if ($showShippingInfo)
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <h3 class="mb-4 text-lg font-medium text-slate-800">Informazioni Spedizione</h3>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label for="shipping_supplier_name"
                                        class="mb-2 block text-sm font-medium text-slate-700">
                                        Nome Fornitore
                                    </label>
                                    <input type="text" id="shipping_supplier_name"
                                        wire:model="shipping_supplier_name"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <label for="shipping_supplier_contact"
                                        class="mb-2 block text-sm font-medium text-slate-700">
                                        Contatto Fornitore
                                    </label>
                                    <input type="text" id="shipping_supplier_contact"
                                        wire:model="shipping_supplier_contact"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <label for="shipping_estimated_delivery_days"
                                        class="mb-2 block text-sm font-medium text-slate-700">
                                        Giorni Consegna Stimati
                                    </label>
                                    <input type="number" id="shipping_estimated_delivery_days"
                                        wire:model="shipping_estimated_delivery_days" min="1" max="365"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Benefits Toggle --}}
                <div class="mb-4">
                    <button type="button" wire:click="toggleBenefits"
                        class="flex items-center space-x-2 text-emerald-600 hover:text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                        <span>{{ $showBenefits ? 'Nascondi' : 'Configura' }} Benefici Esclusivi</span>
                    </button>
                </div>

                {{-- Benefits Section --}}
                @if ($showBenefits)
                    <div class="mb-6 rounded-lg border border-slate-200 bg-white p-4">
                        <h3 class="mb-4 text-lg font-medium text-slate-800">👑 Benefici e Privilegi</h3>
                        <p class="mb-4 text-sm text-slate-600">
                            Seleziona i benefici esclusivi che saranno inclusi nei certificati di questa collection
                        </p>

                        @if (!empty($availableBenefits))
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                @foreach ($availableBenefits as $benefit)
                                    <div
                                        class="rounded-lg border border-slate-200 p-4 transition-colors hover:bg-slate-50">
                                        <label class="flex cursor-pointer items-start space-x-3">
                                            <input type="checkbox" wire:model="selectedBenefits"
                                                value="{{ $benefit['id'] }}"
                                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                            <div class="flex-1">
                                                <div class="mb-2 flex items-center space-x-2">
                                                    <span class="text-lg">
                                                        @switch($benefit['icon'])
                                                            @case('gem')
                                                                💎
                                                            @break

                                                            @case('crown')
                                                                👑
                                                            @break

                                                            @case('zap')
                                                                ⚡
                                                            @break

                                                            @case('star')
                                                                ⭐
                                                            @break

                                                            @case('gift')
                                                                🎁
                                                            @break

                                                            @case('calendar')
                                                                📅
                                                            @break

                                                            @case('chart-line')
                                                                📈
                                                            @break

                                                            @case('flask')
                                                                🧪
                                                            @break

                                                            @case('user-tie')
                                                                🤵
                                                            @break

                                                            @default
                                                                🏆
                                                        @endswitch
                                                    </span>
                                                    <span
                                                        class="font-medium text-slate-800">{{ $benefit['title'] }}</span>
                                                    <span
                                                        class="bg-{{ $benefit['color'] }}-100 text-{{ $benefit['color'] }}-700 inline-flex items-center rounded-full px-2 py-1 text-xs font-medium capitalize">
                                                        {{ $benefit['category'] }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-slate-600">
                                                    {{ \Illuminate\Support\Str::limit($benefit['description'], 120) }}
                                                </p>
                                                @if (!empty($benefit['valid_until']))
                                                    <p class="mt-1 text-xs text-amber-600">
                                                        Scade:
                                                        {{ \Carbon\Carbon::parse($benefit['valid_until'])->format('d/m/Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @if (!empty($selectedBenefits))
                                <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3">
                                    <div class="flex items-center space-x-2">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-emerald-800">
                                            {{ count($selectedBenefits) }} benefici selezionati
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="py-8 text-center">
                                <div class="mb-2 text-slate-400">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="mb-3 text-sm text-slate-500">Nessun beneficio disponibile</p>
                                <a href="{{ route('founders.benefits.create') }}"
                                    class="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-3 py-2 text-sm font-medium leading-4 text-white transition-colors hover:bg-emerald-700">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Crea Primo Beneficio
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- Submit Buttons --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('founders.collections.index') }}"
                class="rounded-md border border-slate-300 px-6 py-2 text-slate-700 transition-colors hover:bg-slate-50">
                Annulla
            </a>
            <button type="submit"
                class="rounded-md bg-emerald-600 px-6 py-2 text-white transition-colors hover:bg-emerald-700">
                {{ $isEditing ? 'Aggiorna Collection' : 'Crea Collection' }}
            </button>
        </div>
    </form>
</div>
