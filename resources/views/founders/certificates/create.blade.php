<x-founders-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Nuovo Certificato') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                <div class="border-b border-gray-200 p-6">
                    <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                        Crea Nuovo Certificato
                    </h1>
                    <p class="mt-2 text-gray-600">
                        Crea un certificato Padre Fondatore per la gestione Web2.0
                    </p>
                </div>

                <form action="{{ route('founders.certificates.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="space-y-6">
                        {{-- Collection Selection --}}
                        <div>
                            <label for="collection_id" class="block text-sm font-medium text-gray-700">
                                Collection *
                            </label>
                            <select name="collection_id" id="collection_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Seleziona una Collection</option>
                                @foreach ($collections as $collection)
                                    <option value="{{ $collection->id }}"
                                        {{ old('collection_id') == $collection->id || ($selectedCollection && $selectedCollection->id == $collection->id) ? 'selected' : '' }}>
                                        {{ $collection->name }} ({{ $collection->available_tokens }} disponibili)
                                    </option>
                                @endforeach
                            </select>
                            @error('collection_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Certificate Title --}}
                        <div>
                            <label for="certificate_title" class="block text-sm font-medium text-gray-700">
                                Titolo Certificato *
                            </label>
                            <input type="text" name="certificate_title" id="certificate_title" required
                                value="{{ old('certificate_title') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('certificate_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Price and Currency --}}
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="base_price" class="block text-sm font-medium text-gray-700">
                                    Prezzo Base *
                                </label>
                                <input type="number" name="base_price" id="base_price" step="0.01" min="0.01"
                                    required value="{{ old('base_price') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('base_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700">
                                    Valuta *
                                </label>
                                <select name="currency" id="currency" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="ALGO" {{ old('currency') == 'ALGO' ? 'selected' : '' }}>ALGO
                                    </option>
                                </select>
                                @error('currency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Stato Iniziale *
                            </label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bozza</option>
                                <option value="ready" {{ old('status') == 'ready' ? 'selected' : '' }}>Pronto per
                                    Vendita</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Metadata Section --}}
                        <div class="rounded-lg bg-gray-50 p-6">
                            <h3 class="mb-4 text-lg font-medium text-gray-900">Metadata NFT</h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="metadata_title" class="block text-sm font-medium text-gray-700">
                                        Titolo NFT
                                    </label>
                                    <input type="text" name="metadata[title]" id="metadata_title"
                                        value="{{ old('metadata.title') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="metadata_image" class="block text-sm font-medium text-gray-700">
                                        URL Immagine
                                    </label>
                                    <input type="url" name="metadata[image]" id="metadata_image"
                                        value="{{ old('metadata.image') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="metadata_description" class="block text-sm font-medium text-gray-700">
                                    Descrizione NFT
                                </label>
                                <textarea name="metadata[description]" id="metadata_description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('metadata.description') }}</textarea>
                            </div>

                            <div class="mt-4">
                                <label for="metadata_external_url" class="block text-sm font-medium text-gray-700">
                                    URL Esterno
                                </label>
                                <input type="url" name="metadata[external_url]" id="metadata_external_url"
                                    value="{{ old('metadata.external_url') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                            <a href="{{ route('founders.certificates.index') }}"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                Annulla
                            </a>
                            <button type="submit"
                                class="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-6 py-2 text-sm font-semibold uppercase tracking-wider text-white transition-all duration-150 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Crea Certificato
                            </button>
                        </div>
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
