<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Collection;
use App\Models\FounderCertificate;

class Dashboard extends Component
{
    public function render()
    {
        // Statistiche Collections
        $activeCollections = Collection::where('status', 'active')->get();
        $averagePrice = $activeCollections->count() > 0 ? $activeCollections->avg('base_price') : 250;

        $collectionsStats = [
            'total' => Collection::count(),
            'active' => Collection::where('status', 'active')->count(),
            'total_revenue' => Collection::sum('total_revenue') ?: 0,
            'total_certificates' => Collection::sum('certificates_issued') ?: 0,
            'total_capacity' => Collection::sum('total_tokens') ?: 0,
            'average_price' => $averagePrice
        ];

        // Attività recenti (ultime 10)
        $recentActivities = collect();

        // Collections create di recente
        $recentCollections = Collection::latest()
            ->take(5)
            ->get()
            ->map(function ($collection) {
                return [
                    'type' => 'collection_created',
                    'title' => 'Collection creata',
                    'description' => "Collection '{$collection->name}' creata con {$collection->total_tokens} token",
                    'timestamp' => $collection->created_at,
                    'icon' => 'folder_collection',
                    'color' => 'bg-blue-100 text-blue-800'
                ];
            });

        // Certificati emessi di recente
        $recentCertificates = FounderCertificate::with('collection')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($certificate) {
                return [
                    'type' => 'certificate_issued',
                    'title' => 'Certificato emesso',
                    'description' => "Certificato #{$certificate->index} per {$certificate->investor_name}" .
                        ($certificate->collection ? " (Collection: {$certificate->collection->name})" : ""),
                    'timestamp' => $certificate->created_at,
                    'icon' => 'certificate',
                    'color' => 'bg-emerald-100 text-emerald-800'
                ];
            });

        // Collections attivate di recente
        $recentlyActivated = Collection::where('status', 'active')
            ->where('updated_at', '>', now()->subDays(7))
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function ($collection) {
                return [
                    'type' => 'collection_activated',
                    'title' => 'Collection attivata',
                    'description' => "Collection '{$collection->name}' è ora attiva e disponibile per la vendita",
                    'timestamp' => $collection->updated_at,
                    'icon' => 'check_circle',
                    'color' => 'bg-green-100 text-green-800'
                ];
            });

        // Unisco tutte le attività e ordino per data
        $recentActivities = $recentCollections
            ->concat($recentCertificates)
            ->concat($recentlyActivated)
            ->sortByDesc('timestamp')
            ->take(10);

        return view('livewire.dashboard', [
            'collectionsStats' => $collectionsStats,
            'recentActivities' => $recentActivities
        ]);
    }
}
