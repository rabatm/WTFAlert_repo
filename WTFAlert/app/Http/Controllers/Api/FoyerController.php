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
        $user = Auth::user();
        \Log::info('User ID: ' . ($user ? $user->id : 'null'));

        $habitant = $user->habitant;
        \Log::info('Habitant ID: ' . ($habitant ? $habitant->id : 'null'));

        $foyers = $habitant ? $habitant->foyers()->get() : collect();
        \Log::info('Foyers count: ' . $foyers->count());

        return response()->json([
            'user_id' => $user ? $user->id : null,
            'habitant_id' => $habitant ? $habitant->id : null,
            'foyers' => $foyers
        ]);
    }
}
