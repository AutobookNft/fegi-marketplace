<?php

/**
 * @Oracode PDF Certificate Service for FlorenceEGI Founders System
 * 🎯 Purpose: Generate branded PDF certificates with FlorenceEGI Rinascimento styling
 * 🧱 Core Logic: Blade templating, Dompdf generation, storage management, brand compliance
 * 🛡️ Security: File validation, storage paths, template sanitization
 *
 * @package App\Services
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori System)
 * @date 2025-07-05
 * @purpose Generate FlorenceEGI branded PDF certificates for founder tokens
 */

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Ultra\UltraLogManager\UltraLogManager;
use Ultra\ErrorManager\Interfaces\ErrorManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;

class PDFCertificateService
{
    private UltraLogManager $logger;
    private ErrorManagerInterface $errorManager;
    private array $config;
    private Dompdf $dompdf;

    /**
     * @Oracode Initialize PDF Certificate Service
     * 🎯 Purpose: Setup PDF generation engine and configuration
     *
     * @param UltraLogManager $logger
     * @param ErrorManagerInterface $errorManager
     */
    public function __construct(
        UltraLogManager $logger,
        ErrorManagerInterface $errorManager
    ) {
        $this->logger = $logger;
        $this->errorManager = $errorManager;
        $this->config = config('founders.certificate');

        // Initialize Dompdf with FlorenceEGI optimized settings
        $this->initializeDompdf();

        $this->logger->info('PDFCertificateService initialized', [
            'type' => 'PDF_SERVICE_INIT',
            'template_path' => $this->config['template_path'],
            'storage_disk' => $this->config['storage_disk']
        ]);
    }

    // ========================================
    // PUBLIC API METHODS
    // ========================================

    /**
     * @Oracode Generate PDF certificate for founder
     * 🎯 Purpose: Create branded PDF certificate with certificate data
     *
     * @param array $certificateData Certificate information
     * @return array [pdf_path, pdf_url, file_size] or exception on failure
     * @throws \Exception
     */
    public function generateFounderCertificate(array $certificateData): array
    {
        $this->logger->info('Starting PDF certificate generation', [
            'type' => 'PDF_GENERATION_START',
            'certificate_index' => $certificateData['index'] ?? null,
            'investor_name' => $certificateData['investor_name'] ?? null
        ]);

        try {
            // Validate required certificate data
            $this->validateCertificateData($certificateData);

            // Prepare template variables with FlorenceEGI branding
            $templateData = $this->prepareCertificateTemplateData($certificateData);

            // Generate PDF content
            $pdfContent = $this->generatePdfContent($templateData);

            // Save PDF to storage
            $storageResult = $this->savePdfToStorage($pdfContent, $certificateData['index']);

            $this->logger->info('PDF certificate generated successfully', [
                'type' => 'PDF_GENERATION_SUCCESS',
                'certificate_index' => $certificateData['index'],
                'pdf_path' => $storageResult['pdf_path'],
                'file_size' => $storageResult['file_size']
            ]);

            return $storageResult;

        } catch (\Exception $e) {
            $this->logger->error('PDF certificate generation failed', [
                'type' => 'PDF_GENERATION_FAILED',
                'certificate_index' => $certificateData['index'] ?? null,
                'error' => $e->getMessage()
            ]);

            throw $this->errorManager->handle('PDF_GENERATION_FAILED', [
                'certificate_index' => $certificateData['index'] ?? null,
                'error' => $e->getMessage()
            ], $e, true);
        }
    }

    /**
     * @Oracode Get PDF certificate from storage
     * 🎯 Purpose: Retrieve generated PDF file for download or email
     *
     * @param string $pdfPath Storage path to PDF file
     * @return string PDF file content
     * @throws \Exception
     */
    public function getCertificatePdf(string $pdfPath): string
    {
        $this->logger->info('Retrieving PDF certificate', [
            'type' => 'PDF_RETRIEVAL_START',
            'pdf_path' => $pdfPath
        ]);

        try {
            $disk = Storage::disk($this->config['storage_disk']);

            if (!$disk->exists($pdfPath)) {
                throw new \Exception("PDF file not found: {$pdfPath}");
            }

            $pdfContent = $disk->get($pdfPath);

            $this->logger->info('PDF certificate retrieved successfully', [
                'type' => 'PDF_RETRIEVAL_SUCCESS',
                'pdf_path' => $pdfPath,
                'file_size' => strlen($pdfContent)
            ]);

            return $pdfContent;

        } catch (\Exception $e) {
            $this->logger->error('PDF certificate retrieval failed', [
                'type' => 'PDF_RETRIEVAL_FAILED',
                'pdf_path' => $pdfPath,
                'error' => $e->getMessage()
            ]);

            throw $this->errorManager->handle('PDF_RETRIEVAL_FAILED', [
                'pdf_path' => $pdfPath,
                'error' => $e->getMessage()
            ], $e, true);
        }
    }

