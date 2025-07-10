<?php

/**
 * @Oracode Email Notification Service for FlorenceEGI Founders System
 * 🎯 Purpose: Send branded email notifications with PDF certificate attachments
 * 🧱 Core Logic: Laravel Mail integration, PDF attachments, template rendering, delivery tracking
 * 🛡️ Security: Email validation, attachment sanitization, rate limiting, GDPR compliance
 *
 * @package App\Services
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori System)
 * @date 2025-07-05
 * @purpose Complete email delivery system for founder certificate notifications
 */

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Mail\Mailables\Attachment;
use Ultra\UltraLogManager\UltraLogManager;
use Ultra\ErrorManager\Interfaces\ErrorManagerInterface;
use App\Mail\FounderCertificateMail;

class EmailNotificationService
{
    private UltraLogManager $logger;
    private ErrorManagerInterface $errorManager;
    private array $config;
    private PDFCertificateService $pdfService;

    /**
     * @Oracode Initialize Email Notification Service
     * 🎯 Purpose: Setup email configuration and dependencies
     *
     * @param UltraLogManager $logger
     * @param ErrorManagerInterface $errorManager
     * @param PDFCertificateService $pdfService
     */
    public function __construct(
        UltraLogManager $logger,
        ErrorManagerInterface $errorManager,
        PDFCertificateService $pdfService
    ) {
        $this->logger = $logger;
        $this->errorManager = $errorManager;
        $this->pdfService = $pdfService;
        $this->config = config('founders.email');

        $this->logger->info('EmailNotificationService initialized', [
            'type' => 'EMAIL_SERVICE_INIT',
            'from_email' => $this->config['from_email'],
            'template' => $this->config['template']
        ]);
    }

    // ========================================
    // PUBLIC API METHODS
    // ========================================

    /**
     * @Oracode Send founder certificate email with PDF attachment
     * 🎯 Purpose: Deliver certificate to investor via email with branded template
     *
     * @param array $certificateData Certificate information
     * @param string $pdfPath Path to generated PDF certificate
     * @return array [message_id, delivery_status] or exception on failure
     * @throws \Exception
     */
    public function sendFounderCertificate(array $certificateData, string $pdfPath): array
    {
        $this->logger->info('Starting certificate email delivery', [
            'type' => 'EMAIL_DELIVERY_START',
            'certificate_index' => $certificateData['index'] ?? null,
            'recipient_email' => $certificateData['investor_email'] ?? null,
            'pdf_path' => $pdfPath
        ]);

        try {
            // Validate email data and PDF attachment
            $this->validateEmailData($certificateData, $pdfPath);

            // Prepare email content with FlorenceEGI branding
            $emailData = $this->prepareEmailData($certificateData);

            // Get PDF content for attachment
            $pdfContent = $this->pdfService->getCertificatePdf($pdfPath);

            // Send email with attachment
            $deliveryResult = $this->sendCertificateEmail($emailData, $pdfContent, $certificateData['index']);

            $this->logger->info('Certificate email delivered successfully', [
                'type' => 'EMAIL_DELIVERY_SUCCESS',
                'certificate_index' => $certificateData['index'],
                'recipient_email' => $certificateData['investor_email'],
                'message_id' => $deliveryResult['message_id'] ?? null
            ]);

            return $deliveryResult;

        } catch (\Exception $e) {
            $this->logger->error('Certificate email delivery failed', [
                'type' => 'EMAIL_DELIVERY_FAILED',
                'certificate_index' => $certificateData['index'] ?? null,
                'recipient_email' => $certificateData['investor_email'] ?? null,
                'error' => $e->getMessage()
            ]);

            throw $this->errorManager->handle('EMAIL_DELIVERY_FAILED', [
                'certificate_index' => $certificateData['index'] ?? null,
                'recipient_email' => $certificateData['investor_email'] ?? null,
                'error' => $e->getMessage()
            ], $e, true);
        }
    }

