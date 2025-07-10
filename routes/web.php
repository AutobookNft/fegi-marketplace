<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/', function () {
    return view('welcome');
});

// Route per il form Padri Fondatori
Route::get('/founders', function () {
    return view('founders-form');
})->name('founders.form');

// Route per test PeraWallet isolato
Route::get('/wallet-test', function () {
    return view('wallet-test');
})->name('wallet.test');

// ========================================
// FOUNDERS SYSTEM ROUTES
// ========================================

Route::prefix('founders')->name('founders.')->group(function () {

    // Wallet Connection (Public - no middleware)
    Route::get('/wallet', function () {
        return view('founders.wallet-connect');
    })->name('wallet.connect');

    // Test Menu (Public - for testing)
    Route::get('/test-menu', function () {
        return view('founders.test-menu');
    })->name('test.menu');

    // Test Sidebar (Public - for testing sidebar)
    Route::get('/test-sidebar', function () {
        return view('founders.test-sidebar');
    })->name('test.sidebar');

    // Wallet connection routes
    Route::get('/wallet/status', function (Illuminate\Http\Request $request) {
        $walletAddress = session('wallet_address');
        $treasuryWallet = config('founders.algorand.treasury_address');

        if ($walletAddress && $walletAddress === $treasuryWallet) {
            return response()->json([
                'connected' => true,
                'address' => $walletAddress
            ]);
        } else {
            return response()->json([
                'connected' => false,
                'address' => null
            ]);
        }
    })->name('wallet.status');

    Route::post('/wallet/connect', function (Illuminate\Http\Request $request) {
        $walletAddress = $request->input('wallet_address');
        $treasuryWallet = config('founders.algorand.treasury_address');

        if ($walletAddress === $treasuryWallet) {
            session(['wallet_address' => $walletAddress]);
            return response()->json([
                'success' => true,
                'message' => 'Treasury wallet connesso con successo'
                // Rimuovo redirect_url - sarà gestito dal JavaScript
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Solo il Treasury wallet può accedere al sistema'
            ], 403);
        }
    })->name('wallet.connect');

    Route::post('/wallet/disconnect', function (Illuminate\Http\Request $request) {
        $request->session()->forget('wallet_address');
        return response()->json([
            'success' => true,
            'message' => 'Wallet disconnesso'
        ]);
    })->name('wallet.disconnect');

    // Protected Dashboard Routes (Require wallet authentication)
    Route::middleware('wallet.auth')->group(function () {

        // Dashboard Overview
        Route::get('/dashboard', function () {
            return view('founders.dashboard');
        })->name('dashboard');

        // Certificate Management
        Route::get('/certificates', function () {
            return view('founders.certificates.index');
        })->name('certificates.index');

        Route::get('/certificates/create', function () {
            return view('founders.certificates.create');
        })->name('certificates.create');

        // Treasury Management
        Route::get('/treasury', function () {
            return view('founders.treasury.index');
        })->name('treasury.index');

        // Collection Management
        Route::get('/collections', function () {
            return view('founders.collections.index');
        })->name('collections');

        // Shipping & Tracking
        Route::get('/shipping', function () {
            return view('founders.shipping.index');
        })->name('shipping.index');

        // GDPR & Privacy
        Route::get('/privacy', function () {
            return view('founders.privacy.index');
        })->name('privacy.index');
    });
});
