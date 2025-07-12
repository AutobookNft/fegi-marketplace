<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificato di Fondazione FlorenceEGI - {{ $certificate->investor_name }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="Certificato di Fondazione FlorenceEGI per {{ $certificate->investor_name }} - Blockchain Algorand Verificato">
    <meta name="keywords" content="FlorenceEGI, Certificato, Blockchain, Algorand, Rinascimento, Sostenibilità">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph -->
    <meta property="og:title" content="Certificato di Fondazione FlorenceEGI - {{ $certificate->investor_name }}">
    <meta property="og:description" content="Certificato blockchain verificato su Algorand">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('images/florenceegi-certificate-og.jpg') }}">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Crimson+Text:wght@400;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* Reset e Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Crimson Text', serif;
            background: linear-gradient(135deg, #2c1810 0%, #4a3424 25%, #6b4e37 50%, #8b6914 75%, #daa520 100%);
            min-height: 100vh;
            padding: 20px;
            line-height: 1.6;
        }

        /* Container principale */
        .certificate-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: fadeInScale 1s ease-out;
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Header di navigazione */
        .navigation-header {
            background: linear-gradient(135deg, #2c1810 0%, #4a3424 100%);
            color: white;
            padding: 20px;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 900;
            color: #daa520;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .nav-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .nav-button {
            background: linear-gradient(135deg, #8b6914 0%, #daa520 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* Certificato principale */
        .certificate-container {
            position: relative;
            background: linear-gradient(135deg, #faf5e6 0%, #f5f0e0 25%, #ede3d0 75%, #e6d7c0 100%);
            border: 8px solid #8b6914;
            margin: 20px;
            border-radius: 15px;
            overflow: hidden;
        }

        .inner-border {
            border: 3px solid #daa520;
            border-radius: 10px;
            margin: 15px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 3;
        }

        /* Ornamenti decorativi */
        .corner-ornament {
            position: absolute;
            font-size: 32px;
            color: #8b6914;
            font-weight: bold;
            z-index: 4;
            animation: sparkle 3s ease-in-out infinite;
        }

        @keyframes sparkle {

            0%,
            100% {
                opacity: 0.7;
            }

            50% {
                opacity: 1;
            }
        }

        .corner-ornament.top-left {
            top: 20px;
            left: 20px;
        }

        .corner-ornament.top-right {
            top: 20px;
            right: 20px;
        }

        .corner-ornament.bottom-left {
            bottom: 20px;
            left: 20px;
        }

        .corner-ornament.bottom-right {
            bottom: 20px;
            right: 20px;
        }

        /* Header certificato */
        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .company-logo {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 900;
            letter-spacing: 8px;
            margin-bottom: 10px;
            color: #8b6914;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            0% {
                text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
            }

            100% {
                text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3), 0 0 20px rgba(218, 165, 32, 0.3);
            }
        }

        .company-subtitle {
            font-size: 18px;
            font-style: italic;
            color: #704214;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .ornamental-divider {
            font-size: 28px;
            color: #8b6914;
            margin: 15px 0;
        }

        /* Titolo certificato */
        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 900;
            letter-spacing: 4px;
            margin: 30px 0;
            text-align: center;
            color: #8b6914;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: slideInTop 1s ease-out 0.5s both;
        }

        @keyframes slideInTop {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .certificate-number {
            font-size: 16px;
            color: #704214;
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
        }

        /* Contenuto principale */
        .main-content {
            text-align: center;
            margin: 40px 0;
        }

        .proclamation {
            font-size: 20px;
            color: #2c1810;
            margin-bottom: 25px;
            line-height: 1.8;
            font-weight: 600;
        }

        .investor-name {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 900;
            font-style: italic;
            margin: 35px 0;
            color: #8b6914;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 2px;
            animation: slideInLeft 1s ease-out 0.8s both;
        }

        @keyframes slideInLeft {
            0% {
                opacity: 0;
                transform: translateX(-50px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .founder-declaration {
            font-size: 22px;
            color: #2c1810;
            margin: 30px 0;
            line-height: 1.7;
            font-weight: 600;
        }

        .emphasis {
            font-size: 28px;
            font-weight: 900;
            color: #8b6914;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Sezione benefits */
        .benefits-section {
            background: linear-gradient(135deg, rgba(255, 248, 220, 0.9) 0%, rgba(255, 248, 220, 0.7) 100%);
            border: 3px solid #daa520;
            border-radius: 15px;
            padding: 30px;
            margin: 40px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideInLeft 1s ease-out 0.8s both;
        }

        .benefits-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 900;
            color: #8b6914;
            text-align: center;
            margin-bottom: 25px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            padding: 20px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            border-left: 4px solid #8b6914;
            transition: all 0.3s ease;
        }

        .benefit-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .benefit-icon {
            font-size: 24px;
            margin-right: 15px;
            flex-shrink: 0;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .benefit-content {
            flex: 1;
        }

        .benefit-name {
            font-weight: 700;
            color: #8b6914;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .benefit-description {
            color: #2c1810;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Sezione blockchain */
        .blockchain-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            border: 3px solid #daa520;
            border-radius: 15px;
            padding: 30px;
            margin: 40px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideInRight 1s ease-out 1s both;
        }

        @keyframes slideInLeft {
            0% {
                opacity: 0;
                transform: translateX(-50px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            0% {
                opacity: 0;
                transform: translateX(50px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .blockchain-title {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 900;
            color: #8b6914;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .blockchain-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .blockchain-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 2px solid #daa520;
            transition: all 0.3s ease;
        }

        .blockchain-item:hover {
            background: rgba(218, 165, 32, 0.1);
            transform: translateX(5px);
        }

        .blockchain-label {
            font-weight: 700;
            color: #704214;
            font-size: 16px;
        }

        .blockchain-value {
            font-family: 'Courier New', monospace;
            color: #2c1810;
            font-weight: 600;
            font-size: 15px;
            text-align: right;
            max-width: 60%;
            word-break: break-all;
        }

        .blockchain-highlight {
            color: #0066cc;
            font-weight: 900;
        }

        .blockchain-wallet {
            color: #cc6600;
            font-size: 13px;
        }

        /* Footer */
        .certificate-footer {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-top: 50px;
            align-items: end;
        }

        /* Sigillo digitale */
        .digital-seal {
            text-align: center;
        }

        .seal-circle {
            width: 120px;
            height: 120px;
            border: 5px solid #8b6914;
            border-radius: 50%;
            background: linear-gradient(45deg, #ffd700 0%, #daa520 50%, #8b6914 100%);
            display: inline-block;
            position: relative;
            margin-bottom: 15px;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .seal-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
            font-weight: 900;
            color: #2c1810;
            text-align: center;
            line-height: 1.2;
        }

        .seal-caption {
            font-size: 12px;
            color: #704214;
            font-weight: 700;
            font-style: italic;
        }

        /* Verifica QR */
        .verification-section {
            text-align: center;
        }

        .qr-container {
            display: inline-block;
            padding: 10px;
            background: white;
            border: 3px solid #8b6914;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .qr-code-image {
            width: 80px;
            height: 80px;
            border-radius: 5px;
            display: block;
        }

        .verification-text {
            font-size: 12px;
            color: #704214;
            font-weight: 600;
            line-height: 1.4;
        }

        .verification-url {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #8b6914;
            font-weight: 700;
            margin-top: 5px;
        }

        /* Firma */
        .signature-section {
            text-align: center;
        }

        .signature-line {
            border-top: 3px solid #8b6914;
            margin: 25px auto 15px;
            width: 200px;
        }

        .signature-name {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 900;
            color: #8b6914;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 14px;
            color: #704214;
            font-style: italic;
            font-weight: 700;
        }

        /* Filigrana */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 140px;
            color: rgba(218, 165, 32, 0.05);
            font-weight: 900;
            z-index: 1;
            pointer-events: none;
        }

        .decorative-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 2;
        }

        .flourish {
            position: absolute;
            font-size: 100px;
            color: rgba(139, 105, 20, 0.1);
            font-weight: 900;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .flourish.left {
            top: 200px;
            left: 20px;
            transform: rotate(-15deg);
        }

        .flourish.right {
            top: 200px;
            right: 20px;
            transform: rotate(15deg);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .navigation-header {
                flex-direction: column;
                gap: 15px;
            }

            .nav-actions {
                justify-content: center;
            }

            .certificate-container {
                margin: 10px;
            }

            .inner-border {
                padding: 20px;
            }

            .company-logo {
                font-size: 32px;
                letter-spacing: 4px;
            }

            .certificate-title {
                font-size: 28px;
                letter-spacing: 2px;
            }

            .investor-name {
                font-size: 36px;
            }

            .benefits-grid {
                grid-template-columns: 1fr;
            }

            .benefit-item {
                flex-direction: column;
                text-align: center;
            }

            .benefit-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .blockchain-grid {
                grid-template-columns: 1fr;
            }

            .blockchain-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .blockchain-value {
                max-width: 100%;
                text-align: left;
            }

            .certificate-footer {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .corner-ornament {
                font-size: 24px;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 12pt;
            }

            .certificate-wrapper {
                box-shadow: none;
                border-radius: 0;
            }

            .navigation-header {
                display: none;
            }

            .certificate-container {
                margin: 0;
                page-break-inside: avoid;
            }

            .nav-button {
                display: none;
            }

            .blockchain-section {
                page-break-inside: avoid;
            }

            .certificate-footer {
                page-break-inside: avoid;
            }

            /* Force single page */
            @page {
                size: A4;
                margin: 1cm;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>
    <div class="certificate-wrapper">
        <!-- Header di navigazione -->
        <div class="navigation-header">
            <div class="nav-logo">FLORENCEEGI</div>
            <div class="nav-actions">
                <button class="nav-button" onclick="window.print()">🖨️ Stampa Browser</button>
                <button class="nav-button" onclick="downloadLegalPdf()">📋 Scarica PDF Legale</button>
                <button class="nav-button"
                    onclick="navigator.share ? navigator.share({title: 'Certificato FlorenceEGI', url: window.location.href}) : copyToClipboard(window.location.href)">📤
                    Condividi</button>
                <a href="https://florenceegi.it" class="nav-button">🏛️ Visita FlorenceEGI</a>
            </div>
        </div>

        <!-- Certificato principale -->
        <div class="certificate-container">
            <div class="inner-border">
                <div class="watermark">AUTENTICO</div>

                <div class="decorative-elements">
                    <div class="flourish left">❦</div>
                    <div class="flourish right">❦</div>
                </div>

                <!-- Ornamenti agli angoli -->
                <div class="corner-ornament top-left">❦</div>
                <div class="corner-ornament top-right">❦</div>
                <div class="corner-ornament bottom-left">❦</div>
                <div class="corner-ornament bottom-right">❦</div>

                <!-- Header -->
                <div class="certificate-header">
                    <div class="company-logo">FLORENCEEGI</div>
                    <div class="company-subtitle">FlorenceEGI – Il nuovo Rinascimento Ecologico Digitale</div>
                    <div class="ornamental-divider">⚜ ❦ ⚜</div>
                </div>

                <!-- Titolo principale -->
                <div class="certificate-title">CERTIFICATO DI FONDAZIONE</div>
                <div class="certificate-number">
                    Anno Domini {{ date('Y') }} • N. {{ str_pad($certificate->id, 4, '0', STR_PAD_LEFT) }}
                </div>

                <!-- Contenuto principale -->
                <div class="main-content">
                    <div class="proclamation">
                        Nel nome del Nuovo Rinascimento e della rinascita tecnologica,<br>
                        si attesta solennemente che il portatore di questo documento è riconosciuto come
                    </div>

                    <div class="investor-name">{{ $certificate->investor_name ?? 'N/A' }}</div>

                    <div class="founder-declaration">
                        <span class="emphasis">PADRE FONDATORE</span><br>
                        del progetto rivoluzionario FlorenceEGI, che unisce la maestria artistica fiorentina<br>
                        con le tecnologie Blockchain più avanzate, creando un ecosistema digitale<br>
                        che onora la tradizione mentre forgia il futuro della sostenibilità.
                    </div>
                </div>

                <!-- Sezione Benefits -->
                @if (
                    $certificate->collection &&
                        $certificate->collection->certificateBenefits &&
                        $certificate->collection->certificateBenefits->count() > 0)
                    <div class="benefits-section">
                        <div class="benefits-title">⚜ Privilegi e Benefici Esclusivi ⚜</div>
                        <div class="benefits-grid">
                            @foreach ($certificate->collection->certificateBenefits as $benefit)
                                <div class="benefit-item">
                                    <div class="benefit-icon">
                                        @switch($benefit->icon ?? 'star')
                                            @case('gem')
                                                💎
                                            @break

                                            @case('zap')
                                                ⚡
                                            @break

                                            @case('star')
                                                ⭐
                                            @break

                                            @case('crown')
                                                👑
                                            @break

                                            @case('shield')
                                                🛡️
                                            @break

                                            @default
                                                ⚜️
                                        @endswitch
                                    </div>
                                    <div class="benefit-content">
                                        <div class="benefit-name">{{ $benefit->name }}</div>
                                        <div class="benefit-description">{{ $benefit->description }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Sezione Blockchain -->
                <div class="blockchain-section">
                    <div class="blockchain-title">⚜ Registro Blockchain Algorand ⚜</div>

                    <div class="blockchain-grid">
                        <div class="blockchain-item">
                            <span class="blockchain-label">Certificato N.:</span>
                            <span class="blockchain-value">{{ str_pad($certificate->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <div class="blockchain-item">
                            <span class="blockchain-label">Data Emissione:</span>
                            <span class="blockchain-value">{{ $certificate->created_at->format('d F Y') }}</span>
                        </div>

                        <div class="blockchain-item">
                            <span class="blockchain-label">Collection:</span>
                            <span
                                class="blockchain-value">{{ $certificate->collection->name ?? 'Padri Fondatori' }}</span>
                        </div>

                        <div class="blockchain-item">
                            <span class="blockchain-label">Valore Nominale:</span>
                            <span
                                class="blockchain-value">€{{ number_format($certificate->base_price ?? 250, 2, ',', '.') }}</span>
                        </div>

                        @if ($certificate->asa_id)
                            <div class="blockchain-item">
                                <span class="blockchain-label">🪙 <strong>Token ASA ID:</strong></span>
                                <span class="blockchain-value blockchain-highlight">{{ $certificate->asa_id }}</span>
                            </div>
                        @endif

                        @if ($certificate->tx_id)
                            <div class="blockchain-item">
                                <span class="blockchain-label">🔗 <strong>Transaction ID:</strong></span>
                                <span class="blockchain-value blockchain-highlight">{{ $certificate->tx_id }}</span>
                            </div>
                        @endif

                        <div class="blockchain-item">
                            <span class="blockchain-label">💰 <strong>Wallet Destinazione:</strong></span>
                            <span class="blockchain-value blockchain-wallet">
                                @if ($certificate->investor_wallet)
                                    {{ substr($certificate->investor_wallet, 0, 25) }}...<br>
                                    <small>(Wallet Investitore)</small>
                                @else
                                    Treasury Wallet<br>
                                    <small>(In attesa di trasferimento)</small>
                                @endif
                            </span>
                        </div>

                        @if ($certificate->minted_at)
                            <div class="blockchain-item">
                                <span class="blockchain-label">⏰ <strong>Data Minting:</strong></span>
                                <span
                                    class="blockchain-value">{{ $certificate->minted_at->format('d F Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="certificate-footer">
                    <div class="digital-seal">
                        <div class="seal-circle">
                            <div class="seal-text">
                                FLORENCEEGI<br>
                                CERTIFICATO<br>
                                AUTENTICO<br>
                                {{ date('Y') }}
                            </div>
                        </div>
                        <div class="seal-caption">Sigillo Digitale Certificato</div>
                    </div>

                    <div class="verification-section">
                        <div class="qr-container">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(url()->current()) }}"
                                alt="QR Code per certificato" class="qr-code-image" loading="lazy">
                        </div>
                        <div class="verification-text">
                            Certificazione Blockchain Verificabile<br>
                            Scansiona per autenticità garantita
                        </div>
                        <div class="verification-url">{{ url()->current() }}</div>
                    </div>

                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <div class="signature-name">Fabio Cherici</div>
                        <div class="signature-title">Direttore Generale</div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <div class="ornamental-divider">❦ ⚜ ❦</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Funzione per copiare il link
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copiato negli appunti!');
            });
        }



        // Funzione per scaricare PDF legale
        function downloadLegalPdf() {
            // Mostra loading
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '⏳ Generando PDF...';
            button.disabled = true;

            // Costruisce URL per il download PDF legale
            const currentUrl = window.location.href;
            const pdfUrl = currentUrl + '/legal-pdf';

            // Crea link temporaneo per download
            const link = document.createElement('a');
            link.href = pdfUrl;
            link.download = 'certificato-legale-florenceegi.pdf';
            link.style.display = 'none';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Ripristina pulsante dopo 2 secondi
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
            }, 2000);
        }

        // Animazione di ingresso
        document.addEventListener('DOMContentLoaded', function() {
            const certificate = document.querySelector('.certificate-container');
            certificate.style.opacity = '0';
            certificate.style.transform = 'translateY(20px)';

            setTimeout(() => {
                certificate.style.transition = 'all 1s ease-out';
                certificate.style.opacity = '1';
                certificate.style.transform = 'translateY(0)';
            }, 200);
        });
    </script>
</body>

</html>
