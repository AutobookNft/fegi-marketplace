<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificato di Fondazione FlorenceEGI - {{ $certificate->investor_name }}</title>

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
            padding: 0;
            margin: 0;
            line-height: 1.6;
        }

        /* Container principale ottimizzato per PNG - Full Size */
        .certificate-wrapper {
            width: 100%;
            height: 100vh;
            background: white;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Certificato principale - Full Size */
        .certificate-container {
            position: relative;
            background: linear-gradient(135deg, #faf5e6 0%, #f5f0e0 25%, #ede3d0 75%, #e6d7c0 100%);
            border: 8px solid #8b6914;
            overflow: hidden;
            width: 100%;
            height: 100vh;
        }

        .inner-border {
            border: 3px solid #daa520;
            margin: 15px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 3;
            height: calc(100vh - 30px);
            box-sizing: border-box;
            overflow-y: auto;
        }

        /* Ornamenti decorativi */
        .corner-ornament {
            position: absolute;
            font-size: 32px;
            color: #8b6914;
            font-weight: bold;
            z-index: 4;
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
        }

        .benefit-icon {
            font-size: 24px;
            margin-right: 15px;
            flex-shrink: 0;
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
    </style>
</head>

<body>
    <div class="certificate-wrapper">
        <!-- Certificato principale (SENZA header di navigazione) -->
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
                        <div class="verification-url">https://scan.florenceegi.it/{{ $certificate->id }}</div>
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
</body>

</html>
