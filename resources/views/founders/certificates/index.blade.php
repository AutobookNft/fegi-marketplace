<x-founders-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Gestione Certificati') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Header Section --}}
            <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg mb-6">
                <div class="border-b border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                                Certificati Padre Fondatore
                            </h1>
                            <p class="mt-2 text-gray-600">
                                Gestione professionale dei certificati FlorenceEGI - Nuovo Rinascimento Ecologico Digitale
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('founders.certificates.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-emerald-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-wider hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nuovo Certificato
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-gray-50 px-6 py-4">
                    <form method="GET" action="{{ route('founders.certificates.index') }}" class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label for="collection" class="sr-only">Collection</label>
                            <select name="collection" id="collection"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                                <option value="">Tutte le Collections</option>
                                @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}" {{ request('collection') == $collection->id ? 'selected' : '' }}>
                                        {{ $collection->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="status" class="sr-only">Stato</label>
                            <select name="status" id="status"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                                <option value="">Tutti gli Stati</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bozza</option>
                                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Pronto</option>
                                <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Venduto</option>
                                <option value="minted" {{ request('status') == 'minted' ? 'selected' : '' }}>Mintato</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completato</option>
                            </select>
                        </div>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Filtra
                        </button>
                        @if(request()->hasAny(['collection', 'status']))
                            <a href="{{ route('founders.certificates.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Certificates Grid --}}
            @if($certificates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($certificates as $certificate)
                        <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                            {{-- Certificate Header --}}
                            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-semibold text-white" style="font-family: 'Playfair Display', serif;">
                                                Certificato #{{ str_pad($certificate->index, 3, '0', STR_PAD_LEFT) }}
                                            </h3>
                                            <p class="text-emerald-100 text-sm">
                                                {{ $certificate->collection->name ?? 'Collection non specificata' }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $certificate->getStatusBadgeColor() }}">
                                        {{ $certificate->getStatusLabel() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Certificate Body --}}
                            <div class="px-6 py-4">
                                <div class="space-y-3">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">
                                            {{ $certificate->certificate_title ?: 'Titolo non specificato' }}
                                        </h4>
                                    </div>

                                    @if($certificate->investor_name)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $certificate->investor_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $certificate->investor_email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            {{ number_format($certificate->base_price, 2) }} {{ $certificate->currency }}
                                        </div>
                                        @if($certificate->issued_at)
                                            <div class="text-xs text-gray-500">
                                                {{ $certificate->issued_at->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Certificate Actions --}}
                            <div class="bg-gray-50 px-6 py-3">
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('founders.certificates.show', $certificate) }}"
                                       class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-500">
                                        Visualizza
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('founders.certificates.edit', $certificate) }}"
                                           class="text-gray-400 hover:text-gray-500" title="Modifica">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @if(!$certificate->asa_id && $certificate->status !== 'minted')
                                            <form action="{{ route('founders.certificates.destroy', $certificate) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Sei sicuro di voler eliminare questo certificato?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-500" title="Elimina">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $certificates->withQueryString()->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900" style="font-family: 'Playfair Display', serif;">
                            Nessun Certificato Trovato
                        </h3>
                        <p class="mt-2 text-gray-500">
                            @if(request()->hasAny(['collection', 'status']))
                                Nessun certificato corrisponde ai filtri selezionati.
                            @else
                                Non ci sono ancora certificati creati. Inizia creando la prima collection.
                            @endif
                        </p>
                        <div class="mt-6 space-x-4">
                            @if(request()->hasAny(['collection', 'status']))
                                <a href="{{ route('founders.certificates.index') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    Rimuovi Filtri
                                </a>
                            @endif
                            <a href="{{ route('founders.collections.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                Gestisci Collections
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Custom Styles for Elegant Design --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');

        .certificate-card {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .certificate-card:hover {
            border-color: #10b981;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .certificate-header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            position: relative;
        }

        .certificate-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        }
    </style>
</x-founders-layout>
