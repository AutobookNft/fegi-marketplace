{{--
    @Oracode Email Template for FlorenceEGI Founder Certificate Delivery
    🎯 Purpose: Branded email notification for certificate delivery with instructions and links
    🧱 Core Logic: Professional email layout, clear CTAs, blockchain information, brand compliance
    🛡️ Security: Secure links, privacy compliance, unsubscribe options

    @package resources/views/emails
    @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
    @version 1.0.0 (FlorenceEGI - Email Certificate Template)
    @date 2025-07-05
    @purpose Professional email template for Padre Fondatore certificate delivery
--}}

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ $subject }}</title>
    <style>
        /* Email-safe CSS with FlorenceEGI branding */

        /* Reset styles for email clients */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        /* FlorenceEGI Brand Colors */
        :root {
            --oro-fiorentino: #D4A574;
            --verde-rinascita: #2D5016;
            --blu-algoritmo: #1B365D;
            --grigio-pietra: #6B6B6B;
        }

        /* Base styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f8fafc;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header styles */
        .email-header {
            background: linear-gradient(135deg, #D4A574 0%, #B8956A 100%);
            padding: 30px 20px;
            text-align: center;
        }

        .logo-section {
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            margin: 5px 0 0 0;
            font-weight: 400;
        }

        .email-title {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin: 20px 0 0 0;
            line-height: 1.3;
        }

        /* Content styles */
        .email-content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            color: #1B365D;
            font-weight: 600;
            margin: 0 0 20px 0;
            line-height: 1.4;
        }

        .content-text {
            font-size: 16px;
            color: #374151;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        .highlight-text {
            color: #1B365D;
            font-weight: 600;
        }

        /* Certificate Summary Box */
        .certificate-summary {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 2px solid #D4A574;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }

        .certificate-title {
            font-size: 20px;
            font-weight: 700;
            color: #1B365D;
            margin: 0 0 15px 0;
        }

        .certificate-number {
            font-size: 28px;
            font-weight: 800;
            color: #D4A574;
            margin: 0 0 15px 0;
            letter-spacing: 1px;
        }

        .certificate-details {
            text-align: left;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dotted #cbd5e1;
            font-size: 14px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #1B365D;
        }

        .detail-value {
            color: #374151;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }

        /* Button styles */
        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #D4A574 0%, #B8956A 100%);
            color: #ffffff;
            border: 2px solid #D4A574;
        }

        .btn-secondary {
            background: #ffffff;
            color: #1B365D;
            border: 2px solid #1B365D;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Blockchain section */
        .blockchain-section {
            background: #f1f5f9;
            border-left: 4px solid #1B365D;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .blockchain-title {
            font-size: 18px;
            font-weight: 600;
            color: #1B365D;
            margin: 0 0 15px 0;
        }

        .blockchain-info {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }

        .transaction-id {
            background: #ffffff;
            padding: 10px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #1B365D;
            word-break: break-all;
            margin: 10px 0;
            border: 1px solid #cbd5e1;
        }

        /* Wallet instructions */
        .wallet-section {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px solid #2D5016;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .wallet-title {
            font-size: 18px;
            font-weight: 600;
            color: #2D5016;
            margin: 0 0 15px 0;
        }

        .wallet-status {
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            font-weight: 500;
        }

        .wallet-transferred {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .wallet-treasury {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Footer styles */
        .email-footer {
            background: #f8fafc;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-links {
            margin: 20px 0;
        }

        .footer-link {
            color: #1B365D;
            text-decoration: none;
            font-weight: 500;
            margin: 0 15px;
            font-size: 14px;
        }

        .footer-link:hover {
            text-decoration: underline;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-link {
            display: inline-block;
            margin: 0 10px;
            text-decoration: none;
            color: #6B6B6B;
            font-size: 14px;
        }

        .footer-text {
            font-size: 12px;
            color: #6B6B6B;
            line-height: 1.5;
            margin: 15px 0;
        }

        .unsubscribe {
            font-size: 11px;
            color: #9ca3af;
        }

        .unsubscribe a {
            color: #9ca3af;
            text-decoration: underline;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }

            .email-content {
                padding: 25px 20px !important;
            }

            .certificate-summary {
                padding: 20px !important;
            }

            .btn {
                display: block !important;
                margin: 10px 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .detail-row {
                display: block !important;
                text-align: left !important;
            }

            .detail-value {
                margin-top: 5px !important;
                display: block !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #1f2937 !important;
            }

            .email-content {
                background-color: #1f2937 !important;
            }

            .content-text {
                color: #d1d5db !important;
            }

            .certificate-summary {
                background: linear-gradient(135deg, #374151 0%, #4b5563 100%) !important;
                border-color: #D4A574 !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">

        <!-- Email Header -->
        <div class="email-header">
            <div class="logo-section">
                <h1 class="company-name">FlorenceEGI</h1>
                <p class="company-tagline">{{ $brand['tagline'] }}</p>
            </div>
            <h2 class="email-title">🎉 Il tuo Certificato Padre Fondatore è pronto!</h2>
        </div>

        <!-- Email Content -->
        <div class="email-content">

            <div class="greeting">
                Caro {{ $recipient_name }},
            </div>

            <p class="content-text">
                È un grande piacere comunicarti che il tuo <span class="highlight-text">Certificato Padre Fondatore</span>
                è stato emesso con successo! Sei ufficialmente entrato a far parte dei primi sostenitori del
                <span class="highlight-text">Nuovo Rinascimento Ecologico Digitale</span>.
            </p>

            <!-- Certificate Summary -->
            <div class="certificate-summary">
                <h3 class="certificate-title">Certificato Padre Fondatore</h3>
                <div class="certificate-number">#{{ $certificate_number }}</div>

                <div class="certificate-details">
                    <div class="detail-row">
                        <span class="detail-label">Round:</span>
                        <span class="detail-value">{{ $round_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">ASA Token ID:</span>
                        <span class="detail-value">{{ $asa_id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Data Emissione:</span>
                        <span class="detail-value">{{ $issue_date }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Network:</span>
                        <span class="detail-value">Algorand {{ $algorand_network }}</span>
                    </div>
                </div>
            </div>

            <!-- Blockchain Verification -->
            <div class="blockchain-section">
                <h3 class="blockchain-title">🔗 Verifica Blockchain</h3>
                <p class="blockchain-info">
                    Il tuo certificato è registrato permanentemente sulla blockchain Algorand.
                    Puoi verificare l'autenticità utilizzando l'ID della transazione:
                </p>
                <div class="transaction-id">{{ $transaction_id }}</div>
                <p class="blockchain-info">
                    <a href="{{ $explorer_url }}" style="color: #1B365D; font-weight: 600;">
                        👀 Visualizza su Algorand Explorer
                    </a>
                </p>
            </div>

            <!-- Wallet Status -->
            <div class="wallet-section">
                <h3 class="wallet-title">💰 Stato del Token ASA</h3>
                @if($token_transferred)
                    <div class="wallet-status wallet-transferred">
                        ✅ Token trasferito con successo al tuo wallet Algorand
                    </div>
                    <p style="margin: 15px 0; color: #2D5016; font-size: 14px;">
                        Il token ASA è ora nel tuo wallet: <code style="background: #f0f9ff; padding: 2px 6px; border-radius: 4px;">{{ $wallet_address }}</code>
                    </p>
                @else
                    <div class="wallet-status wallet-treasury">
                        🏦 Token conservato nel Treasury FlorenceEGI
                    </div>
                    <p style="margin: 15px 0; color: #92400e; font-size: 14px;">
                        @if($has_wallet)
                            Il trasferimento al tuo wallet è in elaborazione. Ti contatteremo per completare l'operazione.
                        @else
                            Per ricevere il token nel tuo wallet, <strong>scarica Pera Wallet</strong> e contattaci con l'indirizzo del tuo wallet.
                        @endif
                    </p>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="button-container">
                @if(!$token_transferred)
                    <a href="{{ $cta_urls['download_pera'] }}" class="btn btn-primary" target="_blank">
                        📱 Scarica Pera Wallet
                    </a>
                @endif
                <a href="{{ $cta_urls['florenceegi_info'] }}" class="btn btn-secondary" target="_blank">
                    🌟 Scopri il Progetto
                </a>
            </div>

            <p class="content-text">
                <strong>Il tuo certificato PDF è allegato a questa email.</strong> Conservalo come prova della tua
                partecipazione al progetto. Riceverai inoltre il <span class="highlight-text">prisma commemorativo</span>
                fisico all'indirizzo che hai fornito.
            </p>

            <p class="content-text">
                Grazie per essere un <strong>Padre Fondatore</strong> del Nuovo Rinascimento Ecologico Digitale.
                Insieme stiamo costruendo il futuro sostenibile!
            </p>

            <p class="content-text" style="margin-top: 30px;">
                Con stima,<br>
                <strong>{{ $signature }}</strong>
            </p>
        </div>

        <!-- Email Footer -->
        <div class="email-footer">
            <div class="footer-links">
                <a href="{{ $brand['website'] }}" class="footer-link">🌐 Sito Web</a>
                <a href="{{ $privacy_policy_url }}" class="footer-link">🔒 Privacy Policy</a>
                <a href="{{ $cta_urls['learn_algorand'] }}" class="footer-link">📖 Algorand</a>
            </div>

            <div class="social-links">
                <a href="{{ $brand['social_links']['linkedin'] }}" class="social-link">LinkedIn</a>
                <a href="{{ $brand['social_links']['twitter'] }}" class="social-link">Twitter</a>
                <a href="{{ $brand['social_links']['instagram'] }}" class="social-link">Instagram</a>
            </div>

            <p class="footer-text">
                <strong>FlorenceEGI</strong><br>
                {{ $brand['tagline'] }}<br>
                Email: {{ $reply_to ?? 'info@florenceegi.it' }}
            </p>

            @unless($is_test ?? false)
            <p class="unsubscribe">
                Non vuoi più ricevere queste email?
                <a href="{{ $unsubscribe_url }}">Cancellati qui</a>
            </p>
            @endunless

            <p class="footer-text">
                <small>
                    Email generata automaticamente il {{ $email_timestamp }}<br>
                    Template version {{ $template_version }}
                </small>
            </p>
        </div>

    </div>
</body>
</html>
