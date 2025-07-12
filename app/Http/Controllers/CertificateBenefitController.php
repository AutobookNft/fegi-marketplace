<?php

namespace App\Http\Controllers;

use App\Models\CertificateBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateBenefitController extends Controller
{
    /**
     * Display a listing of certificate benefits.
     */
    public function index()
    {
        $benefits = CertificateBenefit::orderBy('category')
            ->orderBy('title')
            ->paginate(20);

        return view('founders.benefits.index', compact('benefits'));
    }

    /**
     * Show the form for creating a new benefit.
     */
    public function create()
    {
        $categories = CertificateBenefit::getCategories();
        $icons = CertificateBenefit::getIcons();
        $colors = CertificateBenefit::getColors();

        return view('founders.benefits.create', compact('categories', 'icons', 'colors'));
    }

    /**
     * Store a newly created benefit in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|in:physical,digital,utility,vip,exclusive',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'metadata' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $benefit = CertificateBenefit::create($validated);

        return redirect()->route('founders.benefits.show', $benefit)
            ->with('success', 'Beneficio creato con successo!');
    }

    /**
     * Display the specified benefit.
     */
    public function show(CertificateBenefit $benefit)
    {
        $benefit->load('collections');

        return view('founders.benefits.show', compact('benefit'));
    }

    /**
     * Show the form for editing the specified benefit.
     */
    public function edit(CertificateBenefit $benefit)
    {
        $categories = CertificateBenefit::getCategories();
        $icons = CertificateBenefit::getIcons();
        $colors = CertificateBenefit::getColors();

        return view('founders.benefits.edit', compact('benefit', 'categories', 'icons', 'colors'));
    }

    /**
     * Update the specified benefit in storage.
     */
    public function update(Request $request, CertificateBenefit $benefit)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|in:physical,digital,utility,vip,exclusive',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'metadata' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $benefit->update($validated);

        return redirect()->route('founders.benefits.show', $benefit)
            ->with('success', 'Beneficio aggiornato con successo!');
    }

    /**
     * Remove the specified benefit from storage.
     */
    public function destroy(CertificateBenefit $benefit)
    {
        // Verifica se il beneficio è utilizzato in qualche collection
        if ($benefit->collections()->count() > 0) {
            return redirect()->route('founders.benefits.index')
                ->with('error', 'Impossibile eliminare un beneficio utilizzato in una o più collection.');
        }

        $benefitTitle = $benefit->title;
        $benefit->delete();

        return redirect()->route('founders.benefits.index')
            ->with('success', "Beneficio '{$benefitTitle}' eliminato con successo.");
    }

    /**
     * Toggle active status of the benefit.
     */
    public function toggleActive(CertificateBenefit $benefit)
    {
        $benefit->update(['is_active' => !$benefit->is_active]);

        $status = $benefit->is_active ? 'attivato' : 'disattivato';

        return redirect()->route('founders.benefits.show', $benefit)
            ->with('success', "Beneficio {$status} con successo!");
    }
}
