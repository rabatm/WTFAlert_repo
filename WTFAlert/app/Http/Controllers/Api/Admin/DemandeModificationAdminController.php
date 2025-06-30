<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeModification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DemandeModificationAdminController extends Controller
{
    /**
     * Afficher la liste des demandes de modification
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = DemandeModification::with(['user', 'foyer', 'habitant'])
            ->orderBy('created_at', 'desc');

        // Filtrage par statut
        if ($request->has('statut') && in_array($request->statut, array_keys(DemandeModification::STATUTS))) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par type
        if ($request->has('type') && in_array($request->type, array_keys(DemandeModification::TYPES))) {
            $query->where('type', $request->type);
        }

        $demandes = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'status' => 'success',
            'data' => $demandes
        ]);
    }

    /**
     * Traiter une demande de modification
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function traiter(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approuver,rejeter',
            'reponse' => 'required_if:action,rejeter|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $demande = DemandeModification::findOrFail($id);
        
        if ($demande->statut !== 'en_attente') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette demande a déjà été traitée.'
            ], 400);
        }

        try {
            $statut = $request->action === 'approuver' ? 'approuvee' : 'rejetee';
            
            $updateData = [
                'statut' => $statut,
                'reponse_admin' => $request->reponse ?? null,
                'traitee_le' => now(),
                'admin_id' => auth()->id(),
            ];

            // Si la demande est approuvée, appliquer les modifications
            if ($statut === 'approuvee') {
                $this->appliquerModification($demande);
            }

            $demande->update($updateData);

            // Ici, vous pourriez ajouter une notification pour l'utilisateur
            // Exemple : Notification::send($demande->user, new DemandeTraitee($demande));

            return response()->json([
                'status' => 'success',
                'message' => 'Demande traitée avec succès',
                'data' => $demande->fresh(['user', 'foyer', 'habitant'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du traitement de la demande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Appliquer la modification demandée
     * 
     * @param  \App\Models\DemandeModification  $demande
     * @return void
     */
    protected function appliquerModification(DemandeModification $demande)
    {
        switch ($demande->type) {
            case 'ajout_habitant':
                // Logique pour ajouter un habitant
                // Exemple : 
                // $habitant = Habitant::create($demande->donnees);
                break;
                
            case 'suppression_habitant':
                // Logique pour supprimer un habitant
                if ($demande->habitant_id) {
                    // $demande->habitant->delete();
                }
                break;
                
            case 'demande_info':
                // Rien à faire ici, c'est juste une demande d'information
                break;
        }
    }
}
