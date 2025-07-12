<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Certificato di Fondazione FlorenceEGI</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: serif;
            color: #2c1810;
            line-height: 1.4;
            background: #faf8f2;
            padding: 0;
            margin: 0;
        }

        .certificate-container {
            width: 210mm;
            height: 297mm;
            position: relative;
            background: linear-gradient(to bottom, #fefcf7 0%, #faf8f2 100%);
            border: 15px solid #8b6914;
            padding: 30px;
            margin: 0;
        }

        .inner-border {
            border: 3px solid #daa520;
            padding: 25px;
            height: 100%;
            position: relative;
            background: rgba(255, 255, 255, 0.1);
        }

        .corner-ornament {
            position: absolute;
            font-size: 60px;
            color: #8b6914;
            font-weight: bold;
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

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .company-logo {
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 8px;
            margin-bottom: 10px;
            color: #8b6914;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
        }

        .company-subtitle {
            font-size: 16px;
            font-style: italic;
            color: #704214;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .ornamental-divider {
            font-size: 28px;
            color: #8b6914;
            margin: 10px 0;
        }

        .certificate-title {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 4px;
            margin: 20px 0;
            text-align: center;
            color: #8b6914;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .certificate-number {
            font-size: 14px;
            color: #704214;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .main-content {
            text-align: center;
            margin: 25px 0;
        }

        .proclamation {
            font-size: 18px;
            color: #2c1810;
            margin-bottom: 20px;
            line-height: 1.6;
            font-weight: 500;
        }

        .investor-name {
            font-size: 48px;
            font-weight: bold;
            font-style: italic;
            margin: 25px 0;
            color: #8b6914;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 2px;
        }

        .founder-declaration {
            font-size: 20px;
            color: #2c1810;
            margin: 20px 0;
            line-height: 1.6;
            font-weight: 500;
        }

        .emphasis {
            font-size: 24px;
            font-weight: bold;
            color: #8b6914;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .registry-section {
            background: rgba(255, 255, 255, 0.8);
            border: 3px solid #daa520;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
        }

        .registry-title {
            font-size: 20px;
            font-weight: bold;
            color: #8b6914;
            text-align: center;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .registry-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .registry-table td {
            padding: 10px 12px;
            border-bottom: 2px solid #daa520;
            color: #2c1810;
            font-weight: 500;
        }

        .registry-table td:first-child {
            font-weight: bold;
            color: #704214;
            width: 40%;
        }

        .benefits-section {
            background: rgba(255, 255, 255, 0.6);
            border: 3px solid #daa520;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .benefits-title {
            font-size: 18px;
            font-weight: bold;
            color: #8b6914;
            text-align: center;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .benefits-list li {
            font-size: 14px;
            color: #2c1810;
            margin: 10px 0;
            padding-left: 25px;
            position: relative;
            line-height: 1.5;
            font-weight: 500;
        }

        .benefits-list li::before {
            content: "⚜";
            position: absolute;
            left: 0;
            color: #8b6914;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            display: table;
            width: 100%;
            margin-top: 30px;
        }

        .footer-left {
            display: table-cell;
            width: 200px;
            text-align: center;
            vertical-align: bottom;
        }

        .footer-right {
            display: table-cell;
            width: 200px;
            text-align: center;
            vertical-align: bottom;
        }

        .footer-center {
            display: table-cell;
            text-align: center;
            vertical-align: bottom;
        }

        .digital-seal {
            text-align: center;
        }

        .seal-circle {
            width: 100px;
            height: 100px;
            border: 4px solid #8b6914;
            border-radius: 50%;
            background: linear-gradient(to bottom, #ffd700 0%, #daa520 50%, #8b6914 100%);
            display: inline-block;
            position: relative;
            margin-bottom: 10px;
        }

        .seal-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 9px;
            font-weight: bold;
            color: #2c1810;
            text-align: center;
            line-height: 1.2;
        }

        .seal-caption {
            font-size: 11px;
            color: #704214;
            font-weight: bold;
            font-style: italic;
        }

        .signature-section {
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #8b6914;
            margin: 20px auto 10px;
            width: 150px;
        }

        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #8b6914;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 12px;
            color: #704214;
            font-style: italic;
            font-weight: bold;
        }

        .verification-section {
            text-align: center;
            margin-top: 20px;
        }

        .qr-code {
            width: 60px;
            height: 60px;
            border: 2px solid #8b6914;
            border-radius: 5px;
            background: white;
            display: inline-block;
            margin: 0 auto 8px;
            position: relative;
        }

        .qr-code::after {
            content: 'QR';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
            font-weight: bold;
            color: #8b6914;
        }

        .verification-text {
            font-size: 10px;
            color: #704214;
            margin-top: 5px;
            font-weight: 500;
        }

        .verification-url {
            font-family: monospace;
            font-size: 8px;
            color: #8b6914;
            margin-top: 3px;
            font-weight: bold;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(218, 165, 32, 0.05);
            font-weight: bold;
            z-index: 1;
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
            font-size: 80px;
            color: rgba(139, 105, 20, 0.15);
            font-weight: bold;
        }

        .flourish.left {
            top: 150px;
            left: 10px;
            transform: rotate(-15deg);
        }

        .flourish.right {
            top: 150px;
            right: 10px;
            transform: rotate(15deg);
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="inner-border">
            <div class="watermark">AUTENTICO</div>

            <div class="decorative-elements">
                <div class="flourish left">❦</div>
                <div class="flourish right">❦</div>
            </div>

            <!-- Ornamenti decorativi agli angoli -->
            <div class="corner-ornament top-left">❦</div>
            <div class="corner-ornament top-right">❦</div>
            <div class="corner-ornament bottom-left">❦</div>
            <div class="corner-ornament bottom-right">❦</div>

            <!-- Header -->
            <div class="header">
                <div class="company-logo">FLORENCEEGI</div>
                <div class="company-subtitle">FlorenceEGI – Il nuovo Rinascimento Ecologico Digitale</div>
                <div class="ornamental-divider">⚜ ❦ ⚜</div>
            </div>

            <!-- Titolo principale -->
            <div class="certificate-title">CERTIFICATO DI FONDAZIONE</div>
            <div class="certificate-number">Anno Domini {{ date('Y') }} • N.
                {{ str_pad($certificate->id, 4, '0', STR_PAD_LEFT) }}</div>

            <!-- Contenuto principale -->
            <div class="main-content">
                <div class="proclamation">
                    Nel nome del Nuovo Rinascimento e della rinascita tecnologica,<br>
                    si attesta solennemente che il portatore di questo documento è riconosciuto come
                </div>

                <div class="investor-name">{{ $certificate->investor_name }}</div>

                <div class="founder-declaration">
                    <span class="emphasis">PADRE FONDATORE</span><br>
                    del progetto rivoluzionario FlorenceEGI, che unisce la maestria artistica fiorentina<br>
                    con le tecnologie Blockchain più avanzate, creando un ecosistema digitale<br>
                    che onora la tradizione mentre forgia il futuro della sostenibilità.
                </div>
            </div>

            <!-- Sezione Registro Blockchain -->
            <div class="registry-section">
                <div class="registry-title">⚜ Registro Blockchain Algorand ⚜</div>
                <table class="registry-table">
                    <tr>
                        <td>Certificato N.:</td>
                        <td>{{ str_pad($certificate->id, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td>Data Emissione:</td>
                        <td>{{ $certificate->created_at->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Collection:</td>
                        <td>{{ $collection->name ?? 'Padri Fondatori' }}</td>
                    </tr>
                    <tr>
                        <td>Valore Nominale:</td>
                        <td>€{{ number_format($certificate->base_price ?? 250, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Sezione Privilegi e Benefici -->
            @if ($benefits->count() > 0)
                <div class="benefits-section">
                    <div class="benefits-title">⚜ Privilegi e Benefici Esclusivi ⚜</div>
                    <ul class="benefits-list">
                        @foreach ($benefits as $benefit)
                            <li>{{ $benefit->name }} - {{ $benefit->description }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Footer -->
            <div class="footer">
                <div class="footer-left">
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
                </div>

                <div class="footer-center">
                    <div class="verification-section">
                        @if (isset($qrCodeImage))
                            <img src="{{ $qrCodeImage }}" alt="QR Code"
                                style="width: 60px; height: 60px; border: 2px solid #8b6914; border-radius: 5px; background: white; display: inline-block; margin: 0 auto 8px;">
                        @else
                            <div class="qr-code"></div>
                        @endif
                        <div class="verification-text">
                            Certificazione Blockchain Verificabile<br>
                            Scansiona per autenticità garantita
                        </div>
                        <div class="verification-url">{{ $verificationUrl }}</div>
                    </div>
                </div>

                <div class="footer-right">
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <div class="signature-name">Fabio Cherici</div>
                        <div class="signature-title">Direttore Generale</div>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 15px;">
                <div class="ornamental-divider">❦ ⚜ ❦</div>
            </div>
        </div>
    </div>
</body>

</html>
