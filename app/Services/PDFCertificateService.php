<?php

/**
 * @Oracode PDF Certificate Service for FlorenceEGI Founders System
 * 🎯 Purpose: Generate branded PDF certificates with FlorenceEGI Rinascimento styling
 * 🧱 Core Logic: Blade templating, mPDF generation, storage management, brand compliance
 * 🛡️ Security: File validation, storage paths, template sanitization
 *
 * @package App\Services
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori System)
 * @date 2025-07-05
 * @purpose Generate FlorenceEGI branded PDF certificates for founder tokens
 */

namespace App\Services;

use App\Models\FounderCertificate;
use App\Models\Collection;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class PDFCertificateService
{
    /**
     * Genera il PDF del certificato in stile pergamena rinascimentale
     */
    public function generateCertificatePDF(FounderCertificate $certificate): string
    {
        try {
            // mPDF 8.x modern syntax - font standard temporanei
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
                'default_font' => 'dejavusans',
                'tempDir' => storage_path('app/tmp'),
                // Font custom temporaneamente disabilitati per evitare errori
                // 'fontDir' => [public_path('fonts'), storage_path('fonts')],
                // 'fontdata' => [...font custom...],
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'allow_charset_conversion' => true,
                'use_kwt' => true,
                'shrink_tables_to_fit' => 1,
                'use_active_forms' => false,
                'dpi' => 300,
                'img_dpi' => 300,
            ]);

            // Configura CSS per print
            $mpdf->WriteHTML('@page { size: A4 portrait; margin: 0; }', \Mpdf\HTMLParserMode::HEADER_CSS);

            // Genera HTML dal template mPDF
            $html = View::make('pdf.mpdf-certificate', [
                'certificate' => $certificate,
                'collection' => $certificate->collection,
                'benefits' => $certificate->collection->certificateBenefits ?? collect(),
                'qrCodeData' => $this->generateQRCodeData($certificate),
                'qrCodeImage' => $this->generateQRCodeImage($certificate),
                'verificationUrl' => $this->generateVerificationUrl($certificate)
            ])->render();

            $mpdf->WriteHTML($html);

            return $mpdf->Output('', 'S');
        } catch (\Exception $e) {
            Log::error('Errore generazione PDF mPDF: ' . $e->getMessage());
            throw new \Exception('Errore durante la generazione del PDF: ' . $e->getMessage());
        }
    }

    /**
     * Genera il nome del file PDF
     */
    private function generateFilename(FounderCertificate $certificate): string
    {
        $certificateNumber = str_pad($certificate->index ?? 1, 3, '0', STR_PAD_LEFT);
        $collectionSlug = $certificate->collection?->slug ?? 'padri-fondatori';
        $timestamp = now()->format('Y-m-d');

        return "certificate-{$collectionSlug}-{$certificateNumber}-{$timestamp}.pdf";
    }

    /**
     * Genera l'URL di verifica del certificato
     */
    private function generateVerificationUrl(FounderCertificate $certificate): string
    {
        return "https://scan.florenceegi.it/{$certificate->id}";
    }

    /**
     * Genera dati QR Code per il certificato
     */
    private function generateQRCodeData(FounderCertificate $certificate): string
    {
        $data = [
            'certificate_id' => $certificate->id,
            'investor_name' => $certificate->investor_name,
            'collection' => $certificate->collection?->name,
            'verification_url' => $this->generateVerificationUrl($certificate),
            'issued_at' => $certificate->issued_at?->format('Y-m-d H:i:s')
        ];

        return json_encode($data);
    }

    /**
     * Genera l'immagine QR Code come stringa base64
     */
    private function generateQRCodeImage(FounderCertificate $certificate): string
    {
        try {
            // URL di verifica da codificare nel QR
            $verificationUrl = $this->generateVerificationUrl($certificate);

            // Configura il renderer con backend SVG
            $renderer = new ImageRenderer(
                new RendererStyle(200, 4), // size, margin
                new SvgImageBackEnd()
            );

            // Genera il QR code
            $writer = new Writer($renderer);
            $qrCodeString = $writer->writeString($verificationUrl);

            // Converte SVG in base64 data URI
            $base64 = base64_encode($qrCodeString);

            return 'data:image/svg+xml;base64,' . $base64;
        } catch (\Exception $e) {
            Log::error('Errore generazione QR Code: ' . $e->getMessage());

            // Fallback: placeholder SVG
            $placeholderSvg = '<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">' .
                '<rect width="200" height="200" fill="#f0f0f0" stroke="#8b6914" stroke-width="2"/>' .
                '<text x="100" y="90" text-anchor="middle" font-size="20" fill="#8b6914">QR</text>' .
                '<text x="100" y="115" text-anchor="middle" font-size="12" fill="#8b6914">Code</text>' .
                '<text x="100" y="135" text-anchor="middle" font-size="8" fill="#999">Scan per verifica</text>' .
                '</svg>';

            return 'data:image/svg+xml;base64,' . base64_encode($placeholderSvg);
        }
    }

    /**
     * Visualizza il PDF nel browser
     */
    public function streamCertificatePDF(FounderCertificate $certificate)
    {
        try {
            // mPDF con configurazione minimal per debug
            $mpdf = new Mpdf([
                'format' => 'A4',
                'tempDir' => storage_path('app/tmp')
            ]);

            // Template rinascimentale identico ma sicuro (senza Blade che causa loop)
            $investorName = htmlspecialchars($certificate->investor_name ?? 'N/A');
            $certificateId = str_pad($certificate->id, 4, '0', STR_PAD_LEFT);
            $certificateIdLong = str_pad($certificate->id, 6, '0', STR_PAD_LEFT);
            $createdAt = $certificate->created_at ? $certificate->created_at->format('d F Y') : date('d F Y');
            $asaId = $certificate->asa_id ? htmlspecialchars($certificate->asa_id) : null;
            $txId = $certificate->tx_id ? htmlspecialchars($certificate->tx_id) : null;
            $walletAddress = $certificate->investor_wallet ? htmlspecialchars($certificate->investor_wallet) : null;
            $mintedAt = $certificate->minted_at ? $certificate->minted_at->format('d F Y H:i') : null;
            $basePrice = number_format($certificate->base_price ?? 250, 2, ',', '.');

            $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .certificate-container {
            width: 100%;
            max-width: 794px;
            margin: 0 auto;
            background: linear-gradient(135deg, #faf5e6 0%, #f5f0e0 25%, #ede3d0 75%, #e6d7c0 100%);
            border: 8px solid #8b6914;
            border-radius: 20px;
            position: relative;
            min-height: 1122px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .inner-border {
            border: 3px solid #daa520;
            border-radius: 12px;
            margin: 15px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 3;
        }
        .corner-ornament {
            position: absolute;
            font-size: 32px;
            color: #8b6914;
            font-weight: bold;
            z-index: 4;
        }
        .corner-ornament.top-left { top: 20px; left: 20px; }
        .corner-ornament.top-right { top: 20px; right: 20px; }
        .corner-ornament.bottom-left { bottom: 20px; left: 20px; }
        .corner-ornament.bottom-right { bottom: 20px; right: 20px; }
        .header { text-align: center; margin-bottom: 25px; }
        .company-logo {
            font-size: 48px; font-weight: bold; letter-spacing: 8px; margin-bottom: 10px;
            color: #8b6914; text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
        }
        .company-subtitle {
            font-size: 16px; font-style: italic; color: #704214; margin-bottom: 15px; letter-spacing: 1px;
        }
        .ornamental-divider { font-size: 28px; color: #8b6914; margin: 10px 0; }
        .certificate-title {
            font-size: 36px; font-weight: bold; letter-spacing: 4px; margin: 20px 0;
            text-align: center; color: #8b6914; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        .certificate-number {
            font-size: 14px; color: #704214; text-align: center; margin-bottom: 30px; font-weight: bold;
        }
        .main-content { text-align: center; margin: 25px 0; }
        .proclamation {
            font-size: 18px; color: #2c1810; margin-bottom: 20px; line-height: 1.6; font-weight: 500;
        }
        .investor-name {
            font-size: 48px; font-weight: bold; font-style: italic; margin: 25px 0;
            color: #8b6914; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); letter-spacing: 2px;
        }
        .founder-declaration {
            font-size: 20px; color: #2c1810; margin: 20px 0; line-height: 1.6; font-weight: 500;
        }
        .emphasis {
            font-size: 24px; font-weight: bold; color: #8b6914; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }
        .registry-section {
            background: rgba(255, 255, 255, 0.8); border: 3px solid #daa520;
            border-radius: 10px; padding: 20px; margin: 25px 0;
        }
        .registry-title {
            font-size: 20px; font-weight: bold; color: #8b6914; text-align: center;
            margin-bottom: 15px; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }
        .registry-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .registry-table td {
            padding: 10px 12px; border-bottom: 2px solid #daa520; color: #2c1810; font-weight: 500;
        }
        .registry-table td:first-child { font-weight: bold; color: #704214; width: 40%; }
        .footer { display: table; width: 100%; margin-top: 30px; }
        .footer-left, .footer-right { display: table-cell; width: 200px; text-align: center; vertical-align: bottom; }
        .footer-center { display: table-cell; text-align: center; vertical-align: bottom; }
        .digital-seal { text-align: center; }
        .seal-circle {
            width: 100px; height: 100px; border: 4px solid #8b6914; border-radius: 50%;
            background: linear-gradient(to bottom, #ffd700 0%, #daa520 50%, #8b6914 100%);
            display: inline-block; position: relative; margin-bottom: 10px;
        }
        .seal-text {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            font-size: 9px; font-weight: bold; color: #2c1810; text-align: center; line-height: 1.2;
        }
        .seal-caption { font-size: 11px; color: #704214; font-weight: bold; font-style: italic; }
        .signature-section { text-align: center; }
        .signature-line { border-top: 2px solid #8b6914; margin: 20px auto 10px; width: 150px; }
        .signature-name { font-size: 16px; font-weight: bold; color: #8b6914; margin-bottom: 5px; }
        .signature-title { font-size: 12px; color: #704214; font-style: italic; font-weight: bold; }
        .verification-section { text-align: center; margin-top: 20px; }
        .qr-placeholder {
            width: 60px; height: 60px; border: 2px solid #8b6914; border-radius: 5px;
            background: white; display: inline-block; margin: 0 auto 8px; position: relative;
        }
        .qr-placeholder::after {
            content: "QR"; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            font-size: 10px; font-weight: bold; color: #8b6914;
        }
        .verification-text { font-size: 10px; color: #704214; margin-top: 5px; font-weight: 500; }
        .verification-url { font-family: monospace; font-size: 8px; color: #8b6914; margin-top: 3px; font-weight: bold; }
        .watermark {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px; color: rgba(218, 165, 32, 0.05); font-weight: bold; z-index: 1;
        }
        .decorative-elements { position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; z-index: 2; }
        .flourish { position: absolute; font-size: 80px; color: rgba(139, 105, 20, 0.15); font-weight: bold; }
        .flourish.left { top: 150px; left: 10px; transform: rotate(-15deg); }
        .flourish.right { top: 150px; right: 10px; transform: rotate(15deg); }
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
            <div class="corner-ornament top-left">❦</div>
            <div class="corner-ornament top-right">❦</div>
            <div class="corner-ornament bottom-left">❦</div>
            <div class="corner-ornament bottom-right">❦</div>

            <div class="header">
                <div class="company-logo">FLORENCEEGI</div>
                <div class="company-subtitle">FlorenceEGI – Il nuovo Rinascimento Ecologico Digitale</div>
                <div class="ornamental-divider">⚜ ❦ ⚜</div>
            </div>

            <div class="certificate-title">CERTIFICATO DI FONDAZIONE</div>
            <div class="certificate-number">Anno Domini ' . date('Y') . ' • N. ' . $certificateId . '</div>

            <div class="main-content">
                <div class="proclamation">
                    Nel nome del Nuovo Rinascimento e della rinascita tecnologica,<br>
                    si attesta solennemente che il portatore di questo documento è riconosciuto come
                </div>
                <div class="investor-name">' . $investorName . '</div>
                <div class="founder-declaration">
                    <span class="emphasis">PADRE FONDATORE</span><br>
                    del progetto rivoluzionario FlorenceEGI, che unisce la maestria artistica fiorentina<br>
                    con le tecnologie Blockchain più avanzate, creando un ecosistema digitale<br>
                    che onora la tradizione mentre forgia il futuro della sostenibilità.
                </div>
            </div>

            <div class="registry-section">
                <div class="registry-title">⚜ Registro Blockchain Algorand ⚜</div>
                <table class="registry-table">
                    <tr><td>Certificato N.:</td><td>' . $certificateIdLong . '</td></tr>
                    <tr><td>Data Emissione:</td><td>' . $createdAt . '</td></tr>
                    <tr><td>Collection:</td><td>Padri Fondatori</td></tr>
                    <tr><td>Valore Nominale:</td><td>€' . $basePrice . '</td></tr>' .
                ($asaId ? '<tr><td><strong>🪙 Token ASA ID:</strong></td><td style="font-family: monospace; color: #0066cc; font-weight: bold;">' . $asaId . '</td></tr>' : '') .
                ($txId ? '<tr><td><strong>🔗 Transaction ID:</strong></td><td style="font-family: monospace; color: #0066cc; font-weight: bold; font-size: 10px;">' . $txId . '</td></tr>' : '') .
                '<tr><td><strong>💰 Wallet Destinazione:</strong></td><td style="font-family: monospace; color: #cc6600; font-weight: bold; font-size: 11px;">' .
                ($walletAddress ? substr($walletAddress, 0, 20) . '...<br><small style="color: #666;">(Wallet Investitore)</small>' : 'Treasury Wallet<br><small style="color: #666;">(In attesa di trasferimento)</small>') . '</td></tr>' .
                ($mintedAt ? '<tr><td><strong>⏰ Data Minting:</strong></td><td>' . $mintedAt . '</td></tr>' : '') . '
                </table>
            </div>

            <div class="footer">
                <div class="footer-left">
                    <div class="digital-seal">
                        <div class="seal-circle">
                            <div class="seal-text">FLORENCEEGI<br>CERTIFICATO<br>AUTENTICO<br>' . date('Y') . '</div>
                        </div>
                        <div class="seal-caption">Sigillo Digitale Certificato</div>
                    </div>
                </div>
                <div class="footer-center">
                    <div class="verification-section">
                        <div class="qr-placeholder"></div>
                        <div class="verification-text">Certificazione Blockchain Verificabile<br>Scansiona per autenticità garantita</div>
                        <div class="verification-url">https://scan.florenceegi.it/' . $certificate->id . '</div>
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
</html>';

            $mpdf->WriteHTML($html);

            return $mpdf->Output('test.pdf', 'I');
        } catch (\Exception $e) {
            return response('ERRORE PDF: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Genera PDF in batch per una collection
     */
    public function generateCollectionPDFs(Collection $collection): array
    {
        $certificates = $collection->certificates()->ready()->get();
        $generatedPaths = [];

        foreach ($certificates as $certificate) {
            try {
                $path = $this->generateCertificatePDF($certificate);
                $generatedPaths[] = [
                    'certificate_id' => $certificate->id,
                    'path' => $path,
                    'filename' => basename($path),
                    'status' => 'success'
                ];
            } catch (\Exception $e) {
                $generatedPaths[] = [
                    'certificate_id' => $certificate->id,
                    'error' => $e->getMessage(),
                    'status' => 'error'
                ];
            }
        }

        return $generatedPaths;
    }

    /**
     * Verifica l'esistenza del PDF
     */
    public function pdfExists(FounderCertificate $certificate): bool
    {
        $filename = $this->generateFilename($certificate);
        return Storage::disk('public')->exists("certificates/{$filename}");
    }

    /**
     * Elimina il PDF esistente
     */
    public function deletePDF(FounderCertificate $certificate): bool
    {
        $filename = $this->generateFilename($certificate);
        $path = "certificates/{$filename}";

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return true;
    }

    /**
     * Ottiene l'URL pubblico del PDF
     */
    public function getPDFUrl(FounderCertificate $certificate): ?string
    {
        $filename = $this->generateFilename($certificate);
        $path = "certificates/{$filename}";

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return null;
    }

    /**
     * Genera un hash di verifica per il certificato
     */
    public function generateVerificationHash(FounderCertificate $certificate): string
    {
        $data = [
            'certificate_id' => $certificate->id,
            'investor_name' => $certificate->investor_name,
            'collection_id' => $certificate->collection_id,
            'base_price' => $certificate->base_price,
            'issued_at' => $certificate->issued_at?->timestamp,
        ];

        return hash('sha256', json_encode($data) . config('app.key'));
    }

    /**
     * Verifica l'autenticità del certificato tramite hash
     */
    public function verifyCertificate(FounderCertificate $certificate, string $hash): bool
    {
        return hash_equals($this->generateVerificationHash($certificate), $hash);
    }
}
