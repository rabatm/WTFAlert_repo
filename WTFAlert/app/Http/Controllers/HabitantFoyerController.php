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
}
