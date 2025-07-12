<x-founders-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Gestione Collections') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">

                {{-- Header with Create Button --}}
                <div class="flex items-center justify-between p-6 bg-white border-b border-gray-200">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Collections</h1>
                        <p class="text-gray-600">Gestisci le tue collections di certificati Padri Fondatori</p>
                    </div>
                    <a href="{{ route('founders.collections.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nuova Collection
                    </a>
                </div>

                {{-- Collections List --}}
                <div class="p-6">
                    @if($collections->count() > 0)
                        <div class="grid gap-6">
                            @foreach($collections as $collection)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-800">
                                                        <a href="{{ route('founders.collections.show', $collection) }}"
                                                           class="hover:text-emerald-600 transition-colors">
                                                            {{ $collection->name }}
                                                        </a>
                                                    </h3>
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $collection->getStatusBadgeColor() }}">
                                                        {{ $collection->getStatusLabel() }}
                                                    </span>
                                                </div>

                                                @if($collection->description)
                                                    <p class="text-gray-600 mb-4">{{ $collection->description }}</p>
                                                @endif

                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="font-medium text-gray-700">Token:</span>
                                                        <span class="text-gray-600">{{ $collection->certificates_issued }}/{{ $collection->total_tokens }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Prezzo:</span>
                                                        <span class="text-gray-600">{{ number_format($collection->base_price, 2) }} {{ $collection->currency }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Revenue:</span>
                                                        <span class="text-gray-600">{{ number_format($collection->total_revenue, 2) }} €</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Completamento:</span>
                                                        <span class="text-gray-600">{{ number_format($collection->getCompletionPercentage(), 1) }}%</span>
                                                    </div>
                                                </div>

                                                @if($collection->event_date)
                                                    <div class="mt-2 text-sm text-gray-500">
                                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Evento: {{ $collection->event_date->format('d/m/Y H:i') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex items-center space-x-2 ml-4">
                                                {{-- Quick Actions --}}
                                                @if($collection->status === 'draft')
                                                    <form action="{{ route('founders.collections.activate', $collection) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-md hover:bg-green-200 transition-colors">
                                                            Attiva
                                                        </button>
                                                    </form>
                                                @elseif($collection->status === 'active')
                                                    <form action="{{ route('founders.collections.pause', $collection) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 transition-colors">
                                                            Pausa
                                                        </button>
                                                    </form>
                                                @elseif($collection->status === 'paused')
                                                    <form action="{{ route('founders.collections.activate', $collection) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-md hover:bg-green-200 transition-colors">
                                                            Riattiva
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('founders.collections.edit', $collection) }}"
                                                   class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition-colors">
                                                    Modifica
                                                </a>

                                                <a href="{{ route('founders.collections.show', $collection) }}"
                                                   class="px-3 py-1 text-xs bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors">
                                                    Dettagli
                                                </a>
                                            </div>
                                        </div>

                                        {{-- Progress Bar --}}
                                        @if($collection->total_tokens > 0)
                                            <div class="mt-4">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-sm font-medium text-gray-700">Progressione</span>
                                                    <span class="text-sm text-gray-500">{{ $collection->certificates_issued }}/{{ $collection->total_tokens }}</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300"
                                                         style="width: {{ $collection->getCompletionPercentage() }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $collections->links() }}
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nessuna Collection</h3>
                            <p class="text-gray-500 mb-4">Crea la tua prima collection per iniziare a gestire i certificati.</p>
                            <a href="{{ route('founders.collections.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Crea Prima Collection
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-founders-layout>
