<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Foyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoyerController extends Controller
{
    // 1. Vérifier si l'utilisateur connecté est responsable du foyer
    public function isResponsable($foyerId)
    {
        $user = Auth::user();
        $habitant = $user->habitant;
        $isResponsable = $habitant
            ? $habitant->foyersResponsable()->where('foyer_id', $foyerId)->exists()
            : false;

        return response()->json(['is_responsable' => $isResponsable]);
    }

    // 2. Récupérer tous les habitants (users) d'un foyer
    public function habitants($foyerId)
    {
        $foyer = Foyer::findOrFail($foyerId);
        $habitants = $foyer->habitants()->with('user')->get();

        return response()->json($habitants);
    }

    // 3. Récupérer le secteur d'un foyer
    public function secteur($foyerId)
    {
        $foyer = Foyer::findOrFail($foyerId);
        return response()->json(['secteur' => $foyer->secteur]);
    }
    public function mesFoyers()
    {
        // Récupérer tous les foyers avec leurs habitants et users associés
        $foyers = \App\Models\Foyer::with(['habitants.user'])->get();

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
                    'secteur' => $foyer->secteur,
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

        return response()->json([
            'foyers_count' => $foyersData->count(),
            'foyers' => $foyersData
        ]);
    }
}
