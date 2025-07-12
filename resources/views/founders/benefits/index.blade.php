<x-founders-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Gestione Benefici
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Header Section --}}
            <div class="mb-6 bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-white bg-opacity-20">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h1 class="text-3xl font-bold text-white"
                                    style="font-family: 'Playfair Display', serif;">
                                    👑 Benefici Esclusivi
                                </h1>
                                <p class="text-lg text-purple-100">
                                    Gestisci i privilegi per i Padri Fondatori
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('founders.benefits.create') }}"
                                class="inline-flex items-center rounded-md bg-white bg-opacity-20 px-4 py-2 text-white transition-all duration-150 hover:bg-opacity-30">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nuovo Beneficio
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center">
                        <div class="rounded-full bg-green-100 p-3 text-green-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Benefici Attivi</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $benefits->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-100 p-3 text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Totale Benefici</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $benefits->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center">
                        <div class="rounded-full bg-purple-100 p-3 text-purple-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Categorie</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $benefits->pluck('category')->unique()->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center">
                        <div class="rounded-full bg-red-100 p-3 text-red-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Benefici Inattivi</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $benefits->where('is_active', false)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Benefits Grid --}}
            <div class="bg-white shadow-xl sm:rounded-lg">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Lista Benefici</h2>
                </div>

                @if ($benefits->count() > 0)
                    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($benefits as $benefit)
                            <div
                                class="rounded-lg border border-gray-200 bg-gray-50 p-6 transition-shadow hover:shadow-md">
                                <div class="mb-4 flex items-start justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="bg-{{ $benefit->color }}-100 flex h-10 w-10 items-center justify-center rounded-full">
                                                <span class="text-lg">
                                                    @switch($benefit->icon)
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
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $benefit->title }}
                                            </h3>
                                            <p class="text-sm capitalize text-gray-500">
                                                {{ $benefit->category }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if ($benefit->is_active)
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                Attivo
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                Inattivo
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <p class="mb-4 text-sm text-gray-600">
                                    {{ Str::limit($benefit->description, 100) }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        @if ($benefit->usage_limit)
                                            <span class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-600">
                                                Limite: {{ $benefit->usage_limit }}
                                            </span>
                                        @endif
                                        @if ($benefit->valid_until)
                                            <span class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-600">
                                                Scade: {{ $benefit->valid_until->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('founders.benefits.show', $benefit) }}"
                                            class="text-sm font-medium text-purple-600 hover:text-purple-800">
                                            Dettagli
                                        </a>
                                        <a href="{{ route('founders.benefits.edit', $benefit) }}"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                            Modifica
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="border-t border-gray-200 bg-white px-6 py-4">
                        {{ $benefits->links() }}
                    </div>
                @else
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nessun beneficio trovato</h3>
                        <p class="mt-1 text-sm text-gray-500">Inizia creando il primo beneficio per i Padri Fondatori.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('founders.benefits.create') }}"
                                class="inline-flex items-center rounded-md bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Crea Primo Beneficio
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');
    </style>
</x-founders-layout>
