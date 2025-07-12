<?php

/**
 * @Oracode API Routes for FlorenceEGI Founders System
 * 🎯 Purpose: Define API endpoints for founder certificate issuance and management
 * 🧱 Core Logic: RESTful API design, rate limiting, middleware protection
 * 🛡️ Security: CSRF protection, throttling, input validation
 *
 * @package Routes
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori API Routes)
 * @date 2025-07-05
 * @purpose API routes for complete founder certificate workflow
 */

use App\Http\Controllers\Api\FoundersController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return ['message' => 'API works!'];
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned the "api" middleware group. Make something great!
|
*/

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'FlorenceEGI Founders API',
        'version' => '1.0.0'
    ]);
});

// Founders Certificate API Routes
Route::prefix('founders')->group(function () {

    /**
     * @Oracode Issue new founder certificate
     * POST /api/founders/issue
     *
     * Request Body:
     * - investor_name (required|string|max:200)
     * - investor_email (required|email|max:200)
     * - investor_phone (nullable|string|max:50)
     * - investor_address (nullable|string|max:1000)
     * - investor_wallet (nullable|string|algorand_address)
     *
     * Response: Certificate data with ASA ID, transaction ID, PDF URL
     */
    Route::post('/issue', [FoundersController::class, 'issue'])
        ->name('api.founders.issue');

    /**
     * @Oracode Mint existing certificate to blockchain
     * POST /api/founders/{certificateId}/mint
     *
     * Request Body:
     * - investor_wallet (optional|string|algorand_address) - Update wallet if provided
     *
     * Response: Mint result with ASA ID, transaction ID, PDF URL
     */
    Route::post('/{certificateId}/mint', [FoundersController::class, 'mintExisting'])
        ->where('certificateId', '[0-9]+')
        ->name('api.founders.mint-existing');

    /**
     * @Oracode Get certificate information
     * GET /api/founders/{certificateId}
     *
     * Parameters:
     * - certificateId: Certificate ID or index number
     *
     * Response: Certificate details and status
     */
    Route::get('/{certificateId}', [FoundersController::class, 'show'])
        ->where('certificateId', '[0-9]+')
        ->name('api.founders.show');

    /**
     * @Oracode Get certificates overview and statistics
     * GET /api/founders/overview
     *
     * Response: Dashboard statistics for admin interface
     */
    Route::get('/overview', [FoundersController::class, 'overview'])
        ->name('api.founders.overview');

    /**
     * @Oracode Get all collections
     * GET /api/founders/collections
     *
     * Response: All collections with basic information
     */
    Route::get('/collections', [App\Http\Controllers\CollectionController::class, 'api'])
        ->name('api.founders.collections');

    /**
     * @Oracode Get collections available for sale
     * GET /api/founders/collections/available
     *
     * Response: Collections currently available for certificate issuance
     */
    Route::get('/collections/available', [App\Http\Controllers\CollectionController::class, 'available'])
        ->name('api.founders.collections.available');
});

// Wallet Connection API Routes
Route::prefix('wallet')->middleware(['web'])->group(function () {

    /**
     * @Oracode Connect PeraWallet and validate treasury address
     * POST /api/wallet/connect
     *
     * Request Body:
     * - wallet_address (required|string|algorand_address)
     *
     * Response: Connection status and session data
     */
    Route::post('/connect', [WalletController::class, 'connect'])
        ->name('api.wallet.connect');

    /**
     * @Oracode Disconnect wallet and clear session
     * POST /api/wallet/disconnect
     *
     * Response: Disconnection confirmation
     */
    Route::post('/disconnect', [WalletController::class, 'disconnect'])
        ->name('api.wallet.disconnect');

    /**
     * @Oracode Get current wallet connection status
     * GET /api/wallet/status
     *
     * Response: Current wallet connection state
     */
    Route::get('/status', [WalletController::class, 'status'])
        ->name('api.wallet.status');
});

