<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Oracode Wallet Authentication Middleware for Founders System
 * 🎯 Purpose: Protect dashboard routes with wallet-based authentication
 * 🧱 Core Logic: Only Treasury wallet can access protected routes
 * 🛡️ Security: Session-based wallet validation, redirect to connection page
 *
 * @package App\Http\Middleware
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Wallet Authentication)
 * @date 2025-07-09
 * @purpose Secure wallet-based access control for Founders dashboard
 */
class WalletAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get wallet address from session
        $connectedWallet = $request->session()->get('wallet_address');
        $treasuryWallet = config('founders.algorand.treasury_address');

        // Check if wallet is connected
        if (!$connectedWallet) {
            return $this->redirectToWalletConnection($request, 'Connessione wallet richiesta per accedere alla dashboard');
        }

        // Validate treasury wallet
        if ($connectedWallet !== $treasuryWallet) {
            // Clear invalid session
            $request->session()->forget('wallet_address');

            return $this->redirectToWalletConnection(
                $request,
                'Solo il wallet Treasury può accedere alla dashboard Founders'
            );
        }

        // Wallet is valid, proceed with request
        return $next($request);
    }

    /**
     * Redirect to wallet connection page with error message
     *
     * @param Request $request
     * @param string $message
     * @return Response
     */
    private function redirectToWalletConnection(Request $request, string $message): Response
    {
        // Generate HTTPS URL for wallet connection
        $walletConnectUrl = route('founders.wallet.connect', [], true);

        // Force HTTPS and port 8443 for PeraWallet compatibility
        if (config('app.env') !== 'production') {
            $walletConnectUrl = str_replace('http://', 'https://', $walletConnectUrl);
            $walletConnectUrl = str_replace(':9000', ':8443', $walletConnectUrl);
            // Also handle case where URL doesn't have port
            if (!str_contains($walletConnectUrl, ':8443')) {
                $walletConnectUrl = str_replace('https://localhost', 'https://localhost:8443', $walletConnectUrl);
            }
        }

        // For AJAX requests, return JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error' => $message,
                'redirect' => $walletConnectUrl
            ], 401);
        }

        // For regular requests, redirect with flash message
        return redirect($walletConnectUrl)
            ->with('error', $message);
    }
}
