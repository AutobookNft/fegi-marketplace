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

    // Wallet connection routes
    Route::post('/wallet/connect', function (Illuminate\Http\Request $request) {
        $walletAddress = $request->input('wallet_address');
        $treasuryWallet = config('founders.algorand.treasury_address');

        if ($walletAddress === $treasuryWallet) {
            session(['wallet_address' => $walletAddress]);
            return redirect()->back()->with('success', 'Treasury wallet connesso con successo');
        } else {
            return redirect()->back()->with('error', 'Solo il Treasury wallet può accedere al sistema');
        }
    })->name('wallet.connect');

    Route::post('/wallet/disconnect', function (Illuminate\Http\Request $request) {
        $request->session()->forget('wallet_address');
        return redirect()->back()->with('success', 'Wallet disconnesso');
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
