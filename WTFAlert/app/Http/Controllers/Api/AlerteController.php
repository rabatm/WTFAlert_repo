<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NouvelleAlerteMail;

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
    
    // Valider les données
    $validator = Validator::make($request->all(), [
        'type' => 'required|in:info,danger,alert,accident',
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

    // Utiliser une transaction pour assurer la cohérence
    \DB::beginTransaction();
    
    try {
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

        // Traitement des photos - FIXED: Handle both single file and array of files
        $savedPhotos = [];
        
        if ($request->hasFile('photos')) {
            // Get the files - handle both single file and array
            $photoFiles = $request->file('photos');
            
            // If it's a single file, convert to array
            if (!is_array($photoFiles)) {
                $photoFiles = [$photoFiles];
            }
            
            \Log::info('Nombre de photos à traiter: ' . count($photoFiles));
            
            foreach ($photoFiles as $index => $photo) {
                $nomFichier = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                \Log::info("Traitement photo {$index}: {$nomFichier}");
                
                // Stocker le fichier
                $chemin = $photo->storeAs('alertes/' . $alerte->id, $nomFichier, 'private');
                
                // Créer l'entrée en base
                $photoEntry = Photo::create([
                    'alerte_id' => $alerte->id,
                    'chemin' => $chemin,
                    'nom_original' => $photo->getClientOriginalName(),
                    'mime_type' => $photo->getMimeType(),
                    'taille' => $photo->getSize(),
                ]);
                
                // Garder une trace des photos enregistrées
                $savedPhotos[] = $photoEntry;
                \Log::info("Photo {$index} enregistrée avec ID: {$photoEntry->id}");
            }
        }

        // Notification aux administrateurs
        $alerte->load('photos', 'habitant'); // Charger les photos et l'habitant avant l'envoi
        \Log::info('Sending notification for alert #' . $alerte->id . ' with ' . count($alerte->photos) . ' photos');
        $this->notifierAdministrateurs($alerte);
        
        // Si tout s'est bien passé, valider la transaction
        \DB::commit();
        
        // Reload the alert with photos to return the complete data
        $alerte->load('photos');
        
        return response()->json([
            'message' => 'Alerte créée avec succès',
            'alerte' => $alerte,
            'photos_count' => count($savedPhotos)
        ], 201);
        
    } catch (\Exception $e) {
        // En cas d'erreur, annuler la transaction
        \DB::rollBack();
        
        // Supprimer tous les fichiers qui ont été stockés
        if (isset($alerte) && $alerte->id) {
            Storage::disk('private')->deleteDirectory('alertes/' . $alerte->id);
        }
        
        \Log::error('Erreur lors de la création de l\'alerte: ' . $e->getMessage());
        return response()->json(['error' => 'Une erreur est survenue lors de la création de l\'alerte'], 500);
    }
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
        try {
            // Get admin emails from your configuration
            $adminEmails = ["martin.rabat@gmail.com","nicolas.cudel@gmail.com","patrick.gericault@gmail.com"];
            
            // Send emails synchronously (without queue)
            foreach ($adminEmails as $email) {
                Mail::to($email)->send(new NouvelleAlerteMail($alerte));
            }
                
            \Log::info('Admin notification sent successfully for alert #' . $alerte->id);
            
        } catch (\Exception $e) {
            // Log the error but don't let it break the alert creation
            \Log::error('Failed to send admin notification: ' . $e->getMessage());
            // Don't rethrow the exception so alert creation can still succeed
        }
    }
}
