<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Notifications\NouvelleAlerte;

class AlerteController extends Controller
{
    public function index(Request $request)
    {
        $query = Alerte::with(['photos', 'habitant:id,nom_hb,prenom_hb']);

        // Filtrage par type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtrage par statut
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        $alertes = $query->latest()->paginate(15);

        return response()->json($alertes);
    }

    public function store(Request $request)
    {
        \Log::debug('Files received:', $request->allFiles());
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:info,warning,alert',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'localisation' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'anonyme' => 'nullable|boolean',
            'photos.*' => 'nullable|file|mimes:jpeg,png,jpg|max:50480',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $alerte = Alerte::create([
            'habitant_id' => auth()->id(),
            'type' => $request->type,
            'titre' => $request->titre,
            'description' => $request->description,
            'localisation' => $request->localisation,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'anonyme' => $request->anonyme ?? false,
        ]);

        // Traitement des photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $nomFichier = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                $chemin = $photo->storeAs('alertes/' . $alerte->id, $nomFichier, 'private');
                
                Photo::create([
                    'alerte_id' => $alerte->id,
                    'chemin' => $chemin,
                    'nom_original' => $photo->getClientOriginalName(),
                    'mime_type' => $photo->getMimeType(),
                    'taille' => $photo->getSize(),
                ]);
            }
        }

        // Notification aux administrateurs
        $this->notifierAdministrateurs($alerte);

        return response()->json([
            'message' => 'Alerte créée avec succès',
            'alerte' => $alerte->load('photos')
        ], 201);
    }

    public function show($id)
    {
        $alerte = Alerte::with(['photos', 'habitant:id,nom_hb,prenom_hb'])->findOrFail($id);
        
        return response()->json($alerte);
    }

    public function update(Request $request, $id)
    {
        $alerte = Alerte::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à modifier cette alerte
        if (auth()->id() !== $alerte->habitant_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'localisation' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'anonyme' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $alerte->update($request->only([
            'titre', 'description', 'localisation', 'latitude', 'longitude', 'anonyme'
        ]));

        return response()->json([
            'message' => 'Alerte mise à jour avec succès',
            'alerte' => $alerte
        ]);
    }

    public function destroy($id)
    {
        $alerte = Alerte::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à supprimer cette alerte
        if (auth()->id() !== $alerte->habitant_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Supprimer les photos associées
        foreach ($alerte->photos as $photo) {
            Storage::disk('private')->delete($photo->chemin);
        }

        $alerte->delete();

        return response()->json(['message' => 'Alerte supprimée avec succès']);
    }

    public function getPhoto($id)
    {
        $photo = Photo::findOrFail($id);
        
        // Vérifier l'autorisation
        $alerte = $photo->alerte;
        
        if (!$alerte || (auth()->id() !== $alerte->habitant_id && !auth()->user()->isAdmin())) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if (!Storage::disk('private')->exists($photo->chemin)) {
            return response()->json(['error' => 'Fichier non trouvé'], 404);
        }

        return response()->file(
            Storage::disk('private')->path($photo->chemin),
            ['Content-Type' => $photo->mime_type]
        );
    }

private function notifierAdministrateurs(Alerte $alerte)
    {
        // List of admin emails
        $adminEmails = ["martin.rabat@gmail.com"];
        \Mail::raw('Test message', function($message) {
            $message->to('martin.rabat@gmail.com')
                ->subject('Test Email');
        });
        // Send notification using the Notification facade with route method
        try {
            \Illuminate\Support\Facades\Notification::route('mail', $adminEmails)
                ->notify(new \App\Notifications\NouvelleAlerte($alerte));
            \Log::info('Notification attempted');
        } catch (\Exception $e) {
            \Log::error('Mail error: ' . $e->getMessage());
        }
    }
}
