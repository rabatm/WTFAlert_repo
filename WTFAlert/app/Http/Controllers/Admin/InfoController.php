<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collectivite;
use App\Models\Secteur;

class InfoController extends Controller
{
    /**
     * Affiche la page d'information
     */
    public function index()
    {
        // 1. Récupérer les données
        $collectivites = Collectivite::with('secteurs')->get();
        $totalSecteurs = Secteur::count();

        // 2. Préparer les données pour la vue
        $data = [
            'title' => 'Informations WTFAlert',
            'description' => 'Système d\'alerte pour les coupures d\'eau',
            'collectivites' => $collectivites,
            'totalCollectivites' => $collectivites->count(),
            'totalSecteurs' => $totalSecteurs,
            'dateActuelle' => now()->format('d/m/Y H:i'),
            'version' => '1.0.0'
        ];

        // 3. Retourner la vue avec les données
        return view('info', $data);
    }

    /**
     * Affiche une collectivite spécifique
     */
    public function show($id)
    {
        $collectivite = Collectivite::with('secteurs')->findOrFail($id);

        return view('info.show', [
            'Collectivite' => $collectivite,
            'title' => 'Détails de ' . $collectivite->nom
        ]);
    }
}