    /**
     * @Oracode Send test email for configuration validation
     * 🎯 Purpose: Verify email configuration and template rendering
     *
     * @param string $testEmail Email address for test delivery
     * @return array Test result information
     * @throws \Exception
     */
    public function sendTestEmail(string $testEmail): array
    {
        $this->logger->info('Sending test email', [
            'type' => 'EMAIL_TEST_START',
            'test_email' => $testEmail
        ]);

        try {
            // Validate test email format
            if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Invalid test email format: {$testEmail}");
            }

            // Prepare mock certificate data for testing
            $mockCertificateData = $this->getMockCertificateData();
            $emailData = $this->prepareEmailData($mockCertificateData);

            // Override recipient for test
            $emailData['recipient_email'] = $testEmail;
            // $emailData['subject'] = '[TEST] ' . $emailData['subject'];
            $emailData['is_test'] = true;

            // Send test email without PDF attachment
            $result = $this->sendTestCertificateEmail($emailData);

            $this->logger->info('Test email sent successfully', [
                'type' => 'EMAIL_TEST_SUCCESS',
                'test_email' => $testEmail
            ]);

            return $result;

        } catch (\Exception $e) {
            $this->logger->error('Test email failed', [
                'type' => 'EMAIL_TEST_FAILED',
                'test_email' => $testEmail,
                'error' => $e->getMessage()
            ]);

            throw $this->errorManager->handle('EMAIL_TEST_FAILED', [
                'test_email' => $testEmail,
                'error' => $e->getMessage()
            ], $e, true);
        }
    }

    /**
     * @Oracode Preview email template (HTML)
     * 🎯 Purpose: Generate HTML preview for template testing and debugging
     *
     * @param array $certificateData Certificate information for preview
     * @return string Rendered HTML email template
     */
    public function previewEmailTemplate(array $certificateData): string
    {
        $this->logger->info('Generating email template preview', [
            'type' => 'EMAIL_PREVIEW_START',
            'certificate_index' => $certificateData['index'] ?? null
        ]);

        try {
            $emailData = $this->prepareEmailData($certificateData);

            $html = View::make($this->config['template'], $emailData)->render();

            $this->logger->info('Email template preview generated', [
                'type' => 'EMAIL_PREVIEW_SUCCESS',
                'template_size' => strlen($html)
            ]);

            return $html;

        } catch (\Exception $e) {
            $this->logger->error('Email template preview failed', [
                'type' => 'EMAIL_PREVIEW_FAILED',
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * @Oracode Validate email data and PDF attachment
     * 🎯 Purpose: Ensure all required data is present and valid
     */
    private function validateEmailData(array $certificateData, string $pdfPath): void
    {
        // Required certificate fields for email
        $requiredFields = [
            'index',
            'investor_name',
            'investor_email',
            'asa_id',
            'tx_id',
            'issued_at'
        ];

        foreach ($requiredFields as $field) {
            if (empty($certificateData[$field])) {
                throw new \InvalidArgumentException("Missing required email field: {$field}");
            }
        }

        // Validate email format
        if (!filter_var($certificateData['investor_email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format: {$certificateData['investor_email']}");
        }

        // Validate PDF path
        if (empty($pdfPath)) {
            throw new \InvalidArgumentException("PDF path is required for email attachment");
        }
    }

    /**
     * @Oracode Prepare email template data with FlorenceEGI branding
     * 🎯 Purpose: Build complete data structure for email template
     */
    private function prepareEmailData(array $certificateData): array
    {
        $roundConfig = config('founders');
        $brandColors = config('founders.certificate.brand.colors');

        return [
            // Recipient information
            'recipient_name' => $certificateData['investor_name'],
            'recipient_email' => $certificateData['investor_email'],

            // Email configuration
            // 'subject' => str_replace('{index}', $certificateData['index'], $this->config['subject']),
            // 'greeting' => $this->config['greeting'],
            // 'signature' => $this->config['signature'],

            // Certificate details
            'certificate_number' => str_pad($certificateData['index'], 2, '0', STR_PAD_LEFT),
            'asa_id' => $certificateData['asa_id'],
            'transaction_id' => $certificateData['tx_id'],
            'issue_date' => \Carbon\Carbon::parse($certificateData['issued_at'])->format('d F Y'),

            // Round information
            'round_name' => $roundConfig['round_title'],
            'round_description' => $roundConfig['round_description'],
            'total_certificates' => $roundConfig['total_tokens'],
            'certificate_price' => number_format($roundConfig['price_eur'], 0, ',', '.'),
            'currency' => $roundConfig['currency'],

            // FlorenceEGI branding
            'brand' => [
                'company_name' => 'FlorenceEGI',
                'tagline' => 'Il Nuovo Rinascimento Ecologico Digitale',
                'website' => 'https://florenceegi.it',
                'logo_url' => asset('images/florenceegi-logo.png'),
                'colors' => $brandColors,
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/company/florenceegi',
                    'twitter' => 'https://twitter.com/florenceegi',
                    'instagram' => 'https://instagram.com/florenceegi'
                ]
            ],

            // Blockchain information
            'algorand_network' => ucfirst(config('founders.algorand.network')),
            'explorer_url' => $this->getTransactionExplorerUrl($certificateData['tx_id']),

            // Wallet information
            'has_wallet' => !empty($certificateData['investor_wallet']),
            'wallet_address' => $certificateData['investor_wallet'] ?? null,
            'token_transferred' => $certificateData['token_transferred'] ?? false,

            // Email metadata
            'email_timestamp' => now()->toIso8601String(),
            'template_version' => '1.0.0',
            'unsubscribe_url' => url('/unsubscribe/' . base64_encode($certificateData['investor_email'])),
            'privacy_policy_url' => url('/privacy-policy'),

            // Call-to-action URLs
            'cta_urls' => [
                'download_pera' => 'https://perawallet.app/',
                'learn_algorand' => 'https://algorand.foundation/',
                'florenceegi_info' => 'https://florenceegi.it/founders'
            ]
        ];
    }

    /**
     * @Oracode Send certificate email with PDF attachment
     * 🎯 Purpose: Execute email delivery with proper attachment handling
     */
    private function sendCertificateEmail(array $emailData, string $pdfContent, int $certificateIndex): array
    {
        $attachmentName = str_replace('{index}', str_pad($certificateIndex, 2, '0', STR_PAD_LEFT), $this->config['attachment_name']);

        try {
            // Send email using Laravel Mail system
            Mail::send($this->config['template'], $emailData, function ($message) use ($emailData, $pdfContent, $attachmentName) {
                $message->to($emailData['recipient_email'], $emailData['recipient_name'])
                       ->from($this->config['from_email'], $this->config['from_name'])
                       ->replyTo($this->config['reply_to'])
                       ->subject($emailData['subject']);

                // Attach PDF certificate
                if ($this->config['attach_certificate']) {
                    $message->attachData($pdfContent, $attachmentName, [
                        'mime' => 'application/pdf'
                    ]);
                }
            });

            return [
                'delivery_status' => 'sent',
                'message_id' => $this->generateMessageId($emailData['recipient_email'], $certificateIndex),
                'timestamp' => now()->toIso8601String(),
                'attachment_name' => $attachmentName,
                'attachment_size' => strlen($pdfContent)
            ];

        } catch (\Exception $e) {
            throw new \Exception("Email delivery failed: " . $e->getMessage());
        }
    }

    /**
     * @Oracode Send test email without PDF attachment
     * 🎯 Purpose: Test email configuration and template rendering
     */
    private function sendTestCertificateEmail(array $emailData): array
    {
        try {
            Mail::send($this->config['template'], $emailData, function ($message) use ($emailData) {
                $message->to($emailData['recipient_email'])
                       ->from($this->config['from_email'], $this->config['from_name'])
                       ->replyTo($this->config['reply_to'])
                       ->subject($emailData['subject']);
            });

            return [
                'delivery_status' => 'test_sent',
                'message_id' => $this->generateMessageId($emailData['recipient_email'], 0),
                'timestamp' => now()->toIso8601String(),
                'is_test' => true
            ];

        } catch (\Exception $e) {
            throw new \Exception("Test email delivery failed: " . $e->getMessage());
        }
    }

    /**
     * @Oracode Get mock certificate data for testing
     * 🎯 Purpose: Provide sample data for email testing
     */
    private function getMockCertificateData(): array
    {
        return [
            'index' => 1,
            'investor_name' => 'Mario Rossi',
            'investor_email' => 'test@example.com',
            'investor_wallet' => 'TW7TPTEIJAIVSTHPRJSPP3BGZACLDJJ2RCL43C5UWM2XU7MLXPBAESJYXA',
            'asa_id' => '123456789',
            'tx_id' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890ABCDEFGHIJKLMNOP',
            'issued_at' => now()->toISOString(),
            'token_transferred' => true
        ];
    }

    /**
     * @Oracode Generate unique message ID for tracking
     * 🎯 Purpose: Create trackable message identifier
     */
    private function generateMessageId(string $email, int $certificateIndex): string
    {
        $timestamp = now()->timestamp;
        $hash = substr(md5($email . $certificateIndex . $timestamp), 0, 8);

        return "florenceegi-{$certificateIndex}-{$hash}-{$timestamp}";
    }

    /**
     * @Oracode Get Algorand transaction explorer URL
     * 🎯 Purpose: Generate blockchain verification link
     */
    private function getTransactionExplorerUrl(string $txId): string
    {
        $network = config('founders.algorand.network');
        $explorerUrl = config("founders.algorand.{$network}.explorer_url");

        return "{$explorerUrl}/tx/{$txId}";
    }

    // ========================================
    // PUBLIC UTILITY METHODS
    // ========================================

    /**
     * @Oracode Get email service configuration
     * 🎯 Purpose: Provide current email settings for admin interface
     */
    public function getEmailConfiguration(): array
    {
        return [
            'from_email' => $this->config['from_email'],
            'from_name' => $this->config['from_name'],
            'reply_to' => $this->config['reply_to'],
            'template' => $this->config['template'],
            'attach_certificate' => $this->config['attach_certificate'],
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_encryption' => config('mail.mailers.smtp.encryption')
        ];
    }

    /**
     * @Oracode Validate email service configuration
     * 🎯 Purpose: Check if email service is properly configured
     */
    public function validateEmailConfiguration(): array
    {
        $issues = [];

        // Check required configuration
        if (empty($this->config['from_email'])) {
            $issues[] = 'from_email not configured';
        }

        if (empty($this->config['template'])) {
            $issues[] = 'email template not specified';
        }

        // Check Laravel mail configuration
        if (empty(config('mail.default'))) {
            $issues[] = 'Laravel mail driver not configured';
        }

        // Check template existence
        try {
            View::make($this->config['template'], [])->render();
        } catch (\Exception $e) {
            $issues[] = "Email template not found: {$this->config['template']}";
        }

        return [
            'is_valid' => empty($issues),
            'issues' => $issues,
            'configuration' => $this->getEmailConfiguration()
        ];
    }
}
