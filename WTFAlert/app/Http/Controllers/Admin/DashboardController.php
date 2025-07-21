<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collectivite;
use App\Models\Foyer;
use App\Models\Habitant;
use App\Models\Alerte;
use App\Models\DemandeModification;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord de l'administration.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'collectivites' => Collectivite::count(),
            'foyers' => Foyer::count(),
            'habitants' => Habitant::count(),
            'alertes' => Alerte::count(),
            'demandes' => DemandeModification::where('statut', 'en_attente')->count(),
        ];

        $recentAlertes = Alerte::with('habitant')
            ->latest()
            ->take(5)
            ->get();

        $recentDemandes = DemandeModification::with(['user', 'habitant'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentAlertes' => $recentAlertes,
            'recentDemandes' => $recentDemandes,
        ]);
    }
}
