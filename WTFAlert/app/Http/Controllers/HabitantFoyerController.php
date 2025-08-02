<?php

namespace App\Http\Controllers;

use App\Models\Habitant;
use Illuminate\Http\Request;

class HabitantFoyerController extends Controller
{
    /**
     * Affiche la liste des habitants avec leurs foyers.
     */
    public function index()
    {
        // Récupère tous les habitants avec leurs foyers associés
        $habitants = Habitant::with('foyers')->get();
        return view('habitants_foyers.index', compact('habitants'));
    }

    /**
     * Affiche la vue pour les administrateurs avec tous les foyers et habitants.
     */
    public function administres()
    {
        $foyers = \App\Models\Foyer::with(['habitants.user', 'secteurs'])->get();
        $secteurs = \App\Models\Secteur::orderBy('nom')->get();
        $foyersData = $foyers->map(function($foyer) {
            return [
                'foyer' => [
                    'id' => $foyer->id,
                    'nom' => $foyer->nom,
                    'adresse' => $foyer->adresse,
                    'numero_voie' => $foyer->numero_voie,
                    'complement_dadresse' => $foyer->complement_dadresse,
                    'code_postal' => $foyer->code_postal,
                    'ville' => $foyer->ville,
                    'telephone_fixe' => $foyer->telephone_fixe,
                    'info' => $foyer->info,
                    'animaux' => $foyer->animaux,
                    'latitude' => $foyer->latitude,
                    'longitude' => $foyer->longitude,
                    'internet' => $foyer->internet,
                    'non_connecte' => $foyer->non_connecte,
                    'vulnerable' => $foyer->vulnerable,
                    'indication' => $foyer->indication,
                    'periode_naissance' => $foyer->periode_naissance,
                    'collectivite_id' => $foyer->collectivite_id,
                    'secteurs' => $foyer->secteurs->pluck('nom')->toArray(),
                    'created_at' => $foyer->created_at,
                    'updated_at' => $foyer->updated_at,
                ],
                'habitants' => $foyer->habitants->map(function($habitant) {
                    return [
                        'id' => $habitant->id,
                        'nom' => $habitant->user ? $habitant->user->nom : null,
                        'prenom' => $habitant->user ? $habitant->user->prenom : null,
                        'email' => $habitant->user ? $habitant->user->email : null,
                        'telephone_mobile' => $habitant->user ? $habitant->user->telephone_mobile : null,
                        'adresse' => $habitant->adresse,
                    ];
                })
            ];
        });
        return view('administres', ['foyersData' => $foyersData, 'secteurs' => $secteurs]);
    }
}
