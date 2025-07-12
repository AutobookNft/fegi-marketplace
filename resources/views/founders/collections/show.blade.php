<x-founders-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ $collection->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Collection Overview --}}
            <div class="mb-6 bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                <div class="border-b border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $collection->name }}</h1>
                            <p class="mt-1 text-gray-600">{{ $collection->description }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span
                                class="{{ $collection->getStatusBadgeColor() }} rounded-full px-3 py-1 text-sm font-medium">
                                {{ $collection->getStatusLabel() }}
                            </span>
                            <a href="{{ route('founders.collections.edit', $collection) }}"
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Modifica
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-lg bg-emerald-50 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Certificati Emessi</h3>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ $collection->certificates_issued }}</p>
                                    <p class="text-sm text-gray-500">di {{ $collection->total_tokens }} totali</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg bg-blue-50 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Token Disponibili</h3>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $collection->available_tokens }}
                                    </p>
                                    <p class="text-sm text-gray-500">rimanenti da emettere</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg bg-purple-50 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Revenue Totale</h3>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        €{{ number_format($collection->total_revenue, 2) }}</p>
                                    <p class="text-sm text-gray-500">prezzo base:
                                        €{{ number_format($collection->base_price, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg bg-amber-50 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Completamento</h3>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ number_format($collection->getCompletionPercentage(), 1) }}%</p>
                                    <p class="text-sm text-gray-500">della collection</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mt-8">
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Progressione Collection</span>
                            <span
                                class="text-sm text-gray-500">{{ $collection->certificates_issued }}/{{ $collection->total_tokens }}</span>
                        </div>
                        <div class="h-3 w-full rounded-full bg-gray-200">
                            <div class="h-3 rounded-full bg-emerald-500 transition-all duration-300"
                                style="width: {{ $collection->getCompletionPercentage() }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Collection Details --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                {{-- Main Details --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-800">Dettagli Collection</h2>
                        </div>

                        <div class="space-y-6 p-6">
                            {{-- Basic Info --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $collection->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                                    <p class="mt-1 font-mono text-sm text-gray-900">{{ $collection->slug }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stato</label>
                                    <span
                                        class="{{ $collection->getStatusBadgeColor() }} mt-1 inline-flex rounded-full px-2 py-1 text-xs font-medium">
                                        {{ $collection->getStatusLabel() }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Valuta</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $collection->currency }}</p>
                                </div>
                            </div>

                            {{-- Dates --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                @if ($collection->event_date)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Data Evento</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $collection->event_date->format('d/m/Y H:i') }}</p>
                                    </div>
                                @endif
                                @if ($collection->sale_start_date)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Inizio Vendita</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $collection->sale_start_date->format('d/m/Y H:i') }}</p>
                                    </div>
                                @endif
                                @if ($collection->sale_end_date)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Fine Vendita</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $collection->sale_end_date->format('d/m/Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Description --}}
                            @if ($collection->description)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descrizione</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $collection->description }}</p>
                                </div>
                            @endif

                            {{-- Metadata --}}
                            @if ($collection->metadata)
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Metadata NFT</label>
                                    <div class="rounded-lg bg-gray-50 p-4">
                                        <pre class="text-sm text-gray-900">{{ json_encode($collection->metadata, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Quick Actions --}}
                    <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-800">Azioni Rapide</h3>
                        </div>
                        <div class="space-y-4 p-6">
                            @if ($collection->status === 'draft')
                                <form action="{{ route('founders.collections.activate', $collection) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center justify-center rounded-md bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Attiva Collection
                                    </button>
                                </form>
                            @elseif($collection->status === 'active')
                                <form action="{{ route('founders.collections.pause', $collection) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center justify-center rounded-md bg-yellow-600 px-4 py-2 text-white transition-colors hover:bg-yellow-700">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Metti in Pausa
                                    </button>
                                </form>
                            @elseif($collection->status === 'paused')
                                <form action="{{ route('founders.collections.activate', $collection) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center justify-center rounded-md bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Riattiva Collection
                                    </button>
                                </form>
                            @endif

                            @if ($collection->founderCertificates->count() === 0)
                                {{-- Se non ci sono certificati, mostra il pulsante per generarli --}}
                                <form action="{{ route('founders.collections.generate-certificates', $collection) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="flex w-full items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white transition-colors hover:bg-indigo-700"
                                            onclick="return confirm('Generare {{ $collection->total_tokens }} certificati per questa collection? Questa azione preparerà i certificati per la vendita (fase Web2.0).')">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Genera {{ $collection->total_tokens }} Certificati
                                    </button>
                                </form>
                                <div class="mt-2 text-xs text-gray-500 text-center">
                                    Fase Web2.0: Preparazione metadata certificati
                                </div>
                            @else
                                {{-- Se ci sono certificati, mostra il link per gestirli --}}
                                <a href="{{ route('founders.certificates.index', ['collection' => $collection->id]) }}"
                                   class="flex w-full items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-white transition-colors hover:bg-emerald-700">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Gestisci Certificati ({{ $collection->founderCertificates->count() }})
                                </a>
                                <div class="mt-2 text-xs text-gray-500 text-center">
                                    {{ $collection->founderCertificates->where('status', 'draft')->count() }} in bozza,
                                    {{ $collection->founderCertificates->where('status', 'ready')->count() }} pronti,
                                    {{ $collection->founderCertificates->where('status', 'issued')->count() }} venduti
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Asset Info --}}
                    @if ($collection->asset_id)
                        <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                            <div class="border-b border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-800">Asset Algorand</h3>
                            </div>
                            <div class="space-y-4 p-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Asset ID</label>
                                    <p class="mt-1 font-mono text-sm text-gray-900">{{ $collection->asset_id }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Treasury Address</label>
                                    <p class="mt-1 break-all font-mono text-sm text-gray-900">
                                        {{ $collection->treasury_address }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Collection Benefits --}}
            @if($collection->certificateBenefits->count() > 0)
                <div class="mt-6 bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">👑 Benefici Inclusi</h2>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $collection->certificateBenefits->count() }} Benefici
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $collection->activeBenefits->count() }} Attivi
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($collection->certificateBenefits as $benefit)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow {{ !$benefit->is_active ? 'bg-gray-50 opacity-75' : 'bg-white' }}">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-{{ $benefit->color }}-100 rounded-full flex items-center justify-center">
                                                <span class="text-lg">
                                                    @switch($benefit->icon)
                                                        @case('gem') 💎 @break
                                                        @case('crown') 👑 @break
                                                        @case('zap') ⚡ @break
                                                        @case('star') ⭐ @break
                                                        @case('gift') 🎁 @break
                                                        @case('calendar') 📅 @break
                                                        @case('chart-line') 📈 @break
                                                        @case('flask') 🧪 @break
                                                        @case('user-tie') 🤵 @break
                                                        @default 🏆
                                                    @endswitch
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h3 class="text-sm font-semibold text-gray-900">{{ $benefit->title }}</h3>
                                                @if(!$benefit->is_active)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                        Inattivo
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-600 mb-2">
                                                {{ Str::limit($benefit->description, 100) }}
                                            </p>
                                            <div class="flex items-center justify-between">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $benefit->color }}-100 text-{{ $benefit->color }}-700 capitalize">
                                                    {{ $benefit->getCategoryLabel() }}
                                                </span>
                                                @if($benefit->valid_until)
                                                    <span class="text-xs text-amber-600">
                                                        Scade: {{ $benefit->valid_until->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                Questi benefici saranno inclusi in tutti i certificati di questa collection
                            </div>
                            <a href="{{ route('founders.collections.edit', $collection) }}"
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifica Benefici
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-6 bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                    <div class="border-b border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800">👑 Benefici Inclusi</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nessun beneficio associato</h3>
                            <p class="text-gray-500 mb-6">
                                Aggiungi benefici esclusivi per rendere questa collection più attraente per i Padri Fondatori
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('founders.collections.edit', $collection) }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Aggiungi Benefici
                                </a>
                                <a href="{{ route('founders.benefits.create') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    Crea Nuovo Beneficio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Certificates List --}}
            @if ($collection->founderCertificates->count() > 0)
                <div class="mt-6 bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                    <div class="border-b border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800">Certificati Emessi</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Certificato
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Investitore
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Data Emissione
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            ASA ID
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Stato
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($collection->founderCertificates as $certificate)
                                        <tr>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    #{{ $certificate->index }}
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $certificate->investor_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $certificate->investor_email }}
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                                {{ $certificate->issued_at?->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 font-mono text-sm text-gray-900">
                                                {{ $certificate->asa_id ?? 'N/A' }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <span
                                                    class="{{ $certificate->is_complete ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                                    {{ $certificate->is_complete ? 'Completato' : 'In Corso' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-founders-layout>
