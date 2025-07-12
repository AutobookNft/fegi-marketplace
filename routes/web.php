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

        // Certificates routes
        Route::get('/certificates', [App\Http\Controllers\CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/create', [App\Http\Controllers\CertificateController::class, 'create'])->name('certificates.create');
        Route::post('/certificates', [App\Http\Controllers\CertificateController::class, 'store'])->name('certificates.store');
        Route::get('/certificates/{certificate}', [App\Http\Controllers\CertificateController::class, 'show'])->name('certificates.show');
        Route::get('/certificates/{certificate}/edit', [App\Http\Controllers\CertificateController::class, 'edit'])->name('certificates.edit');
        Route::put('/certificates/{certificate}', [App\Http\Controllers\CertificateController::class, 'update'])->name('certificates.update');
        Route::delete('/certificates/{certificate}', [App\Http\Controllers\CertificateController::class, 'destroy'])->name('certificates.destroy');

        // Certificate actions
        Route::post('/certificates/{certificate}/mark-ready', [App\Http\Controllers\CertificateController::class, 'markAsReady'])->name('certificates.mark-ready');
        Route::post('/certificates/{certificate}/assign-investor', [App\Http\Controllers\CertificateController::class, 'assignToInvestor'])->name('certificates.assign-investor');
        Route::post('/certificates/{certificate}/prepare-mint', [App\Http\Controllers\CertificateController::class, 'prepareForMinting'])->name('certificates.prepare-mint');

        // PDF routes
        Route::get('/certificates/{certificate}/pdf', [App\Http\Controllers\CertificateController::class, 'generatePdf'])->name('certificates.generate-pdf');
        Route::get('/certificates/{certificate}/pdf/stream', [App\Http\Controllers\CertificateController::class, 'streamPdf'])->name('certificates.stream-pdf');

        // Public URL generation
        Route::get('/certificates/{certificate}/public-url', [App\Http\Controllers\CertificateController::class, 'getPublicUrlAjax'])->name('certificates.public-url');

        // Test PDF route (mock certificate)
        Route::get('/test-pdf', function () {
            // Crea un certificato mock per testare il template PDF
            $certificate = new stdClass();
            $certificate->id = 1;
            $certificate->index = 1;
            $certificate->certificate_title = 'Certificato di Test FlorenceEGI';
            $certificate->base_price = 250.00;
            $certificate->currency = 'EUR';
            $certificate->investor_name = 'Marco Rossi';
            $certificate->investor_email = 'marco.rossi@email.com';
            $certificate->investor_phone = '+39 123 456 789';
            $certificate->investor_address = 'Via del Corso, 123 - 50123 Firenze (FI)';
            $certificate->investor_wallet = 'ABCDEFGHIJKLMNOP1234567890ABCDEFGHIJKLMNOP1234567890';
            $certificate->asa_id = '123456789';
            $certificate->tx_id = 'ABCDEF123456789ABCDEF123456789ABCDEF123456789ABCDEF123456789';
            $certificate->issued_at = now();

            // Collection mock
            $collection = new stdClass();
            $collection->name = 'Padri Fondatori - Prima Emissione';
            $collection->slug = 'padri-fondatori-prima-emissione';

            // Benefits mock
            $benefits = collect([
                (object) [
                    'title' => 'Prisma Olografico FlorenceEGI',
                    'description' => 'Accesso esclusivo al prisma olografico che rappresenta la convergenza di arte, tecnologia e sostenibilità.',
                    'icon' => 'gem',
                    'color' => 'emerald',
                ],
                (object) [
                    'title' => 'Zero Fee su Emissione 1 EGI',
                    'description' => 'Esenzione totale dalle commissioni per l\'emissione di 1 EGI con supporto EPP completo.',
                    'icon' => 'zap',
                    'color' => 'blue',
                ],
                (object) [
                    'title' => 'Accesso VIP Eventi FlorenceEGI',
                    'description' => 'Ingresso prioritario a tutti gli eventi esclusivi FlorenceEGI, conferenze e workshop.',
                    'icon' => 'star',
                    'color' => 'purple',
                ],
            ]);

            $collection->activeBenefits = $benefits;
            $certificate->collection = $collection;

            return view('pdf.founder-certificate', [
                'certificate' => $certificate,
                'generated_at' => now(),
            ]);
        })->name('test.pdf');

        // DEBUG: Route per controllare i dati reali
        Route::get('/debug-certificates', function () {
            $certificates = \App\Models\FounderCertificate::with(['collection.activeBenefits'])->get();

            $debug = [
                'total_certificates' => $certificates->count(),
                'certificates_with_collection' => $certificates->where('collection')->count(),
                'certificates_with_benefits' => $certificates->filter(function ($cert) {
                    return $cert->collection && $cert->collection->activeBenefits->count() > 0;
                })->count(),
                'total_collections' => \App\Models\Collection::count(),
                'collections_with_benefits' => \App\Models\Collection::with('activeBenefits')->get()->filter(function ($coll) {
                    return $coll->activeBenefits->count() > 0;
                })->count(),
                'total_benefits' => \App\Models\CertificateBenefit::count(),
                'active_benefits' => \App\Models\CertificateBenefit::where('is_active', true)->count(),
                'certificates_details' => $certificates->map(function ($cert) {
                    return [
                        'id' => $cert->id,
                        'index' => $cert->index,
                        'investor_name' => $cert->investor_name,
                        'collection_name' => $cert->collection->name ?? 'NO COLLECTION',
                        'benefits_count' => $cert->collection?->activeBenefits?->count() ?? 0,
                        'status' => $cert->status,
                    ];
                })->toArray()
            ];

            return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
        })->name('debug.certificates');

        // Test PDF Info Page
        Route::get('/test-pdf-info', function () {
            return view('founders.test-pdf-info');
        })->name('test.pdf-info');

        // Treasury Management
        Route::get('/treasury', function () {
            return view('founders.treasury.index');
        })->name('treasury.index');

        // Collection Management
        Route::get('/collections', [App\Http\Controllers\CollectionController::class, 'index'])->name('collections.index');
        Route::get('/collections/create', [App\Http\Controllers\CollectionController::class, 'create'])->name('collections.create');
        Route::post('/collections', [App\Http\Controllers\CollectionController::class, 'store'])->name('collections.store');
        Route::get('/collections/{collection}', [App\Http\Controllers\CollectionController::class, 'show'])->name('collections.show');
        Route::get('/collections/{collection}/edit', [App\Http\Controllers\CollectionController::class, 'edit'])->name('collections.edit');
        Route::put('/collections/{collection}', [App\Http\Controllers\CollectionController::class, 'update'])->name('collections.update');
        Route::delete('/collections/{collection}', [App\Http\Controllers\CollectionController::class, 'destroy'])->name('collections.destroy');

        // Collection Status Actions
        Route::post('/collections/{collection}/activate', [App\Http\Controllers\CollectionController::class, 'activate'])->name('collections.activate');
        Route::post('/collections/{collection}/pause', [App\Http\Controllers\CollectionController::class, 'pause'])->name('collections.pause');
        Route::post('/collections/{collection}/complete', [App\Http\Controllers\CollectionController::class, 'complete'])->name('collections.complete');
        Route::post('/collections/{collection}/cancel', [App\Http\Controllers\CollectionController::class, 'cancel'])->name('collections.cancel');

        // Collection Certificate Generation
        Route::post('/collections/{collection}/generate-certificates', [App\Http\Controllers\CollectionController::class, 'generateCertificates'])->name('collections.generate-certificates');

        // Benefits Management
        Route::get('/benefits', [App\Http\Controllers\CertificateBenefitController::class, 'index'])->name('benefits.index');
        Route::get('/benefits/create', [App\Http\Controllers\CertificateBenefitController::class, 'create'])->name('benefits.create');
        Route::post('/benefits', [App\Http\Controllers\CertificateBenefitController::class, 'store'])->name('benefits.store');
        Route::get('/benefits/{benefit}', [App\Http\Controllers\CertificateBenefitController::class, 'show'])->name('benefits.show');
        Route::get('/benefits/{benefit}/edit', [App\Http\Controllers\CertificateBenefitController::class, 'edit'])->name('benefits.edit');
        Route::put('/benefits/{benefit}', [App\Http\Controllers\CertificateBenefitController::class, 'update'])->name('benefits.update');
        Route::delete('/benefits/{benefit}', [App\Http\Controllers\CertificateBenefitController::class, 'destroy'])->name('benefits.destroy');
        Route::post('/benefits/{benefit}/toggle-active', [App\Http\Controllers\CertificateBenefitController::class, 'toggleActive'])->name('benefits.toggle-active');

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

// Route di test per mPDF
Route::get('/test-mpdf', function () {
    $certificate = App\Models\FounderCertificate::with('collection.certificateBenefits')->first();

    if (!$certificate) {
        return response()->json(['error' => 'Nessun certificato trovato'], 404);
    }

    $pdfService = new App\Services\PDFCertificateService();
    return $pdfService->streamCertificatePDF($certificate);
})->name('test.mpdf');

// ========================================
// PUBLIC CERTIFICATE ROUTES
// ========================================

// Route pubblico per visualizzare il certificato (con hash per sicurezza)
Route::get('/certificate/{id}/{hash}', [App\Http\Controllers\CertificateController::class, 'showPublic'])
    ->name('certificate.public');

// Route di test per generare link pubblico
Route::get('/test-public-certificate/{id?}', function ($id = null) {
    $certificate = App\Models\FounderCertificate::with(['collection.certificateBenefits'])
        ->when($id, function ($query, $id) {
            return $query->where('id', $id);
        })
        ->first();

    if (!$certificate) {
        return response()->json(['error' => 'Certificato non trovato'], 404);
    }

    $controller = new App\Http\Controllers\CertificateController();
    $publicUrl = $controller->getPublicUrl($certificate);

    return response()->json([
        'certificate_id' => $certificate->id,
        'investor_name' => $certificate->investor_name,
        'public_url' => $publicUrl,
        'direct_link_https' => str_replace('http://localhost:8000', 'https://localhost:8443', $publicUrl),
        'click_to_view' => '<a href="' . str_replace('http://localhost:8000', 'https://localhost:8443', $publicUrl) . '" target="_blank">🌐 Visualizza Certificato HTTPS</a>'
    ], 200, [], JSON_PRETTY_PRINT);
})->name('test.public-certificate');
