<?php

namespace App\Http\Controllers;

use App\Models\FounderCertificate;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\PDFCertificateService;

/**
 * @Oracode Certificate Controller for FlorenceEGI Founders System
 * 🎯 Purpose: Gestione elegante e professionale dei certificati (fase Web2.0)
 * 🧱 Core Logic: CRUD certificates, elegant design, professional management
 * 🛡️ Security: Input validation, authorization, error handling
 *
 * @package App\Http\Controllers
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Certificate Management Web2.0)
 * @date 2025-07-11
 * @purpose Professional certificate management before blockchain minting
 */
class CertificateController extends Controller
{
    /**
     * Display a listing of certificates.
     */
    public function index(Request $request)
    {
        $query = FounderCertificate::with('collection')
            ->orderBy('collection_id')
            ->orderBy('index');

        // Filter by collection if specified
        if ($request->has('collection')) {
            $query->where('collection_id', $request->get('collection'));
        }

        // Filter by status if specified
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $certificates = $query->paginate(20);

        // Get collections for filter
        $collections = Collection::select('id', 'name')->get();

        return view('founders.certificates.index', compact('certificates', 'collections'));
    }

    /**
     * Show the form for creating a new certificate.
     */
    public function create(Request $request)
    {
        $collections = Collection::active()->get();
        $selectedCollection = null;

        if ($request->has('collection')) {
            $selectedCollection = Collection::find($request->get('collection'));
        }

        return view('founders.certificates.create', compact('collections', 'selectedCollection'));
    }

