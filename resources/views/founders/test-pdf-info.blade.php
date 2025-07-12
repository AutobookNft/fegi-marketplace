<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PDF Rinascimentale - FlorenceEGI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap');
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-50">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8 text-center">
            <h1 class="mb-2 text-4xl font-bold text-amber-800" style="font-family: 'Playfair Display', serif;">
                🏛️ Test PDF Rinascimentale
            </h1>
            <p class="text-amber-700">Design B - Pergamena Rinascimentale per Certificati Padri Fondatori</p>
        </div>

        <div class="mb-8 rounded-lg border border-amber-200 bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-amber-800">
                📜 Caratteristiche del Design
            </h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div>
                    <h3 class="font-semibold text-amber-800">🎨 Stile Rinascimentale</h3>
                    <ul class="mt-2 space-y-1 text-sm text-amber-700">
                        <li>• ⚜️ Simboli fiorentini</li>
                        <li>• 🏺 Bordi decorativi dorati</li>
                        <li>• 📜 Texture pergamena</li>
                        <li>• ❦ Ornamenti tipografici</li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-amber-800">🖋️ Tipografia Elegante</h3>
                    <ul class="mt-2 space-y-1 text-sm text-amber-700">
                        <li>• <strong>Cinzel</strong> - Titoli</li>
                        <li>• <strong>Playfair Display</strong> - Eleganza</li>
                        <li>• <strong>EB Garamond</strong> - Testo</li>
                        <li>• Palette colori storici</li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-amber-800">🔐 Autenticazione</h3>
                    <ul class="mt-2 space-y-1 text-sm text-amber-700">
                        <li>• 🏆 Sigillo digitale</li>
                        <li>• 🔗 Hash blockchain</li>
                        <li>• 📱 QR Code verifica</li>
                        <li>• 🛡️ Watermark antifrode</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center">
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
        </div>
    </div>
</body>

</html>
