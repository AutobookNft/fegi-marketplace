<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @Oracode Collection Controller for FlorenceEGI Founders System
 * 🎯 Purpose: Gestione completa delle collections/eventi per certificati
 * 🧱 Core Logic: CRUD operations, validation, business logic
 * 🛡️ Security: Input validation, authorization, error handling
 *
 * @package App\Http\Controllers
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Collections Management)
 * @date 2025-07-11
 * @purpose Complete collection management for founder certificates
 */
class CollectionController extends Controller
{
    /**
     * Display a listing of collections.
     */
    public function index()
    {
        $collections = Collection::orderBy('created_at', 'desc')->paginate(10);

        return view('founders.collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new collection.
     */
    public function create()
    {
        return view('founders.collections.create');
    }

    /**
     * Store a newly created collection in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'total_tokens' => 'required|integer|min:1|max:1000',
            'base_price' => 'required|numeric|min:0.01|max:999999.99',
            'currency' => 'required|string|in:EUR,USD,ALGO',
            'metadata' => 'nullable|array',
            'metadata.title' => 'nullable|string|max:255',
            'metadata.description' => 'nullable|string|max:1000',
            'metadata.image' => 'nullable|url',
            'metadata.external_url' => 'nullable|url',
            'metadata.properties' => 'nullable|array',
            'treasury_address' => 'nullable|string|max:255',
            'status' => 'required|in:draft,active,paused,completed,cancelled',
            'event_date' => 'nullable|date|after:now',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after:sale_start_date',
            'allow_wallet_payments' => 'boolean',
            'allow_fiat_payments' => 'boolean',
            'min_symbolic_price' => 'nullable|numeric|min:0.000001|max:1',
            'requires_shipping' => 'boolean',
            'shipping_info' => 'nullable|array',
            'shipping_info.supplier_name' => 'nullable|string|max:255',
            'shipping_info.supplier_contact' => 'nullable|string|max:255',
            'shipping_info.estimated_delivery_days' => 'nullable|integer|min:1|max:365',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);

        // Ensure slug is unique
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Collection::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Set defaults
        $validated['available_tokens'] = $validated['total_tokens'];
        $validated['certificates_issued'] = 0;
        $validated['total_revenue'] = 0;

        // Set treasury address from config if not provided
        if (empty($validated['treasury_address'])) {
            $validated['treasury_address'] = config('founders.algorand.treasury_address');
        }

        $collection = Collection::create($validated);

        return redirect()->route('founders.collections.show', $collection)
            ->with('success', 'Collection creata con successo!');
    }

    /**
     * Display the specified collection.
     */
    public function show(Collection $collection)
    {
        $collection->load('founderCertificates');

        return view('founders.collections.show', compact('collection'));
    }

    /**
     * Show the form for editing the specified collection.
     */
    public function edit(Collection $collection)
    {
        return view('founders.collections.edit', compact('collection'));
    }

    /**
     * Update the specified collection in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'total_tokens' => 'required|integer|min:1|max:1000',
            'base_price' => 'required|numeric|min:0.01|max:999999.99',
            'currency' => 'required|string|in:EUR,USD,ALGO',
            'metadata' => 'nullable|array',
            'metadata.title' => 'nullable|string|max:255',
            'metadata.description' => 'nullable|string|max:1000',
            'metadata.image' => 'nullable|url',
            'metadata.external_url' => 'nullable|url',
            'metadata.properties' => 'nullable|array',
            'treasury_address' => 'nullable|string|max:255',
            'status' => 'required|in:draft,active,paused,completed,cancelled',
            'event_date' => 'nullable|date',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after:sale_start_date',
            'allow_wallet_payments' => 'boolean',
            'allow_fiat_payments' => 'boolean',
            'min_symbolic_price' => 'nullable|numeric|min:0.000001|max:1',
            'requires_shipping' => 'boolean',
            'shipping_info' => 'nullable|array',
            'shipping_info.supplier_name' => 'nullable|string|max:255',
            'shipping_info.supplier_contact' => 'nullable|string|max:255',
            'shipping_info.estimated_delivery_days' => 'nullable|integer|min:1|max:365',
        ]);

        // Update slug if name changed
        if ($collection->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            // Ensure slug is unique (excluding current collection)
            $baseSlug = $validated['slug'];
            $counter = 1;
            while (Collection::where('slug', $validated['slug'])->where('id', '!=', $collection->id)->exists()) {
                $validated['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }
        }

        // Update available tokens if total tokens changed
        if ($collection->total_tokens !== $validated['total_tokens']) {
            $difference = $validated['total_tokens'] - $collection->total_tokens;
            $validated['available_tokens'] = $collection->available_tokens + $difference;

            // Ensure available tokens is not negative
            if ($validated['available_tokens'] < 0) {
                $validated['available_tokens'] = 0;
            }
        }

        $collection->update($validated);

        return redirect()->route('founders.collections.show', $collection)
            ->with('success', 'Collection aggiornata con successo!');
    }

    /**
     * Remove the specified collection from storage.
     */
    public function destroy(Collection $collection)
    {
        // Check if collection has certificates
        if ($collection->founderCertificates()->count() > 0) {
            return redirect()->route('founders.collections.index')
                ->with('error', 'Impossibile eliminare una collection con certificati associati.');
        }

        $collection->delete();

        return redirect()->route('founders.collections.index')
            ->with('success', 'Collection eliminata con successo!');
    }

    /**
     * Activate a collection (set status to active).
     */
    public function activate(Collection $collection)
    {
        $collection->update(['status' => 'active']);

        return redirect()->route('founders.collections.show', $collection)
            ->with('success', 'Collection attivata con successo!');
    }

    /**
     * Pause a collection (set status to paused).
     */
    public function pause(Collection $collection)
    {
        $collection->update(['status' => 'paused']);

        return redirect()->route('founders.collections.show', $collection)
            ->with('success', 'Collection messa in pausa!');
    }

    /**
     * Complete a collection (set status to completed).
     */
    public function complete(Collection $collection)
    {
        $collection->update(['status' => 'completed']);

        return redirect()->route('founders.collections.show', $collection)
            ->with('success', 'Collection completata!');
    }

    /**
     * Cancel a collection (set status to cancelled).
     */
    public function cancel(Collection $collection)
    {
        $collection->update(['status' => 'cancelled']);

        return redirect()->route('founders.collections.show', $collection)
            ->with('success', 'Collection annullata!');
    }

    /**
     * Generate certificates for a collection.
     */
    public function generateCertificates(Collection $collection)
    {
        // Verifica che non ci siano già certificati
        if ($collection->founderCertificates()->count() > 0) {
            return redirect()->route('founders.collections.show', $collection)
                ->with('error', 'I certificati per questa collection sono già stati generati.');
        }

        // Verifica che la collection sia valida
        if ($collection->total_tokens <= 0) {
            return redirect()->route('founders.collections.show', $collection)
                ->with('error', 'La collection deve avere almeno 1 token.');
        }

        // Genera i certificati vuoti
        $certificatesCreated = 0;
        for ($i = 1; $i <= $collection->total_tokens; $i++) {
            \App\Models\FounderCertificate::create([
                'collection_id' => $collection->id,
                'index' => $i,
                'certificate_title' => $collection->name . " #" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'base_price' => $collection->base_price,
                'currency' => $collection->currency,
                'status' => 'draft',
                'metadata' => $collection->metadata ? $collection->metadata : [
                    'title' => $collection->name . " #" . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'description' => 'Certificato Padre Fondatore FlorenceEGI - Nuovo Rinascimento Ecologico Digitale',
                    'certificate_number' => $i,
                    'collection_name' => $collection->name
                ],
            ]);
            $certificatesCreated++;
        }

        return redirect()->route('founders.collections.show', $collection)
            ->with('success', "Generati {$certificatesCreated} certificati per la collection '{$collection->name}'.");
    }

    /**
     * Get collections for API/AJAX calls.
     */
    public function api()
    {
        $collections = Collection::select(['id', 'name', 'slug', 'status', 'total_tokens', 'available_tokens', 'base_price', 'currency'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($collections);
    }

    /**
     * Get active collections available for sale.
     */
    public function available()
    {
        $collections = Collection::onSale()
            ->select(['id', 'name', 'slug', 'status', 'total_tokens', 'available_tokens', 'base_price', 'currency'])
            ->get();

        return response()->json($collections);
    }
}
