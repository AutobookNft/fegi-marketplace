<x-founders-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Nuovo Beneficio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                <div class="border-b border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-800" style="font-family: 'Playfair Display', serif;">
                        👑 Crea Nuovo Beneficio
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Aggiungi un nuovo privilegio esclusivo per i Padri Fondatori
                    </p>
                </div>

                <form action="{{ route('founders.benefits.store') }}" method="POST" class="space-y-6 p-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        {{-- Titolo --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Titolo Beneficio *
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                placeholder="es. Accesso VIP Eventi FlorenceEGI">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Categoria --}}
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">
                                Categoria *
                            </label>
                            <select name="category" id="category" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Seleziona categoria</option>
                                @foreach ($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Icona --}}
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700">
                                Icona *
                            </label>
                            <select name="icon" id="icon" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Seleziona icona</option>
                                @foreach ($icons as $key => $label)
                                    <option value="{{ $key }}" {{ old('icon') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Colore --}}
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700">
                                Colore *
                            </label>
                            <select name="color" id="color" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Seleziona colore</option>
                                @foreach ($colors as $key => $label)
                                    <option value="{{ $key }}" {{ old('color') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Descrizione --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Descrizione *
                        </label>
                        <textarea name="description" id="description" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            placeholder="Descrivi dettagliatamente il beneficio e come può essere utilizzato...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Validità --}}
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div>
                            <label for="valid_from" class="block text-sm font-medium text-gray-700">
                                Valido dal
                            </label>
                            <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('valid_from')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="valid_until" class="block text-sm font-medium text-gray-700">
                                Valido fino al
                            </label>
                            <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('valid_until')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Limite di utilizzo --}}
                    <div>
                        <label for="usage_limit" class="block text-sm font-medium text-gray-700">
                            Limite di utilizzo
                        </label>
                        <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}"
                            min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            placeholder="Lascia vuoto per illimitato">
                        @error('usage_limit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Numero massimo di volte che il beneficio può essere utilizzato (opzionale)
                        </p>
                    </div>

                    {{-- Stato attivo --}}
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Beneficio attivo
                        </label>
                    </div>

                    {{-- Pulsanti --}}
                    <div class="flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                        <a href="{{ route('founders.benefits.index') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Annulla
                        </a>
                        <button type="submit"
                            class="inline-flex items-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-700">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crea Beneficio
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
</x-founders-layout>