// Additional utility routes for testing and configuration
Route::prefix('founders/test')->group(function () {

    /**
     * @Oracode Test Algorand service connectivity
     * GET /api/founders/test/algorand
     *
     * Response: Network status and treasury information
     */
    Route::get('/algorand', function (Request $request) {
        try {
            $algorandService = app(\App\Services\AlgorandService::class);

            $networkStatus = $algorandService->getNetworkStatus();
            $treasuryStatus = $algorandService->getTreasuryStatus();

            return response()->json([
                'success' => true,
                'network' => [
                    'status' => 'connected',
                    'last_round' => $networkStatus['last-round'],
                    'network_name' => config('founders.algorand.network')
                ],
                'treasury' => [
                    'address' => $treasuryStatus['address'],
                    'balance_algos' => round($treasuryStatus['amount'] / 1000000, 6),
                    'assets_count' => count($treasuryStatus['assets'] ?? [])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Algorand service test failed',
                'message' => $e->getMessage()
            ], 500);
        }
    })->name('api.founders.test.algorand');

    /**
     * @Oracode Test email service configuration
     * GET /api/founders/test/email
     *
     * Response: Email configuration validation
     */
    Route::get('/email', function (Request $request) {
        try {
            $emailService = app(\App\Services\EmailNotificationService::class);
            $config = $emailService->getEmailConfiguration();
            $validation = $emailService->validateEmailConfiguration();

            return response()->json([
                'success' => true,
                'configuration' => $config,
                'validation' => $validation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Email service test failed',
                'message' => $e->getMessage()
            ], 500);
        }
    })->name('api.founders.test.email');

    /**
     * @Oracode Test PDF generation service
     * GET /api/founders/test/pdf
     *
     * Response: PDF service statistics and configuration
     */
    Route::get('/pdf', function (Request $request) {
        try {
            $pdfService = app(\App\Services\PDFCertificateService::class);
            $statistics = $pdfService->getServiceStatistics();

            return response()->json([
                'success' => true,
                'statistics' => $statistics,
                'dompdf_available' => class_exists('\Dompdf\Dompdf'),
                'storage_writable' => is_writable(storage_path('app/public'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'PDF service test failed',
                'message' => $e->getMessage()
            ], 500);
        }
    })->name('api.founders.test.pdf');
});

// Certificate verification routes (public, no authentication required)
Route::prefix('certificates')->group(function () {

    /**
     * @Oracode Verify certificate authenticity
     * GET /api/certificates/verify/{certificateIndex}
     *
     * Public endpoint for certificate verification via QR codes
     */
    Route::get('/verify/{certificateIndex}', function (Request $request, int $certificateIndex) {
        try {
            $certificate = \App\Models\FounderCertificate::where('index', $certificateIndex)->first();

            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificato non trovato'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'certificate' => [
                    'number' => str_pad($certificate->index, 2, '0', STR_PAD_LEFT),
                    'holder_name' => $certificate->investor_name,
                    'asa_id' => $certificate->asa_id,
                    'issued_date' => $certificate->issued_at->format('d/m/Y'),
                    'blockchain_network' => strtoupper(config('founders.algorand.network')),
                    'verification_status' => 'verified',
                    'round_name' => config('founders.round_title')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella verifica del certificato'
            ], 500);
        }
    })->where('certificateIndex', '[0-9]+')
        ->name('api.certificates.verify');
});

// Development and testing routes (only available in non-production environments)
if (app()->environment(['local', 'testing', 'staging'])) {

    Route::prefix('founders/dev')->group(function () {

        /**
         * @Oracode Generate certificate preview (HTML)
         * POST /api/founders/dev/preview-certificate
         *
         * Development tool for testing certificate template
         */
        Route::post('/preview-certificate', function (Request $request) {
            try {
                $pdfService = app(\App\Services\PDFCertificateService::class);

                $mockData = [
                    'index' => $request->input('index', 1),
                    'investor_name' => $request->input('name', 'Mario Rossi'),
                    'investor_email' => $request->input('email', 'mario.rossi@example.com'),
                    'asa_id' => $request->input('asa_id', '123456789'),
                    'tx_id' => $request->input('tx_id', 'SAMPLE-TRANSACTION-ID-FOR-PREVIEW'),
                    'issued_at' => now()->toISOString()
                ];

                $html = $pdfService->generateCertificatePreview($mockData);

                return response($html)->header('Content-Type', 'text/html');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Preview generation failed',
                    'message' => $e->getMessage()
                ], 500);
            }
        })->name('api.founders.dev.preview-certificate');

        /**
         * @Oracode Generate email preview (HTML)
         * POST /api/founders/dev/preview-email
         *
         * Development tool for testing email template
         */
        Route::post('/preview-email', function (Request $request) {
            try {
                $emailService = app(\App\Services\EmailNotificationService::class);

                $mockData = [
                    'index' => $request->input('index', 1),
                    'investor_name' => $request->input('name', 'Mario Rossi'),
                    'investor_email' => $request->input('email', 'mario.rossi@example.com'),
                    'investor_wallet' => $request->input('wallet'),
                    'asa_id' => $request->input('asa_id', '123456789'),
                    'tx_id' => $request->input('tx_id', 'SAMPLE-TRANSACTION-ID-FOR-PREVIEW'),
                    'issued_at' => now()->toISOString(),
                    'token_transferred' => $request->boolean('token_transferred', true)
                ];

                $html = $emailService->previewEmailTemplate($mockData);

                return response($html)->header('Content-Type', 'text/html');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Email preview generation failed',
                    'message' => $e->getMessage()
                ], 500);
            }
        })->name('api.founders.dev.preview-email');
    });
}
