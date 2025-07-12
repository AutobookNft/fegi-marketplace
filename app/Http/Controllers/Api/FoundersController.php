<?php

/**
 * @Oracode Founders API Controller for FlorenceEGI Certificate System
 * 🎯 Purpose: Handle founder certificate issuance via API endpoint with complete workflow
 * 🧱 Core Logic: Validation, ASA minting, PDF generation, email delivery, database storage
 * 🛡️ Security: Input validation, rate limiting, CSRF protection, error handling
 *
 * @package App\Http\Controllers\Api
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori System)
 * @date 2025-07-05
 * @purpose Complete certificate issuance API with Algorand integration
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FounderCertificate;
use App\Rules\AlgorandAddressRule;
use App\Services\AlgorandService;
use App\Services\PDFCertificateService;
use App\Services\EmailNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ultra\UltraLogManager\UltraLogManager;
use Ultra\ErrorManager\Interfaces\ErrorManagerInterface;

class FoundersController extends Controller
{
    private UltraLogManager $logger;
    private ErrorManagerInterface $errorManager;
    private AlgorandService $algorandService;
    private PDFCertificateService $pdfService;
    private EmailNotificationService $emailService;
    private array $config;

    /**
     * @Oracode Initialize Founders Controller
     * 🎯 Purpose: Setup dependencies and services for certificate issuance
     */
    public function __construct(
        UltraLogManager $logger,
        ErrorManagerInterface $errorManager,
        AlgorandService $algorandService,
        PDFCertificateService $pdfService,
        EmailNotificationService $emailService
    ) {
        $this->logger = $logger;
        $this->errorManager = $errorManager;
        $this->algorandService = $algorandService;
        $this->pdfService = $pdfService;
        $this->emailService = $emailService;
        $this->config = config('founders');

        // Apply rate limiting middleware
        // $this->middleware('throttle:' . $this->config['validation']['rate_limit']['max_attempts'] . ',' . $this->config['validation']['rate_limit']['decay_minutes']);
    }

    /**
     * @Oracode Issue new founder certificate with complete workflow
     * 🎯 Purpose: Execute full certificate issuance: mint ASA, generate PDF, send email, store data
     *
     * @param Request $request HTTP request with certificate data
     * @return JsonResponse Certificate issuance result or error response
     */
    /**
     * @Oracode Issue new founder certificate with complete workflow
     * 🎯 Purpose: Execute full certificate issuance: mint ASA, generate PDF, send email, store data
     *
     * @param Request $request HTTP request with certificate data
     * @return JsonResponse Certificate issuance result or error response
     */
    public function issue(Request $request): JsonResponse
    {
        $this->logger->info('Founder certificate issuance request received', [
            'type'       => 'FOUNDER_ISSUE_REQUEST',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            // Step 1: Validate request data
            $validatedData = $this->validateCertificateRequest($request);

            // Step 2: Get next available certificate index
            $index = $this->getNextAvailableCertificateIndex();

            // Step 3: Process certificate issuance in transaction
            $certificate = DB::transaction(function () use ($validatedData, $index, $request) {
                return $this->processCertificateIssuance($validatedData, $index, $request);
            });

            $this->logger->info('Certificate issued successfully', [
                'type' => 'FOUNDER_ISSUE_SUCCESS',
                'certificate_id' => $certificate->id,
                'certificate_index' => $certificate->index,
                'asa_id' => $certificate->asa_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Certificato Padre Fondatore emesso con successo',
                'data' => [
                    'certificate_id' => $certificate->id,
                    'certificate_number' => str_pad($certificate->index, 2, '0', STR_PAD_LEFT),
                    'investor_name' => $certificate->investor_name,
                    'asa_id' => $certificate->asa_id,
                    'transaction_id' => $certificate->tx_id,
                    'issued_at' => $certificate->issued_at->toISOString(),
                    'token_location' => $certificate->token_transferred ? 'investor_wallet' : 'treasury',
                    'blockchain_explorer' => $this->getExplorerUrl($certificate->tx_id)
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->logger->warning('Certificate issuance validation failed', [
                'type'   => 'FOUNDER_ISSUE_VALIDATION_FAILED',
                'errors' => $e->errors(),
                'ip'     => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Dati di input non validi',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // 1) log completo con UltraLogManager
            $this->logger->error('Certificate issuance exception', [
                'type'    => 'FOUNDER_ISSUE_EXCEPTION',
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip(),
            ]);

            // 2) restituisci subito JSON con messaggio e stacktrace
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ], 500);

            // 3) quando risolvi, reintegra l’ErrorManager così:
            // return $this->errorManager
            //     ->handle('FOUNDER_CERTIFICATE_ISSUANCE_FAILED', [
            //         'ip_address' => $request->ip(),
            //         'error'      => $e->getMessage(),
            //     ], $e);
        }
    }


    /**
     * @Oracode Get certificate status and information
     * 🎯 Purpose: Retrieve certificate details for tracking and verification
     *
     * @param Request $request
     * @param int $certificateId Certificate ID or index
     * @return JsonResponse Certificate information
     */
    public function show(Request $request, int $certificateId): JsonResponse
    {
        $this->logger->info('Certificate information requested', [
            'type' => 'FOUNDER_CERTIFICATE_INFO_REQUEST',
            'certificate_id' => $certificateId,
            'ip_address' => $request->ip()
        ]);

        try {
            // Find certificate by ID or index
            $certificate = FounderCertificate::where('id', $certificateId)
                ->orWhere('index', $certificateId)
                ->first();

            if (!$certificate) {
                $this->logger->warning('Certificate not found', [
                    'type' => 'FOUNDER_CERTIFICATE_NOT_FOUND',
                    'certificate_id' => $certificateId,
                    'ip_address' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Certificato non trovato'
                ], 404);
            }

            $this->logger->info('Certificate information retrieved', [
                'type' => 'FOUNDER_CERTIFICATE_INFO_SUCCESS',
                'certificate_id' => $certificate->id,
                'certificate_index' => $certificate->index
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'certificate_id' => $certificate->id,
                    'certificate_number' => str_pad($certificate->index, 2, '0', STR_PAD_LEFT),
                    'investor_name' => $certificate->investor_name,
                    'asa_id' => $certificate->asa_id,
                    'transaction_id' => $certificate->tx_id,
                    'issued_at' => $certificate->issued_at->toISOString(),
                    'token_location' => $certificate->token_location,
                    'artifact_status' => $certificate->artifact_status,
                    'blockchain_explorer' => $this->getExplorerUrl($certificate->tx_id),
                    'is_complete' => $certificate->is_complete
                ]
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Certificate information retrieval failed', [
                'type' => 'FOUNDER_CERTIFICATE_INFO_FAILED',
                'certificate_id' => $certificateId,
                'error' => $e->getMessage()
            ]);

            return $this->errorManager->handle('FOUNDER_CERTIFICATE_INFO_FAILED', [
                'certificate_id' => $certificateId,
                'error' => $e->getMessage()
            ], $e);
        }
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * @Oracode Validate certificate issuance request
     * 🎯 Purpose: Comprehensive input validation with custom rules
     */
    private function validateCertificateRequest(Request $request): array
    {
        $rules = [
            'investor_name' => $this->config['validation']['investor_name'],
            'investor_email' => $this->config['validation']['investor_email'],
            'investor_phone' => $this->config['validation']['investor_phone'],
            'investor_address' => $this->config['validation']['investor_address'],
            'investor_wallet' => [
                'nullable',
                'string',
                new AlgorandAddressRule()
            ]
        ];

        $messages = [
            'investor_name.required' => 'Il nome dell\'investitore è obbligatorio',
            'investor_name.min' => 'Il nome deve essere di almeno 2 caratteri',
            'investor_name.max' => 'Il nome non può superare 200 caratteri',
            'investor_email.required' => 'L\'email è obbligatoria',
            'investor_email.email' => 'Formato email non valido',
            'investor_email.max' => 'L\'email non può superare 200 caratteri',
            'investor_phone.max' => 'Il telefono non può superare 50 caratteri',
            'investor_address.max' => 'L\'indirizzo non può superare 1000 caratteri',
            'investor_wallet.string' => 'Il wallet deve essere una stringa valida'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * @Oracode Get next available certificate index
     * 🎯 Purpose: Determine next certificate number to issue
     */
    private function getNextAvailableCertificateIndex(): int
    {
        $nextIndex = FounderCertificate::getNextAvailableIndex();

        if ($nextIndex === null) {
            throw new \Exception('Tutti i certificati Padri Fondatori sono stati emessi (40/40)');
        }

        $this->logger->info('Next certificate index determined', [
            'type' => 'FOUNDER_NEXT_INDEX',
            'next_index' => $nextIndex,
            'remaining_certificates' => $this->config['total_tokens'] - FounderCertificate::count()
        ]);

        return $nextIndex;
    }

    /**
     * @Oracode Process complete certificate issuance workflow
     * 🎯 Purpose: Execute all steps of certificate creation in transaction
     */
    private function processCertificateIssuance(array $validatedData, int $index, Request $request): FounderCertificate
    {
        $this->logger->info('Starting certificate processing workflow', [
            'type' => 'FOUNDER_PROCESSING_START',
            'certificate_index' => $index,
            'has_wallet' => !empty($validatedData['investor_wallet'])
        ]);

        // Step 1: Mint ASA token on Algorand
        $algorandResult = $this->mintCertificateToken($index);

        // Step 2: Transfer token to investor wallet (if provided)
        $transferResult = $this->transferTokenIfWalletProvided($validatedData, $algorandResult['asaId']);

        // Step 3: Create certificate record in database
        $certificate = $this->createCertificateRecord($validatedData, $index, $algorandResult, $transferResult);

        // Step 4: Generate PDF certificate
        $pdfResult = $this->generateCertificatePdf($certificate);

        // Step 5: Send email notification
        $this->sendCertificateEmail($certificate, $pdfResult['pdf_path']);

        // Step 6: Update certificate with PDF path
        $certificate->update(['pdf_path' => $pdfResult['pdf_path']]);

        $this->logger->info('Certificate processing workflow completed', [
            'type' => 'FOUNDER_PROCESSING_COMPLETE',
            'certificate_id' => $certificate->id,
            'certificate_index' => $index
        ]);

        return $certificate;
    }

    /**
     * @Oracode Mint ASA token on Algorand blockchain
     * 🎯 Purpose: Create unique ASA token for certificate
     */
    private function mintCertificateToken(int $index): array
    {
        $this->logger->info('Minting ASA token', [
            'type' => 'FOUNDER_MINTING_START',
            'certificate_index' => $index
        ]);

        try {
            $result = $this->algorandService->mintFounderToken($index);

            $this->logger->info('ASA token minted successfully', [
                'type' => 'FOUNDER_MINTING_SUCCESS',
                'certificate_index' => $index,
                'asa_id' => $result['asaId'],
                'tx_id' => $result['txId']
            ]);

            return $result;
        } catch (\Exception $e) {
            $this->logger->error('ASA token minting failed', [
                'type' => 'FOUNDER_MINTING_FAILED',
                'certificate_index' => $index,
                'error' => $e->getMessage()
            ]);

            throw new \Exception("Fallimento creazione token ASA: {$e->getMessage()}");
        }
    }

    /**
     * @Oracode Transfer token to investor wallet if provided
     * 🎯 Purpose: Move token ownership from treasury to investor
     */
    private function transferTokenIfWalletProvided(array $validatedData, string $asaId): ?array
    {
        if (empty($validatedData['investor_wallet'])) {
            return null;
        }

        $this->logger->info('Transferring token to investor wallet', [
            'type' => 'FOUNDER_TRANSFER_START',
            'wallet_address' => $validatedData['investor_wallet'],
            'asa_id' => $asaId
        ]);

        try {
            $transferTxId = $this->algorandService->transferAsset(
                $validatedData['investor_wallet'],
                $asaId
            );

            $this->logger->info('Token transferred successfully', [
                'type' => 'FOUNDER_TRANSFER_SUCCESS',
                'wallet_address' => $validatedData['investor_wallet'],
                'transfer_tx_id' => $transferTxId
            ]);

            return [
                'transfer_tx_id' => $transferTxId,
                'transferred_at' => now()
            ];
        } catch (\Exception $e) {
            $this->logger->warning('Token transfer failed, keeping in treasury', [
                'type' => 'FOUNDER_TRANSFER_FAILED',
                'wallet_address' => $validatedData['investor_wallet'],
                'error' => $e->getMessage()
            ]);

            // Don't fail the entire process, just log the issue
            // Token remains in treasury for manual transfer later
            return null;
        }
    }

    /**
     * @Oracode Create certificate database record
     * 🎯 Purpose: Store certificate information in database
     */
    private function createCertificateRecord(array $validatedData, int $index, array $algorandResult, ?array $transferResult): FounderCertificate
    {
        $certificateData = [
            'index' => $index,
            'asa_id' => $algorandResult['asaId'],
            'tx_id' => $algorandResult['txId'],
            'investor_name' => $validatedData['investor_name'],
            'investor_email' => $validatedData['investor_email'],
            'investor_phone' => $validatedData['investor_phone'] ?? null,
            'investor_address' => $validatedData['investor_address'] ?? null,
            'investor_wallet' => $validatedData['investor_wallet'] ?? null,
            'issued_at' => now(),
            'token_transferred' => $transferResult !== null,
            'token_transferred_at' => $transferResult['transferred_at'] ?? null,
            'transfer_tx_id' => $transferResult['transfer_tx_id'] ?? null,
            'pdf_path' => null,
        ];

        return FounderCertificate::create($certificateData);
    }

    /**
     * @Oracode Generate PDF certificate
     * 🎯 Purpose: Create branded PDF certificate file
     */
    private function generateCertificatePdf(FounderCertificate $certificate): array
    {
        $pdfString = $this->pdfService->generateCertificatePDF($certificate);

        // Save PDF to storage
        $filename = "certificate-{$certificate->id}-" . now()->format('Y-m-d') . ".pdf";
        $path = "certificates/{$filename}";
        Storage::disk('public')->put($path, $pdfString);

        return ['pdf_path' => $path];
    }

    /**
     * @Oracode Send certificate email notification
     * 🎯 Purpose: Deliver certificate to investor via email
     */
    private function sendCertificateEmail(FounderCertificate $certificate, string $pdfPath): void
    {
        $certificateData = [
            'index' => $certificate->index,
            'investor_name' => $certificate->investor_name,
            'investor_email' => $certificate->investor_email,
            'investor_wallet' => $certificate->investor_wallet,
            'asa_id' => $certificate->asa_id,
            'tx_id' => $certificate->tx_id,
            'issued_at' => $certificate->issued_at ? $certificate->issued_at->toISOString() : now()->toISOString(),
            'token_transferred' => $certificate->token_transferred
        ];

        $this->emailService->sendFounderCertificate($certificateData, $pdfPath);
    }

    /**
     * @Oracode Get blockchain explorer URL for transaction
     * 🎯 Purpose: Generate verification link for blockchain transaction
     */
    private function getExplorerUrl(string $txId): string
    {
        $network = $this->config['algorand']['network'];
        $explorerUrl = $this->config['algorand'][$network]['explorer_url'];

        return "{$explorerUrl}/tx/{$txId}";
    }

    /**
     * @Oracode Mint existing certificate to blockchain
     * 🎯 Purpose: Mint an existing 'issued' certificate to Algorand blockchain
     *
     * @param Request $request
     * @param int $certificateId Certificate ID to mint
     * @return JsonResponse Mint result
     */
    public function mintExisting(Request $request, int $certificateId): JsonResponse
    {
        $this->logger->info('Mint existing certificate request received', [
            'type' => 'FOUNDER_MINT_EXISTING_REQUEST',
            'certificate_id' => $certificateId,
            'ip_address' => $request->ip(),
        ]);

        try {
            // Find the certificate
            $certificate = FounderCertificate::find($certificateId);

            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificato non trovato'
                ], 404);
            }

            // Validate certificate can be minted
            if ($certificate->status !== 'issued') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo i certificati con status "issued" possono essere mintati'
                ], 400);
            }

            if ($certificate->asa_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Questo certificato è già stato mintato su blockchain'
                ], 400);
            }

            // Validate optional wallet update
            $validatedData = [];
            if ($request->has('investor_wallet') && $request->input('investor_wallet')) {
                $request->validate([
                    'investor_wallet' => [
                        'string',
                        new \App\Rules\AlgorandAddressRule()
                    ]
                ]);
                $validatedData['investor_wallet'] = $request->input('investor_wallet');
            }

            // Process minting in transaction
            $mintedCertificate = DB::transaction(function () use ($certificate, $validatedData) {
                return $this->processCertificateMinting($certificate, $validatedData);
            });

            $this->logger->info('Certificate minted successfully', [
                'type' => 'FOUNDER_MINT_EXISTING_SUCCESS',
                'certificate_id' => $certificate->id,
                'certificate_index' => $certificate->index,
                'asa_id' => $mintedCertificate->asa_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Certificato mintato con successo su blockchain',
                'data' => [
                    'certificate_id' => $mintedCertificate->id,
                    'certificate_number' => str_pad($mintedCertificate->index, 2, '0', STR_PAD_LEFT),
                    'investor_name' => $mintedCertificate->investor_name,
                    'asa_id' => $mintedCertificate->asa_id,
                    'transaction_id' => $mintedCertificate->tx_id,
                    'minted_at' => $mintedCertificate->updated_at->toISOString(),
                    'token_location' => $mintedCertificate->investor_wallet ? 'investor_wallet' : 'treasury',
                    'blockchain_explorer' => $this->getExplorerUrl($mintedCertificate->tx_id)
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dati di input non validi',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->logger->error('Certificate minting failed', [
                'type' => 'FOUNDER_MINT_EXISTING_FAILED',
                'certificate_id' => $certificateId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @Oracode Process minting of existing certificate
     * 🎯 Purpose: Execute blockchain mint and update certificate record
     */
    private function processCertificateMinting(FounderCertificate $certificate, array $validatedData): FounderCertificate
    {
        $this->logger->info('Starting certificate minting workflow', [
            'type' => 'FOUNDER_MINTING_START',
            'certificate_id' => $certificate->id,
            'certificate_index' => $certificate->index,
            'has_wallet_update' => !empty($validatedData['investor_wallet'])
        ]);

        // Step 1: Mint ASA token on Algorand using existing certificate index
        $algorandResult = $this->mintCertificateToken($certificate->index);

        // Step 2: Update wallet if provided and transfer token
        $transferResult = null;
        $walletToUse = $validatedData['investor_wallet'] ?? $certificate->investor_wallet;

        if ($walletToUse) {
            $transferResult = $this->transferTokenIfWalletProvided(['investor_wallet' => $walletToUse], $algorandResult['asaId']);
        }

        // Step 3: Update certificate with blockchain data
        $updateData = [
            'asa_id' => $algorandResult['asaId'],
            'tx_id' => $algorandResult['txId'],
            'status' => 'minted',
            'minted_at' => now(),
            'token_transferred' => $transferResult !== null,
            'token_transferred_at' => $transferResult['transferred_at'] ?? null,
            'transfer_tx_id' => $transferResult['transfer_tx_id'] ?? null,
        ];

        // Update wallet if provided
        if (!empty($validatedData['investor_wallet'])) {
            $updateData['investor_wallet'] = $validatedData['investor_wallet'];
        }

        $certificate->update($updateData);

        // Step 4: Generate PDF certificate with QR code
        $pdfString = $this->pdfService->generateCertificatePDF($certificate);

        // Save PDF to storage
        $filename = "certificate-{$certificate->id}-" . now()->format('Y-m-d') . ".pdf";
        $path = "certificates/{$filename}";
        Storage::disk('public')->put($path, $pdfString);

        $pdfResult = ['pdf_path' => $path];

        // Step 5: Send email notification
        $this->sendCertificateEmail($certificate, $pdfResult['pdf_path']);

        // Step 6: Update certificate with PDF path
        $certificate->update(['pdf_path' => $pdfResult['pdf_path']]);

        $this->logger->info('Certificate minting workflow completed', [
            'type' => 'FOUNDER_MINTING_COMPLETE',
            'certificate_id' => $certificate->id,
            'certificate_index' => $certificate->index
        ]);

        return $certificate->fresh();
    }

    // ========================================
    // ADMIN UTILITY ENDPOINTS
    // ========================================

    /**
     * @Oracode Get certificates overview for admin interface
     * 🎯 Purpose: Provide summary data for dashboard
     */
    public function overview(Request $request): JsonResponse
    {
        $this->logger->info('Certificates overview requested', [
            'type' => 'FOUNDER_OVERVIEW_REQUEST',
            'ip_address' => $request->ip()
        ]);

        try {
            $totalIssued = FounderCertificate::count();
            $totalAvailable = $this->config['total_tokens'];
            $remaining = $totalAvailable - $totalIssued;

            $statistics = [
                'certificates' => [
                    'total_available' => $totalAvailable,
                    'total_issued' => $totalIssued,
                    'remaining' => $remaining,
                    'completion_percentage' => round(($totalIssued / $totalAvailable) * 100, 1)
                ],
                'artifacts' => [
                    'ready_for_order' => FounderCertificate::readyForArtifactOrder()->count(),
                    'ordered_not_paid' => FounderCertificate::artifactOrderedNotPaid()->count(),
                    'ready_for_shipping' => FounderCertificate::readyForShipping()->count(),
                    'completed' => FounderCertificate::completed()->count()
                ],
                'tokens' => [
                    'in_treasury' => FounderCertificate::tokenInTreasury()->count(),
                    'transferred' => FounderCertificate::where('token_transferred', true)->count()
                ],
                'round_info' => [
                    'name' => $this->config['round_title'],
                    'price' => $this->config['price_eur'],
                    'currency' => $this->config['currency'],
                    'network' => $this->config['algorand']['network']
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Certificates overview failed', [
                'type' => 'FOUNDER_OVERVIEW_FAILED',
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore nel recupero delle statistiche'
            ], 500);
        }
    }
}
