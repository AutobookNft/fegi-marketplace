<?php

namespace App\Services;

use App\Models\FounderCertificate;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

/**
 * Service per generare immagini PNG dei certificati
 * Converte HTML in PNG ad alta risoluzione per stampa perfetta
 */
class CertificateImageService
{
    /**
     * Genera PNG del certificato ad alta risoluzione
     */
    public function generateCertificateImage(FounderCertificate $certificate): string
    {
        try {
            // Crea il template HTML senza header di navigazione (per PNG pulito)
            $html = $this->generatePrintOptimizedHtml($certificate);

            // Crea PNG ad alta risoluzione (300 DPI equivalente)
            $imageData = Browsershot::html($html)
                ->setChromePath('/usr/bin/google-chrome-stable') // Path di Chrome
                ->windowSize(2480, 3508) // A4 a 300 DPI (210x297mm)
                ->deviceScaleFactor(3)    // Alta risoluzione
                ->setOption('args', [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-accelerated-2d-canvas',
                    '--no-first-run',
                    '--no-zygote',
                    '--disable-gpu',
                    '--disable-background-timer-throttling',
                    '--disable-backgrounding-occluded-windows',
                    '--disable-renderer-backgrounding'
                ])
                ->screenshot();

            // Salva il file
            $filename = $this->generateImageFilename($certificate);
            $path = "certificates/images/{$filename}";

            Storage::disk('public')->put($path, $imageData);

            return $path;
        } catch (\Exception $e) {
            throw new \Exception('Errore generazione immagine certificato: ' . $e->getMessage());
        }
    }

    /**
     * Genera HTML ottimizzato per stampa (senza header di navigazione)
     */
    private function generatePrintOptimizedHtml(FounderCertificate $certificate): string
    {
        // Usa lo stesso template magnifico ma senza navigation header
        return view('certificates.print-image', compact('certificate'))->render();
    }

    /**
     * Genera nome file per l'immagine
     */
    private function generateImageFilename(FounderCertificate $certificate): string
    {
        $certificateNumber = str_pad($certificate->id, 4, '0', STR_PAD_LEFT);
        $investorSlug = Str::slug($certificate->investor_name ?? 'certificato');
        $timestamp = now()->format('Y-m-d');

        return "florenceegi-certificate-{$certificateNumber}-{$investorSlug}-{$timestamp}.png";
    }

    /**
     * Stream immagine PNG direttamente al browser per download
     */
    public function streamCertificateImage(FounderCertificate $certificate)
    {
        try {
            // Genera l'immagine al volo
            $html = $this->generatePrintOptimizedHtml($certificate);

            $imageData = Browsershot::html($html)
                ->setChromePath('/usr/bin/google-chrome-stable') // Path di Chrome
                ->windowSize(2480, 3508) // A4 a 300 DPI
                ->deviceScaleFactor(3)
                ->setOption('args', [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-accelerated-2d-canvas',
                    '--no-first-run',
                    '--no-zygote',
                    '--disable-gpu',
                    '--disable-background-timer-throttling',
                    '--disable-backgrounding-occluded-windows',
                    '--disable-renderer-backgrounding'
                ])
                ->screenshot();

            $filename = $this->generateImageFilename($certificate);

            return response($imageData)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            return response('Errore generazione immagine: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Verifica esistenza immagine
     */
    public function imageExists(FounderCertificate $certificate): bool
    {
        $filename = $this->generateImageFilename($certificate);
        return Storage::disk('public')->exists("certificates/images/{$filename}");
    }

    /**
     * Elimina immagine esistente
     */
    public function deleteImage(FounderCertificate $certificate): bool
    {
        $filename = $this->generateImageFilename($certificate);
        $path = "certificates/images/{$filename}";

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return true;
    }

    /**
     * Ottiene URL pubblico dell'immagine
     */
    public function getImageUrl(FounderCertificate $certificate): ?string
    {
        $filename = $this->generateImageFilename($certificate);
        $path = "certificates/images/{$filename}";

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return null;
    }
}
