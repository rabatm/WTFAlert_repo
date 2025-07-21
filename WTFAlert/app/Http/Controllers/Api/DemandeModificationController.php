<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DemandeModification;
use App\Models\Collectivite;
use App\Mail\NouvelleDemandeModificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DemandeModificationController extends Controller
{
    /**
     * Envoyer une demande de modification d'information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:ajout_habitant,suppression_habitant,demande_info,modification_info',
            'message' => 'required|string|max:1000',
            'habitant_id' => 'required_if:type,ajout_habitant,suppression_habitant,modification_info|exists:habitants,id',
            'foyer_id' => 'required|exists:foyers,id',
            'donnees' => 'required|array', // Pour stocker des données supplémentaires selon le type de demande
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $demande = DemandeModification::create([
                'user_id' => auth()->id(),
                'foyer_id' => $request->foyer_id,
                'type' => $request->type,
                'message' => $request->message,
                'habitant_id' => $request->habitant_id,
                'statut' => 'en_attente',
                'donnees' => $request->donnees ?? [],
            ]);

            // Liste des administrateurs à notifier (en dur)
            $adminEmails = [
                "martin.rabat@gmail.com",
                "nicolas.cudel@gmail.com",
                "patrick.gericault@gmail.com"
            ];

            // Récupérer les administrateurs de la collectivité du foyer concerné
            $collectiviteAdmins = [];
            if ($demande->foyer && $demande->foyer->collectivite) {
                $collectiviteAdmins = $demande->foyer->collectivite->administrateurs()
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                Log::info('Administrateurs trouvés pour la collectivité:', [
                    'collectivite_id' => $demande->foyer->collectivite->id,
                    'emails' => $collectiviteAdmins
                ]);
            }

            // Fusionner les listes d'emails en évitant les doublons
            $allRecipients = array_unique(array_merge($adminEmails, $collectiviteAdmins));

            // Filtrer les emails vides
            $allRecipients = array_filter($allRecipients);

            if (!empty($allRecipients)) {
                try {
                    // Envoyer la notification par email
                    Mail::to($allRecipients[0])
                        ->cc(array_slice($allRecipients, 1))
                        ->send(new NouvelleDemandeModificationMail($demande, $allRecipients));

                    Log::info('Notification email envoyée pour la demande #' . $demande->id . ' à ' . implode(', ', $allRecipients));

                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi des emails de notification : ' . $e->getMessage());
                    // On continue même en cas d'erreur d'envoi d'email
                }
            } else {
                Log::warning('Aucun destinataire trouvé pour la demande #' . $demande->id);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Demande envoyée avec succès',
                'data' => $demande
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'envoi de la demande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les demandes de l'utilisateur connecté
     *
     * @return \Illuminate\Http\Response
     */
    public function mesDemandes()
    {
        $demandes = DemandeModification::where('user_id', auth()->id())
            ->with(['foyer', 'habitant'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $demandes
        ]);
    }
}