    /**
     * @Oracode Delete PDF certificate from storage
     * 🎯 Purpose: Clean up PDF files when needed (GDPR compliance)
     *
     * @param string $pdfPath Storage path to PDF file
     * @return bool Success status
     */
    public function deleteCertificatePdf(string $pdfPath): bool
    {
        $this->logger->info('Deleting PDF certificate', [
            'type' => 'PDF_DELETION_START',
            'pdf_path' => $pdfPath
        ]);

        try {
            $disk = Storage::disk($this->config['storage_disk']);

            if ($disk->exists($pdfPath)) {
                $disk->delete($pdfPath);

                $this->logger->info('PDF certificate deleted successfully', [
                    'type' => 'PDF_DELETION_SUCCESS',
                    'pdf_path' => $pdfPath
                ]);

                return true;
            }

            $this->logger->warning('PDF certificate not found for deletion', [
                'type' => 'PDF_DELETION_NOT_FOUND',
                'pdf_path' => $pdfPath
            ]);

            return false;

        } catch (\Exception $e) {
            $this->logger->error('PDF certificate deletion failed', [
                'type' => 'PDF_DELETION_FAILED',
                'pdf_path' => $pdfPath,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * @Oracode Initialize Dompdf with optimized settings
     * 🎯 Purpose: Configure PDF generation engine for certificates
     */
    private function initializeDompdf(): void
    {
        $options = new Options();

        // Security and performance settings
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultPaperSize', $this->config['pdf_format']);
        $options->set('defaultPaperOrientation', $this->config['pdf_orientation']);

        // Enable local file access for assets
        $options->set('isPhpEnabled', false);
        $options->set('chroot', [public_path(), storage_path('app/public')]);

        $this->dompdf = new Dompdf($options);
    }

    /**
     * @Oracode Validate certificate data completeness
     * 🎯 Purpose: Ensure all required data is present for PDF generation
     */
    private function validateCertificateData(array $data): void
    {
        $requiredFields = [
            'index',
            'investor_name',
            'investor_email',
            'asa_id',
            'tx_id',
            'issued_at'
        ];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Missing required certificate field: {$field}");
            }
        }

        // Validate specific field formats
        if (!is_numeric($data['index']) || $data['index'] < 1 || $data['index'] > 40) {
            throw new \InvalidArgumentException("Invalid certificate index: must be 1-40");
        }

        if (!filter_var($data['investor_email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }
    }

    /**
     * @Oracode Prepare template data with FlorenceEGI branding
     * 🎯 Purpose: Build complete data structure for certificate template
     */
    private function prepareCertificateTemplateData(array $certificateData): array
    {
        $brandConfig = $this->config['brand'];
        $roundConfig = config('founders');

        return [
            // Certificate core data
            'certificate_number' => str_pad($certificateData['index'], 2, '0', STR_PAD_LEFT),
            'investor_name' => strtoupper($certificateData['investor_name']),
            'investor_email' => $certificateData['investor_email'],
            'issue_date' => Carbon::parse($certificateData['issued_at'])->format('d F Y'),
            'issue_date_it' => Carbon::parse($certificateData['issued_at'])->locale('it')->isoFormat('D MMMM YYYY'),

            // Blockchain data
            'asa_id' => $certificateData['asa_id'],
            'transaction_id' => $certificateData['tx_id'],
            'algorand_explorer_url' => $this->getExplorerUrl($certificateData['tx_id']),

            // Round information
            'round_name' => $roundConfig['round_title'],
            'round_description' => $roundConfig['round_description'],
            'total_certificates' => $roundConfig['total_tokens'],
            'certificate_price' => number_format($roundConfig['price_eur'], 0, ',', '.'),
            'currency' => $roundConfig['currency'],

            // FlorenceEGI branding
            'brand' => [
                'logo_url' => $brandConfig['logo_path'],
                'colors' => $brandConfig['colors'],
                'fonts' => $brandConfig['fonts'],
                'company_name' => 'FlorenceEGI',
                'tagline' => 'Il Nuovo Rinascimento Ecologico Digitale',
                'website' => 'https://florenceegi.it'
            ],

            // Certificate authenticity
            'certificate_hash' => $this->generateCertificateHash($certificateData),
            'qr_code_data' => $this->generateQrCodeData($certificateData),
            'generation_timestamp' => now()->toIso8601String(),

            // Template metadata
            'template_version' => '1.0.0',
            'generation_system' => 'FlorenceEGI Founders System v1.0'
        ];
    }

    /**
     * @Oracode Generate PDF content from template
     * 🎯 Purpose: Render Blade template and convert to PDF
     */
    private function generatePdfContent(array $templateData): string
    {
        try {
            // Render Blade template
            $html = View::make('pdf.founder-certificate', $templateData)->render();

            // Load HTML into Dompdf
            $this->dompdf->loadHtml($html);

            // Set paper size and orientation
            $this->dompdf->setPaper(
                $this->config['pdf_format'],
                $this->config['pdf_orientation']
            );

            // Render PDF
            $this->dompdf->render();

            return $this->dompdf->output();

        } catch (\Exception $e) {
            throw new \Exception("PDF rendering failed: " . $e->getMessage());
        }
    }

    /**
     * @Oracode Save PDF to storage and return file information
     * 🎯 Purpose: Store PDF file and generate access paths
     */
    private function savePdfToStorage(string $pdfContent, int $index): array
    {
        $timestamp = now()->format('YmdHis');
        $filename = str_replace(
            ['{index}', '{timestamp}'],
            [str_pad($index, 2, '0', STR_PAD_LEFT), $timestamp],
            $this->config['filename_template']
        );

        $storagePath = $this->config['storage_path'] . '/' . $filename;
        $disk = Storage::disk($this->config['storage_disk']);

        // Create directory if it doesn't exist
        $directory = dirname($storagePath);
        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        // Save PDF file
        $disk->put($storagePath, $pdfContent);

        // Generate public URL if using public disk
        $publicUrl = null;
        if ($this->config['storage_disk'] === 'public') {
            $publicUrl = Storage::disk('public')->url($storagePath);
        }

        return [
            'pdf_path' => $storagePath,
            'pdf_url' => $publicUrl,
            'filename' => $filename,
            'file_size' => strlen($pdfContent),
            'mime_type' => 'application/pdf'
        ];
    }

    /**
     * @Oracode Generate certificate authenticity hash
     * 🎯 Purpose: Create verification hash for certificate authenticity
     */
    private function generateCertificateHash(array $certificateData): string
    {
        $hashData = [
            $certificateData['index'],
            $certificateData['investor_name'],
            $certificateData['asa_id'],
            $certificateData['tx_id'],
            $certificateData['issued_at']
        ];

        return strtoupper(substr(hash('sha256', implode('|', $hashData)), 0, 16));
    }

    /**
     * @Oracode Generate QR code data for certificate verification
     * 🎯 Purpose: Create QR code content for mobile verification
     */
    private function generateQrCodeData(array $certificateData): string
    {
        return json_encode([
            'type' => 'florenceegi_founder_certificate',
            'version' => '1.0',
            'certificate_id' => $certificateData['index'],
            'asa_id' => $certificateData['asa_id'],
            'tx_id' => $certificateData['tx_id'],
            'verification_url' => url("/certificates/verify/{$certificateData['index']}")
        ]);
    }

    /**
     * @Oracode Get Algorand explorer URL for transaction
     * 🎯 Purpose: Generate link to blockchain transaction
     */
    private function getExplorerUrl(string $txId): string
    {
        $network = config('founders.algorand.network');
        $explorerUrl = config("founders.algorand.{$network}.explorer_url");

        return "{$explorerUrl}/tx/{$txId}";
    }

    // ========================================
    // PUBLIC UTILITY METHODS
    // ========================================

    /**
     * @Oracode Get certificate template preview (HTML)
     * 🎯 Purpose: Generate HTML preview for testing/debugging
     */
    public function generateCertificatePreview(array $certificateData): string
    {
        $templateData = $this->prepareCertificateTemplateData($certificateData);

        return View::make('pdf.founder-certificate', $templateData)->render();
    }

    /**
     * @Oracode Get service statistics
     * 🎯 Purpose: Provide metrics for monitoring and admin interface
     */
    public function getServiceStatistics(): array
    {
        $disk = Storage::disk($this->config['storage_disk']);
        $storagePath = $this->config['storage_path'];

        $files = [];
        $totalSize = 0;

        if ($disk->exists($storagePath)) {
            $files = $disk->files($storagePath);

            foreach ($files as $file) {
                $totalSize += $disk->size($file);
            }
        }

        return [
            'total_certificates' => count($files),
            'total_storage_size' => $totalSize,
            'average_file_size' => count($files) > 0 ? round($totalSize / count($files)) : 0,
            'storage_disk' => $this->config['storage_disk'],
            'storage_path' => $storagePath
        ];
    }
}
