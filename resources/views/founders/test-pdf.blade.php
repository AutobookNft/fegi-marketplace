<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PDF Rinascimentale - FlorenceEGI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap');
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-50">
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="mb-2 text-4xl font-bold text-amber-800" style="font-family: 'Playfair Display', serif;">
                🏛️ Test PDF Rinascimentale
            </h1>
            <p class="text-amber-700">Design B - Pergamena Rinascimentale per Certificati Padri Fondatori</p>
        </div>

        {{-- PDF Preview Info --}}
        <div class="mb-8 rounded-lg border border-amber-200 bg-white p-6 shadow-lg">
            <h2 class="mb-4 flex items-center text-xl font-semibold text-amber-800">
                <svg class="mr-2 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Caratteristiche del Design
            </h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div class="space-y-3">
                    <h3 class="font-semibold text-amber-800">🎨 Stile Rinascimentale</h3>
                    <ul class="space-y-1 text-sm text-amber-700">
                        <li>• ⚜️ Simboli fiorentini autentici</li>
                        <li>• 🏺 Bordi decorativi dorati</li>
                        <li>• 📜 Texture pergamena antica</li>
                        <li>• ❦ Ornamenti tipografici</li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h3 class="font-semibold text-amber-800">🖋️ Tipografia Elegante</h3>
                    <ul class="space-y-1 text-sm text-amber-700">
                        <li>• <strong>Cinzel</strong> - Titoli imperiali</li>
                        <li>• <strong>Playfair Display</strong> - Eleganza</li>
                        <li>• <strong>EB Garamond</strong> - Leggibilità</li>
                        <li>• Palette colori storici</li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h3 class="font-semibold text-amber-800">🔐 Autenticazione</h3>
                    <ul class="space-y-1 text-sm text-amber-700">
                        <li>• 🏆 Sigillo digitale circolare</li>
                        <li>• 🔗 Hash blockchain Algorand</li>
                        <li>• 📱 QR Code di verifica</li>
                        <li>• 🛡️ Watermark antifrode</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Test Data --}}
        <div class="mb-8 rounded-lg border border-amber-200 bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-amber-800">📊 Dati di Test</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <p class="text-sm text-amber-700">
                        <strong>Investitore:</strong> Marco Rossi
                    </p>
                    <p class="text-sm text-amber-700">
                        <strong>Collection:</strong> Padri Fondatori - Prima Emissione
                    </p>
                    <p class="text-sm text-amber-700">
                        <strong>Certificato:</strong> #001/2024
                    </p>
                    <p class="text-sm text-amber-700">
                        <strong>Valore:</strong> €250.00 EUR
                    </p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-amber-700">
                        <strong>Benefici:</strong> 3 privilegi esclusivi
                    </p>
                    <p class="text-sm text-amber-700">
                        <strong>Wallet:</strong> ABC...789 (mock)
                    </p>
                    <p class="text-sm text-amber-700">
                        <strong>ASA ID:</strong> 123456789
                    </p>
                    <p class="text-sm text-amber-700">
                        <strong>Status:</strong> Emesso
                    </p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="space-y-4 text-center">
            <div class="flex justify-center space-x-4">
                <a href="{{ route('founders.test.pdf') }}" target="_blank"
                    class="inline-flex items-center rounded-lg bg-amber-600 px-6 py-3 text-lg font-medium text-white transition-colors hover:bg-amber-700">
                    <svg class="mr-2 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    📜 Visualizza PDF Pergamena
                </a>

                <a href="{{ route('founders.test.menu') }}"
                    class="inline-flex items-center rounded-lg bg-gray-600 px-6 py-3 text-lg font-medium text-white transition-colors hover:bg-gray-700">
                    <svg class="mr-2 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Torna al Menu Test
                </a>
            </div>

            <p class="text-sm text-amber-600">
                Il PDF si aprirà in una nuova finestra per la visualizzazione completa
            </p>
        </div>

        {{-- Footer --}}
        <div class="mt-12 text-center text-amber-600">
            <p class="text-sm">
                FlorenceEGI - Nuovo Rinascimento Digitale Ecologico<br>
                Design B: Pergamena Rinascimentale con autenticazione blockchain
            </p>
        </div>
    </div>
</body>

</html>
