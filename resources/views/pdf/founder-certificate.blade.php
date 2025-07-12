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
            line-height: 1.3;
            background: #faf8f2;
            padding: 0;
            margin: 0;
        }

        .certificate-container {
            width: 210mm;
            height: 297mm;
            position: relative;
            background: linear-gradient(to bottom, #fefcf7 0%, #faf8f2 100%);
            border: 12px solid #8b6914;
            padding: 20px;
            margin: 0;
        }

        .inner-border {
            border: 2px solid #daa520;
            padding: 15px;
            height: 100%;
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
        }

        .corner-ornament {
            position: absolute;
            font-size: 45px;
            color: #8b6914;
            font-weight: bold;
        }

        .corner-ornament.top-left {
            top: 15px;
            left: 15px;
        }

        .corner-ornament.top-right {
            top: 15px;
            right: 15px;
        }

        .corner-ornament.bottom-left {
            bottom: 15px;
            left: 15px;
        }

        .corner-ornament.bottom-right {
            bottom: 15px;
            right: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .company-logo {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 6px;
            margin-bottom: 8px;
            color: #8b6914;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .company-subtitle {
            font-size: 13px;
            font-style: italic;
            color: #704214;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .ornamental-divider {
            font-size: 20px;
            color: #8b6914;
            margin: 8px 0;
        }

        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 15px 0 10px 0;
            text-align: center;
            color: #8b6914;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .certificate-number {
            font-size: 12px;
            color: #704214;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .main-content {
            text-align: center;
            margin: 15px 0;
            flex-shrink: 0;
        }

        .proclamation {
            font-size: 14px;
            color: #2c1810;
            margin-bottom: 15px;
            line-height: 1.4;
            font-weight: 500;
        }

        .investor-name {
            font-size: 32px;
            font-weight: bold;
            font-style: italic;
            margin: 15px 0;
            color: #8b6914;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .founder-declaration {
            font-size: 15px;
            color: #2c1810;
            margin: 12px 0;
            line-height: 1.4;
            font-weight: 500;
        }

        .emphasis {
            font-size: 18px;
            font-weight: bold;
            color: #8b6914;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .registry-section {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid #daa520;
            border-radius: 8px;
            padding: 12px;
            margin: 15px 0;
            flex-shrink: 0;
        }

        .registry-title {
            font-size: 16px;
            font-weight: bold;
            color: #8b6914;
            text-align: center;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .registry-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .registry-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #daa520;
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
            border: 2px solid #daa520;
            border-radius: 8px;
            padding: 12px;
            margin: 15px 0;
            flex-shrink: 0;
        }

        .benefits-title {
            font-size: 14px;
            font-weight: bold;
            color: #8b6914;
            text-align: center;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .benefits-list li {
            font-size: 11px;
            color: #2c1810;
            margin: 6px 0;
            padding-left: 20px;
            position: relative;
            line-height: 1.3;
            font-weight: 500;
        }

        .benefits-list li::before {
            content: "⚜";
            position: absolute;
            left: 0;
            color: #8b6914;
            font-size: 13px;
            font-weight: bold;
        }

        .footer {
            display: table;
            width: 100%;
            margin-top: auto;
            padding-top: 10px;
        }

        .footer-left {
            display: table-cell;
            width: 130px;
            text-align: center;
            vertical-align: bottom;
        }

        .footer-right {
            display: table-cell;
            width: 130px;
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
            width: 70px;
            height: 70px;
            border: 3px solid #8b6914;
            border-radius: 50%;
            background: linear-gradient(to bottom, #ffd700 0%, #daa520 50%, #8b6914 100%);
            display: inline-block;
            position: relative;
            margin-bottom: 6px;
        }

        .seal-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 7px;
            font-weight: bold;
            color: #2c1810;
            text-align: center;
            line-height: 1.1;
        }

        .seal-caption {
            font-size: 8px;
            color: #704214;
            font-weight: bold;
            font-style: italic;
        }

        .signature-section {
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #8b6914;
            margin: 15px auto 8px;
            width: 100px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #8b6914;
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 9px;
            color: #704214;
            font-style: italic;
            font-weight: bold;
        }

        .verification-section {
            text-align: center;
        }

        .qr-code {
            width: 45px;
            height: 45px;
            border: 2px solid #8b6914;
            border-radius: 4px;
            background: white;
            display: inline-block;
            margin: 0 auto 6px;
            position: relative;
        }

        .qr-code::after {
            content: 'QR';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 8px;
            font-weight: bold;
            color: #8b6914;
        }

        .verification-text {
            font-size: 8px;
            color: #704214;
            margin-top: 4px;
            font-weight: 500;
            line-height: 1.2;
        }

        .verification-url {
            font-family: monospace;
            font-size: 7px;
            color: #8b6914;
            margin-top: 2px;
            font-weight: bold;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(218, 165, 32, 0.03);
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
            font-size: 50px;
            color: rgba(139, 105, 20, 0.08);
            font-weight: bold;
        }

        .flourish.left {
            top: 120px;
            left: 5px;
            transform: rotate(-15deg);
        }

        .flourish.right {
            top: 120px;
            right: 5px;
            transform: rotate(15deg);
        }

        .final-ornament {
            text-align: center;
            margin-top: 8px;
            font-size: 16px;
            color: #8b6914;
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
                {{ str_pad($certificate->id ?? 1, 4, '0', STR_PAD_LEFT) }}</div>

            <!-- Contenuto principale -->
            <div class="main-content">
                <div class="proclamation">
                    Nel nome del Nuovo Rinascimento e della rinascita tecnologica,<br>
                    si attesta solennemente che il portatore di questo documento è riconosciuto come
                </div>

                <div class="investor-name">{{ $certificate->investor_name ?? 'Marco Rossi' }}</div>

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
                        <td>{{ str_pad($certificate->id ?? 1, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td>Data Emissione:</td>
                        <td>{{ ($certificate->issued_at ?? now())->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Collection:</td>
                        <td>{{ $certificate->collection->name ?? 'Padri Fondatori' }}</td>
                    </tr>
                    <tr>
                        <td>Valore Nominale:</td>
                        <td>€{{ number_format($certificate->base_price ?? 250, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Sezione Privilegi e Benefici -->
            @if (isset($certificate->collection) &&
                    isset($certificate->collection->activeBenefits) &&
                    $certificate->collection->activeBenefits->count() > 0)
                <div class="benefits-section">
                    <div class="benefits-title">⚜ Privilegi e Benefici Esclusivi ⚜</div>
                    <ul class="benefits-list">
                        @foreach ($certificate->collection->activeBenefits->take(3) as $benefit)
                            <li>{{ $benefit->title }} - {{ Str::limit($benefit->description, 60) }}</li>
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
                        <div class="seal-caption">Sigillo Digitale</div>
                    </div>
                </div>

                <div class="footer-center">
                    <div class="verification-section">
                        @if (isset($qrCodeImage))
                            <img src="{{ $qrCodeImage }}" alt="QR Code"
                                style="width: 45px; height: 45px; border: 2px solid #8b6914; border-radius: 4px; background: white;">
                        @else
                            <div class="qr-code"></div>
                        @endif
                        <div class="verification-text">
                            Certificazione Blockchain<br>
                            Scansiona per verifica
                        </div>
                        <div class="verification-url">florenceegi.it/{{ $certificate->id ?? '1' }}</div>
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

            <div class="final-ornament">❦ ⚜ ❦</div>
        </div>
    </div>
</body>

</html>
