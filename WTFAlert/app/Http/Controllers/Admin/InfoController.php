<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mairie;
use App\Models\Secteur;

class InfoController extends Controller
{
    /**
     * Affiche la page d'information
     */
    public function index()
    {
        // 1. Récupérer les données
        $mairies = Mairie::with('secteurs')->get();
        $totalSecteurs = Secteur::count();

        // 2. Préparer les données pour la vue
        $data = [
            'title' => 'Informations WTFAlert',
            'description' => 'Système d\'alerte pour les coupures d\'eau',
            'mairies' => $mairies,
            'totalMairies' => $mairies->count(),
            'totalSecteurs' => $totalSecteurs,
            'dateActuelle' => now()->format('d/m/Y H:i'),
            'version' => '1.0.0'
        ];

        // 3. Retourner la vue avec les données
        return view('info', $data);
    }

    /**
     * Affiche une mairie spécifique
     */
    public function show($id)
    {
        $mairie = Mairie::with('secteurs')->findOrFail($id);

        return view('info.show', [
            'mairie' => $mairie,
            'title' => 'Détails de ' . $mairie->nom
        ]);
    }
}
