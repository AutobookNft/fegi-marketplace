{{--
    @Oracode PDF Certificate Template for FlorenceEGI Founders
    🎯 Purpose: Branded PDF certificate with Rinascimento styling for printing and digital use
    🧱 Core Logic: Professional certificate layout, blockchain verification, brand compliance
    🛡️ Security: QR codes for verification, certificate hash, tamper-evident design

    @package resources/views/pdf
    @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
    @version 1.0.0 (FlorenceEGI - PDF Certificate Template)
    @date 2025-07-05
    @purpose Professional PDF certificate for Padre Fondatore with complete branding
--}}

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificato Padre Fondatore #{{ $certificate_number }} - FlorenceEGI</title>
    <style>
        /* FlorenceEGI Brand Fonts and Colors */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Source+Sans+Pro:wght@300;400;500;600;700&display=swap');

        :root {
            --oro-fiorentino: #D4A574;
            --verde-rinascita: #2D5016;
            --blu-algoritmo: #1B365D;
            --grigio-pietra: #6B6B6B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Source Sans Pro', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #ffffff 0%, #fefcf8 50%, #fef7e8 100%);
        }

        .certificate-container {
            width: 210mm;
            height: 297mm; /* A4 */
            padding: 20mm;
            margin: 0 auto;
            position: relative;
            background: #ffffff;
            box-shadow: 0 0 20mm rgba(212, 165, 116, 0.1);
        }

        /* Decorative Border - Renaissance Style */
        .certificate-border {
            position: absolute;
            top: 15mm;
            left: 15mm;
            right: 15mm;
            bottom: 15mm;
            border: 3px solid var(--oro-fiorentino);
            border-radius: 8mm;
            background: linear-gradient(135deg, #ffffff 0%, #fefcf8 100%);
        }

        .certificate-border::before {
            content: '';
            position: absolute;
            top: 3mm;
            left: 3mm;
            right: 3mm;
            bottom: 3mm;
            border: 1px solid rgba(212, 165, 116, 0.3);
            border-radius: 6mm;
        }

        /* Header Section */
        .certificate-header {
            text-align: center;
            margin: 15mm 0 10mm;
            position: relative;
            z-index: 2;
        }

        .logo-section {
            margin-bottom: 8mm;
        }

        .company-name {
            font-family: 'Playfair Display', serif;
            font-size: 28pt;
            font-weight: 700;
            color: var(--oro-fiorentino);
            letter-spacing: 2px;
            margin-bottom: 3mm;
            text-shadow: 1px 1px 2px rgba(212, 165, 116, 0.3);
        }

        .company-tagline {
            font-size: 12pt;
            color: var(--verde-rinascita);
            font-weight: 500;
            letter-spacing: 1px;
            margin-bottom: 8mm;
        }

        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 24pt;
            font-weight: 600;
            color: var(--blu-algoritmo);
            margin-bottom: 5mm;
            letter-spacing: 1px;
        }

        .certificate-subtitle {
            font-size: 14pt;
            color: var(--grigio-pietra);
            font-weight: 400;
            margin-bottom: 10mm;
        }

        /* Certificate Content */
        .certificate-content {
            text-align: center;
            margin: 10mm 0;
            position: relative;
            z-index: 2;
        }

        .certificate-text {
            font-size: 14pt;
            line-height: 1.8;
            color: #333;
            margin-bottom: 8mm;
        }

        .investor-name {
            font-family: 'Playfair Display', serif;
            font-size: 22pt;
            font-weight: 600;
            color: var(--oro-fiorentino);
            margin: 8mm 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 2px solid var(--oro-fiorentino);
            padding-bottom: 3mm;
            display: inline-block;
            min-width: 80mm;
        }

        .certificate-details {
            margin: 10mm 0;
            text-align: left;
            max-width: 150mm;
            margin-left: auto;
            margin-right: auto;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3mm;
            padding: 2mm 0;
            border-bottom: 1px dotted rgba(212, 165, 116, 0.3);
        }

        .detail-label {
            font-weight: 600;
            color: var(--blu-algoritmo);
            font-size: 11pt;
        }

        .detail-value {
            color: #333;
            font-size: 11pt;
            font-family: 'JetBrains Mono', monospace;
        }

        /* Blockchain Section */
        .blockchain-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 2px solid var(--blu-algoritmo);
            border-radius: 6mm;
            padding: 6mm;
            margin: 8mm 0;
            position: relative;
        }

        .blockchain-title {
            font-family: 'Playfair Display', serif;
            font-size: 14pt;
            font-weight: 600;
            color: var(--blu-algoritmo);
            text-align: center;
            margin-bottom: 4mm;
        }

        .blockchain-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3mm;
            margin-bottom: 4mm;
        }

        .blockchain-item {
            text-align: center;
        }

        .blockchain-label {
            font-size: 9pt;
            color: var(--grigio-pietra);
            font-weight: 500;
            margin-bottom: 1mm;
        }

        .blockchain-value {
            font-size: 10pt;
            color: var(--blu-algoritmo);
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
            word-break: break-all;
        }

        /* QR Code Section */
        .qr-section {
            position: absolute;
            bottom: 25mm;
            right: 25mm;
            text-align: center;
            z-index: 3;
        }

        .qr-code {
            width: 20mm;
            height: 20mm;
            border: 2px solid var(--oro-fiorentino);
            border-radius: 2mm;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: var(--grigio-pietra);
            margin-bottom: 2mm;
        }

        .qr-label {
            font-size: 8pt;
            color: var(--grigio-pietra);
            font-weight: 500;
        }

        /* Certificate Footer */
        .certificate-footer {
            position: absolute;
            bottom: 30mm;
            left: 25mm;
            right: 35mm;
            text-align: center;
            z-index: 2;
        }

        .issue-date {
            font-size: 12pt;
            color: var(--verde-rinascita);
            font-weight: 500;
            margin-bottom: 3mm;
        }

        .certificate-hash {
            font-size: 9pt;
            color: var(--grigio-pietra);
            font-family: 'JetBrains Mono', monospace;
            margin-bottom: 3mm;
        }

        .authority-signature {
            margin-top: 5mm;
            text-align: right;
        }

        .signature-line {
            border-bottom: 2px solid var(--oro-fiorentino);
            width: 50mm;
            margin: 3mm 0 2mm auto;
        }

        .signature-label {
            font-size: 10pt;
            color: var(--blu-algoritmo);
            font-weight: 500;
        }

        /* Decorative Elements */
        .decorative-element {
            position: absolute;
            opacity: 0.1;
            z-index: 1;
        }

        .decorative-element.top-left {
            top: 25mm;
            left: 25mm;
            width: 15mm;
            height: 15mm;
            background: radial-gradient(circle, var(--oro-fiorentino) 0%, transparent 70%);
            border-radius: 50%;
        }

        .decorative-element.bottom-right {
            bottom: 45mm;
            right: 45mm;
            width: 20mm;
            height: 20mm;
            background: radial-gradient(circle, var(--verde-rinascita) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-family: 'Playfair Display', serif;
            font-size: 48pt;
            color: rgba(212, 165, 116, 0.05);
            font-weight: 700;
            z-index: 1;
            pointer-events: none;
        }

        /* Print Optimizations */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            .certificate-container {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 15mm;
            }
        }

        /* Responsive adjustments for smaller screens */
        @media screen and (max-width: 800px) {
            .certificate-container {
                width: 100%;
                height: auto;
                min-height: 100vh;
                padding: 5mm;
            }

            .company-name {
                font-size: 24pt;
            }

            .certificate-title {
                font-size: 20pt;
            }

            .investor-name {
                font-size: 18pt;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Decorative Border -->
        <div class="certificate-border"></div>

        <!-- Decorative Elements -->
        <div class="decorative-element top-left"></div>
        <div class="decorative-element bottom-right"></div>

        <!-- Watermark -->
        <div class="watermark">FLORENCEEGI</div>

        <!-- Certificate Header -->
        <div class="certificate-header">
            <div class="logo-section">
                <div class="company-name">FlorenceEGI</div>
                <div class="company-tagline">{{ $brand['tagline'] }}</div>
            </div>

            <div class="certificate-title">CERTIFICATO PADRE FONDATORE</div>
            <div class="certificate-subtitle">{{ $round_description }}</div>
        </div>

        <!-- Certificate Content -->
        <div class="certificate-content">
            <div class="certificate-text">
                Questo certificato attesta che
            </div>

            <div class="investor-name">{{ $investor_name }}</div>

            <div class="certificate-text">
                è ufficialmente riconosciuto come <strong>Padre Fondatore</strong> del Nuovo Rinascimento Ecologico Digitale,
                avendo contribuito alla realizzazione del progetto FlorenceEGI con l'acquisizione del certificato numero
                <strong>#{{ $certificate_number }}</strong> di {{ $total_certificates }} emessi.
            </div>

            <!-- Certificate Details -->
            <div class="certificate-details">
                <div class="detail-row">
                    <span class="detail-label">Certificato N°:</span>
                    <span class="detail-value">#{{ $certificate_number }}/{{ $total_certificates }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Round:</span>
                    <span class="detail-value">{{ $round_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Valore:</span>
                    <span class="detail-value">€{{ $certificate_price }} {{ $currency }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Data Emissione:</span>
                    <span class="detail-value">{{ $issue_date_it }}</span>
                </div>
            </div>
        </div>

        <!-- Blockchain Verification Section -->
        <div class="blockchain-section">
            <div class="blockchain-title">🔗 Verifica Blockchain</div>
            <div class="blockchain-details">
                <div class="blockchain-item">
                    <div class="blockchain-label">ASA Token ID</div>
                    <div class="blockchain-value">{{ $asa_id }}</div>
                </div>
                <div class="blockchain-item">
                    <div class="blockchain-label">Network</div>
                    <div class="blockchain-value">Algorand</div>
                </div>
            </div>
            <div class="blockchain-details">
                <div class="blockchain-item" style="grid-column: 1 / -1;">
                    <div class="blockchain-label">Transaction ID</div>
                    <div class="blockchain-value" style="font-size: 9pt;">{{ $transaction_id }}</div>
                </div>
            </div>
            <div style="text-align: center; margin-top: 3mm;">
                <div style="font-size: 9pt; color: var(--grigio-pietra);">
                    Verifica su: {{ $algorand_explorer_url }}
                </div>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-code">
                <!-- QR Code placeholder - in real implementation this would be generated -->
                <div style="font-size: 6pt; text-align: center;">
                    QR<br>{{ $certificate_number }}
                </div>
            </div>
            <div class="qr-label">Verifica Mobile</div>
        </div>

        <!-- Certificate Footer -->
        <div class="certificate-footer">
            <div class="issue-date">
                Emesso il {{ $issue_date_it }}
            </div>
            <div class="certificate-hash">
                Hash Certificato: {{ $certificate_hash }}
            </div>

            <div class="authority-signature">
                <div class="signature-line"></div>
                <div class="signature-label">FlorenceEGI Authority</div>
            </div>
        </div>
    </div>
</body>
</html>