    /**
     * Store a newly created certificate in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'collection_id' => 'required|exists:collections,id',
            'certificate_title' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0.01|max:999999.99',
            'currency' => 'required|string|in:EUR,USD,ALGO',
            'status' => 'required|in:draft,ready',
            'metadata' => 'nullable|array',
            'metadata.title' => 'nullable|string|max:255',
            'metadata.description' => 'nullable|string|max:1000',
            'metadata.image' => 'nullable|url',
            'metadata.external_url' => 'nullable|url',
            'metadata.properties' => 'nullable|array',
        ]);

        $collection = Collection::findOrFail($validated['collection_id']);

        // Get next index for this collection
        $nextIndex = $collection->founderCertificates()->max('index') + 1;
        if ($nextIndex > $collection->total_tokens) {
            return redirect()->back()
                ->with('error', 'Numero massimo di certificati raggiunto per questa collection.');
        }

        $validated['index'] = $nextIndex;

        $certificate = FounderCertificate::create($validated);

        return redirect()->route('founders.certificates.show', $certificate)
            ->with('success', 'Certificato creato con successo!');
    }

    /**
     * Display the specified certificate.
     */
    public function show(FounderCertificate $certificate)
    {
        $certificate->load('collection');

        return view('founders.certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified certificate.
     */
    public function edit(FounderCertificate $certificate)
    {
        $certificate->load('collection');
        $collections = Collection::select('id', 'name')->get();

        return view('founders.certificates.edit', compact('certificate', 'collections'));
    }

    /**
     * Update the specified certificate in storage.
     */
    public function update(Request $request, FounderCertificate $certificate)
    {
        $validated = $request->validate([
            'certificate_title' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0.01|max:999999.99',
            'currency' => 'required|string|in:EUR,USD,ALGO',
            'status' => 'required|in:draft,ready,issued,minted,completed',
            'investor_name' => 'nullable|string|max:255',
            'investor_email' => 'nullable|email|max:255',
            'investor_phone' => 'nullable|string|max:50',
            'investor_address' => 'nullable|string|max:1000',
            'investor_wallet' => 'nullable|string|max:58',
            'metadata' => 'nullable|array',
            'metadata.title' => 'nullable|string|max:255',
            'metadata.description' => 'nullable|string|max:1000',
            'metadata.image' => 'nullable|url',
            'metadata.external_url' => 'nullable|url',
            'metadata.properties' => 'nullable|array',
        ]);

        $certificate->update($validated);

        return redirect()->route('founders.certificates.show', $certificate)
            ->with('success', 'Certificato aggiornato con successo!');
    }

    /**
     * Remove the specified certificate from storage.
     */
    public function destroy(FounderCertificate $certificate)
    {
        // Verifica che il certificato non sia già stato mintato
        if ($certificate->asa_id || $certificate->status === 'minted') {
            return redirect()->route('founders.certificates.index')
                ->with('error', 'Impossibile eliminare un certificato già mintato su blockchain.');
        }

        $certificateTitle = $certificate->certificate_title;
        $certificate->delete();

        return redirect()->route('founders.certificates.index')
            ->with('success', "Certificato '{$certificateTitle}' eliminato con successo.");
    }

    /**
     * Change certificate status to ready for sale.
     */
    public function markAsReady(FounderCertificate $certificate)
    {
        $certificate->update(['status' => 'ready']);

        return redirect()->route('founders.certificates.show', $certificate)
            ->with('success', 'Certificato contrassegnato come pronto per la vendita!');
    }

    /**
     * Assign certificate to an investor (mark as issued).
     */
    public function assignToInvestor(Request $request, FounderCertificate $certificate)
    {
        $validated = $request->validate([
            'investor_name' => 'required|string|max:255',
            'investor_email' => 'required|email|max:255',
            'investor_phone' => 'nullable|string|max:50',
            'investor_address' => 'nullable|string|max:1000',
            'investor_wallet' => 'nullable|string|max:58',
        ]);

        $validated['status'] = 'issued';
        $validated['issued_at'] = now();

        $certificate->update($validated);

        // Update collection statistics
        if ($certificate->collection) {
            $certificate->collection->increment('certificates_issued');
            $certificate->collection->increment('total_revenue', $certificate->base_price);
            $certificate->collection->decrement('available_tokens');
        }

        return redirect()->route('founders.certificates.show', $certificate)
            ->with('success', "Certificato assegnato a {$validated['investor_name']} con successo!");
    }

    /**
     * Generate PDF for certificate.
     */
    public function generatePdf(FounderCertificate $certificate)
    {
        try {
            // Controlla se DomPDF è disponibile
            if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                return redirect()->route('founders.certificates.stream-pdf', $certificate)
                    ->with('info', 'DomPDF non installato. Visualizzazione HTML alternativa.');
            }

            $pdfService = new PDFCertificateService();

            // Genera il PDF in stile pergamena rinascimentale
            $path = $pdfService->generateCertificatePDF($certificate);

            // Aggiorna il certificato con il path del PDF
            $certificate->update([
                'pdf_path' => $path,
                'pdf_generated_at' => now(),
            ]);

            // Download del PDF
            return response()->download(
                Storage::disk('public')->path($path),
                "Certificato-FlorenceEGI-{$certificate->id}.pdf"
            );
        } catch (\Exception $e) {
            // Se c'è un errore, mostra la vista HTML
            return redirect()->route('founders.certificates.stream-pdf', $certificate)
                ->with('warning', 'Errore PDF: ' . $e->getMessage() . ' - Visualizzazione HTML alternativa.');
        }
    }

    /**
     * Visualizza PDF del certificato nel browser
     */
    public function streamPdf(FounderCertificate $certificate)
    {
        try {
            $pdfService = new PDFCertificateService();
            return $pdfService->streamCertificatePDF($certificate);
        } catch (\Exception $e) {
            // Fallback: mostra la vista HTML se PDF non funziona
            $data = [
                'certificate' => $certificate->load(['collection.activeBenefits']),
                'generated_at' => now(),
                'show_pdf_warning' => true,
                'pdf_error' => $e->getMessage(),
            ];

            return view('pdf.founder-certificate', $data);
        }
    }

    /**
     * Prepare certificate for blockchain minting.
     */
    public function prepareForMinting(FounderCertificate $certificate)
    {
        if ($certificate->status !== 'issued') {
            return redirect()->route('founders.certificates.show', $certificate)
                ->with('error', 'Solo i certificati venduti possono essere preparati per il mint.');
        }

        // TODO: Implementare preparazione per mint
        return redirect()->route('founders.certificates.show', $certificate)
            ->with('info', 'Preparazione per mint blockchain sarà disponibile presto.');
    }

    /**
     * Get certificates statistics.
     */
    public function statistics()
    {
        $stats = [
            'total' => FounderCertificate::count(),
            'draft' => FounderCertificate::draft()->count(),
            'ready' => FounderCertificate::ready()->count(),
            'issued' => FounderCertificate::issued()->count(),
            'minted' => FounderCertificate::minted()->count(),
            'completed' => FounderCertificate::where('status', 'completed')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Show public certificate with security hash verification.
     */
    public function showPublic($id, $hash)
    {
        $certificate = FounderCertificate::with(['collection.certificateBenefits'])->findOrFail($id);

        // Verify security hash
        if (!$this->verifyPublicHash($certificate, $hash)) {
            abort(404, 'Certificato non trovato o link non valido.');
        }

        return view('certificates.public', compact('certificate'));
    }

    /**
     * Generate public URL for certificate.
     */
    public function getPublicUrl(FounderCertificate $certificate)
    {
        $hash = $this->generatePublicHash($certificate);

        return route('certificate.public', [
            'id' => $certificate->id,
            'hash' => $hash
        ]);
    }

    /**
     * Generate secure hash for public certificate access.
     */
    private function generatePublicHash(FounderCertificate $certificate)
    {
        $data = [
            'id' => $certificate->id,
            'investor_name' => $certificate->investor_name,
            'created_at' => $certificate->created_at?->timestamp,
            'collection_id' => $certificate->collection_id,
        ];

        return substr(hash('sha256', json_encode($data) . config('app.key')), 0, 16);
    }

    /**
     * Verify public certificate hash.
     */
    private function verifyPublicHash(FounderCertificate $certificate, string $hash)
    {
        return hash_equals($this->generatePublicHash($certificate), $hash);
    }

    /**
     * Get public URL for certificate via AJAX.
     */
    public function getPublicUrlAjax(FounderCertificate $certificate)
    {
        $url = $this->getPublicUrl($certificate);

        return response()->json([
            'url' => $url,
            'certificate_id' => $certificate->id,
            'investor_name' => $certificate->investor_name,
        ]);
    }
}
