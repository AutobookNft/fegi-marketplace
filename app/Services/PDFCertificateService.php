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
            // mPDF 8.x modern syntax
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
                'fontDir' => [
                    public_path('fonts'),
                    storage_path('fonts')
                ],
                'fontdata' => [
                    'cinzel' => [
                        'R' => 'Cinzel-Regular.ttf',
                        'B' => 'Cinzel-Bold.ttf',
                    ],
                    'playfair' => [
                        'R' => 'PlayfairDisplay-Regular.ttf',
                        'B' => 'PlayfairDisplay-Bold.ttf',
                        'I' => 'PlayfairDisplay-Italic.ttf',
                        'BI' => 'PlayfairDisplay-BoldItalic.ttf',
                    ],
                    'garamond' => [
                        'R' => 'EBGaramond-Regular.ttf',
                        'B' => 'EBGaramond-Bold.ttf',
                        'I' => 'EBGaramond-Italic.ttf',
                        'BI' => 'EBGaramond-BoldItalic.ttf',
                    ]
                ],
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
            \Log::error('Errore generazione PDF mPDF: ' . $e->getMessage());
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
            \Log::error('Errore generazione QR Code: ' . $e->getMessage());

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
            // mPDF 8.x modern syntax
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
                'fontDir' => [
                    public_path('fonts'),
                    storage_path('fonts')
                ],
                'fontdata' => [
                    'cinzel' => [
                        'R' => 'Cinzel-Regular.ttf',
                        'B' => 'Cinzel-Bold.ttf',
                    ],
                    'playfair' => [
                        'R' => 'PlayfairDisplay-Regular.ttf',
                        'B' => 'PlayfairDisplay-Bold.ttf',
                        'I' => 'PlayfairDisplay-Italic.ttf',
                        'BI' => 'PlayfairDisplay-BoldItalic.ttf',
                    ],
                    'garamond' => [
                        'R' => 'EBGaramond-Regular.ttf',
                        'B' => 'EBGaramond-Bold.ttf',
                        'I' => 'EBGaramond-Italic.ttf',
                        'BI' => 'EBGaramond-BoldItalic.ttf',
                    ]
                ],
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
                'benefits' => $certificate->collection->benefits ?? collect(),
                'qrCodeData' => $this->generateQRCodeData($certificate),
                'qrCodeImage' => $this->generateQRCodeImage($certificate),
                'verificationUrl' => route('certificate.verify', $certificate->verification_code)
            ])->render();

            $mpdf->WriteHTML($html);

            return $mpdf->Output('certificato-' . $certificate->id . '.pdf', 'I');
        } catch (\Exception $e) {
            \Log::error('Errore stream PDF mPDF: ' . $e->getMessage());
            throw new \Exception('Errore durante lo streaming del PDF: ' . $e->getMessage());
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
            return Storage::disk('public')->url($path);
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
