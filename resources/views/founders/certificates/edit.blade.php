<x-founders-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Modifica Certificato #{{ str_pad($certificate->index, 3, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg dark:bg-gray-800">
                <div class="border-b border-gray-200 p-6">
                    <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                        Modifica Certificato
                    </h1>
                    <p class="mt-2 text-gray-600">
                        Certificato #{{ str_pad($certificate->index, 3, '0', STR_PAD_LEFT) }} -
                        {{ $certificate->collection->name }}
                    </p>
                </div>

                <form action="{{ route('founders.certificates.update', $certificate) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        {{-- Certificate Title --}}
                        <div>
                            <label for="certificate_title" class="block text-sm font-medium text-gray-700">
                                Titolo Certificato *
                            </label>
                            <input type="text" name="certificate_title" id="certificate_title" required
                                value="{{ old('certificate_title', $certificate->certificate_title) }}"
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
                                    required value="{{ old('base_price', $certificate->base_price) }}"
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
                                    <option value="EUR"
                                        {{ old('currency', $certificate->currency) == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="USD"
                                        {{ old('currency', $certificate->currency) == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="ALGO"
                                        {{ old('currency', $certificate->currency) == 'ALGO' ? 'selected' : '' }}>ALGO
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
                                Stato *
                            </label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="draft"
                                    {{ old('status', $certificate->status) == 'draft' ? 'selected' : '' }}>Bozza
                                </option>
                                <option value="ready"
                                    {{ old('status', $certificate->status) == 'ready' ? 'selected' : '' }}>Pronto per
                                    Vendita</option>
                                <option value="issued"
                                    {{ old('status', $certificate->status) == 'issued' ? 'selected' : '' }}>Venduto
                                </option>
                                <option value="minted"
                                    {{ old('status', $certificate->status) == 'minted' ? 'selected' : '' }}>Mintato
                                </option>
                                <option value="completed"
                                    {{ old('status', $certificate->status) == 'completed' ? 'selected' : '' }}>
                                    Completato</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Investor Information --}}
                        <div class="rounded-lg bg-emerald-50 p-6">
                            <h3 class="mb-4 text-lg font-medium text-gray-900">Informazioni Investitore</h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="investor_name" class="block text-sm font-medium text-gray-700">
                                        Nome Completo
                                    </label>
                                    <input type="text" name="investor_name" id="investor_name"
                                        value="{{ old('investor_name', $certificate->investor_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @error('investor_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="investor_email" class="block text-sm font-medium text-gray-700">
                                        Email
                                    </label>
                                    <input type="email" name="investor_email" id="investor_email"
                                        value="{{ old('investor_email', $certificate->investor_email) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @error('investor_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="investor_phone" class="block text-sm font-medium text-gray-700">
                                        Telefono
                                    </label>
                                    <input type="text" name="investor_phone" id="investor_phone"
                                        value="{{ old('investor_phone', $certificate->investor_phone) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @error('investor_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="investor_wallet" class="block text-sm font-medium text-gray-700">
                                        Wallet Algorand
                                    </label>
                                    <input type="text" name="investor_wallet" id="investor_wallet"
                                        value="{{ old('investor_wallet', $certificate->investor_wallet) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @error('investor_wallet')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="investor_address" class="block text-sm font-medium text-gray-700">
                                    Indirizzo
                                </label>
                                <textarea name="investor_address" id="investor_address" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('investor_address', $certificate->investor_address) }}</textarea>
                                @error('investor_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
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
                                        value="{{ old('metadata.title', $certificate->metadata['title'] ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="metadata_image" class="block text-sm font-medium text-gray-700">
                                        URL Immagine
                                    </label>
                                    <input type="url" name="metadata[image]" id="metadata_image"
                                        value="{{ old('metadata.image', $certificate->metadata['image'] ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="metadata_description" class="block text-sm font-medium text-gray-700">
                                    Descrizione NFT
                                </label>
                                <textarea name="metadata[description]" id="metadata_description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('metadata.description', $certificate->metadata['description'] ?? '') }}</textarea>
                            </div>

                            <div class="mt-4">
                                <label for="metadata_external_url" class="block text-sm font-medium text-gray-700">
                                    URL Esterno
                                </label>
                                <input type="url" name="metadata[external_url]" id="metadata_external_url"
                                    value="{{ old('metadata.external_url', $certificate->metadata['external_url'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('founders.certificates.show', $certificate) }}"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Torna al Certificato
                                </a>
                                <a href="{{ route('founders.certificates.index') }}"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    Torna alla Lista
                                </a>
                            </div>
                            <div class="flex items-center space-x-4">
                                @if(!$certificate->asa_id && $certificate->status !== 'minted')
                                    <button type="button"
                                            onclick="if(confirm('Sei sicuro di voler eliminare questo certificato?')) { document.getElementById('delete-form').submit(); }"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-wider hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Elimina
                                    </button>
                                @endif
                                <button type="submit"
                                    class="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-6 py-2 text-sm font-semibold uppercase tracking-wider text-white transition-all duration-150 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Salva Modifiche
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Form di eliminazione separato per evitare conflitti --}}
                @if (!$certificate->asa_id && $certificate->status !== 'minted')
                    <form id="delete-form" action="{{ route('founders.certificates.destroy', $certificate) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');
    </style>
</x-founders-layout>
