<?php

/**
 * @Oracode Wallet Controller for PeraWallet Integration
 * 🎯 Purpose: Handle wallet connection, validation, and session management
 * 🧱 Core Logic: Treasury validation, session storage, security checks
 * 🛡️ Security: Treasury-only access, address validation, rate limiting
 *
 * @package App\Http\Controllers\Api
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Wallet Authentication System)
 * @date 2025-07-09
 * @purpose Secure wallet-based authentication for Founders System
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Ultra\UltraLogManager\UltraLogManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    private ?UltraLogManager $logger;

    public function __construct()
    {
        try {
            $this->logger = app(UltraLogManager::class);
        } catch (\Exception $e) {
            // Fallback se UltraLogManager non è disponibile
            $this->logger = null;
        }
    }

    /**
     * @Oracode Connect wallet and validate treasury address
     * POST /api/wallet/connect
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function connect(Request $request): JsonResponse
    {
        // Rate limiting per IP
        $key = 'wallet-connect:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $this->logger?->warning('Wallet connection rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Troppe richieste di connessione. Riprova tra qualche minuto.'
            ], 429);
        }

        RateLimiter::hit($key, 60); // 1 minuto di cooldown

        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'wallet_address' => [
                    'required',
                    'string',
                    'min:58',
                    'max:58',
                    'regex:/^[A-Z2-7]{58}$/' // Algorand address format
                ]
            ], [
                'wallet_address.required' => 'Indirizzo wallet richiesto',
                'wallet_address.string' => 'Formato indirizzo wallet non valido',
                'wallet_address.min' => 'Indirizzo wallet troppo corto',
                'wallet_address.max' => 'Indirizzo wallet troppo lungo',
                'wallet_address.regex' => 'Formato indirizzo Algorand non valido'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Dati di connessione non validi',
                    'details' => $validator->errors()->first()
                ], 400);
            }

            $walletAddress = $request->input('wallet_address');
            $treasuryAddress = config('founders.algorand.treasury_address');

            // Log tentativo connessione
            $this->logger?->info('Tentativo di connessione wallet', [
                'wallet_address' => $walletAddress,
                'treasury_address' => $treasuryAddress,
                'ip' => $request->ip()
            ]);

            // Validate treasury address
            if ($walletAddress !== $treasuryAddress) {
                $this->logger->warning('Connessione wallet non autorizzata', [
                    'attempted_wallet' => $walletAddress,
                    'treasury_wallet' => $treasuryAddress,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Accesso negato: Solo il wallet Treasury può accedere al sistema',
                    'details' => [
                        'wallet_connesso' => $this->truncateAddress($walletAddress),
                        'wallet_richiesto' => $this->truncateAddress($treasuryAddress),
                        'network' => config('founders.algorand.network')
                    ]
                ], 403);
            }

            // Store wallet address in session
            $request->session()->put('wallet_address', $walletAddress);
            $request->session()->put('wallet_connected_at', now()->toISOString());
            $request->session()->put('wallet_session_id', Str::uuid());

            $this->logger->info('Wallet connesso con successo', [
                'wallet_address' => $walletAddress,
                'session_id' => $request->session()->get('wallet_session_id')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wallet connesso con successo',
                'data' => [
                    'wallet_address' => $walletAddress,
                    'wallet_truncated' => $this->truncateAddress($walletAddress),
                    'network' => config('founders.algorand.network'),
                    'connected_at' => now()->toISOString(),
                    'session_expires' => now()->addHours(24)->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Errore durante connessione wallet', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Errore interno del server durante la connessione',
                'message' => app()->environment('production') ? 'Riprova più tardi' : $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Oracode Disconnect wallet and clear session
     * POST /api/wallet/disconnect
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function disconnect(Request $request): JsonResponse
    {
        try {
            $walletAddress = $request->session()->get('wallet_address');
            $sessionId = $request->session()->get('wallet_session_id');

            if ($walletAddress) {
                $this->logger->info('Wallet disconnesso', [
                    'wallet_address' => $walletAddress,
                    'session_id' => $sessionId
                ]);
            }

            // Clear wallet session data
            $request->session()->forget([
                'wallet_address',
                'wallet_connected_at',
                'wallet_session_id'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wallet disconnesso con successo'
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Errore durante disconnessione wallet', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Errore durante la disconnessione',
                'message' => app()->environment('production') ? 'Riprova più tardi' : $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Oracode Get current wallet connection status
     * GET /api/wallet/status
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function status(Request $request): JsonResponse
    {
        try {
            $walletAddress = $request->session()->get('wallet_address');
            $connectedAt = $request->session()->get('wallet_connected_at');
            $sessionId = $request->session()->get('wallet_session_id');

            if (!$walletAddress) {
                return response()->json([
                    'success' => true,
                    'connected' => false,
                    'message' => 'Nessun wallet connesso'
                ]);
            }

            $treasuryAddress = config('founders.algorand.treasury_address');
            $isAuthorized = $walletAddress === $treasuryAddress;

            return response()->json([
                'success' => true,
                'connected' => true,
                'authorized' => $isAuthorized,
                'data' => [
                    'wallet_address' => $walletAddress,
                    'wallet_truncated' => $this->truncateAddress($walletAddress),
                    'connected_at' => $connectedAt,
                    'session_id' => $sessionId,
                    'network' => config('founders.algorand.network'),
                    'is_treasury' => $isAuthorized
                ]
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Errore durante controllo status wallet', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Errore durante il controllo dello status',
                'message' => app()->environment('production') ? 'Riprova più tardi' : $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Oracode Truncate wallet address for display
     *
     * @param string $address
     * @return string
     */
    private function truncateAddress(string $address): string
    {
        if (strlen($address) <= 16) {
            return $address;
        }

        return substr($address, 0, 8) . '...' . substr($address, -8);
    }

    /**
     * Safe logging helper
     */
    private function safeLog(string $level, string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->$level($message, $context);
        }
    }
}
