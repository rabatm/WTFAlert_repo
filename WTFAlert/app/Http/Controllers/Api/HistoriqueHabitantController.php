<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habitant;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;

class HistoriqueHabitantController extends Controller
{
    /**
     * Affiche l'historique des modifications d'un habitant
     *
     * @param  int  $habitantId
     * @return \Illuminate\Http\Response
     */
    public function index($habitantId)
    {
        $habitant = Habitant::findOrFail($habitantId);
        
        // Vérifier que l'utilisateur a le droit de voir cet historique
        $this->authorize('view', $habitant);
        
        $activites = Activity::where('subject_type', Habitant::class)
            ->where('subject_id', $habitantId)
            ->with('causer')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($activite) {
                return [
                    'id' => $activite->id,
                    'description' => $activite->description,
                    'causer' => $activite->causer ? [
                        'id' => $activite->causer->id,
                        'name' => $activite->causer->name,
                        'email' => $activite->causer->email,
                    ] : null,
                    'properties' => $activite->properties->toArray(),
                    'created_at' => $activite->created_at->format('d/m/Y H:i:s'),
                    'updated_at' => $activite->updated_at->format('d/m/Y H:i:s'),
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'habitant' => [
                    'id' => $habitant->id,
                    'nom_complet' => $habitant->prenom . ' ' . $habitant->nom,
                ],
                'activites' => $activites
            ]
        ]);
    }

    /**
     * Affiche les détails d'une modification spécifique
     *
     * @param  int  $habitantId
     * @param  int  $activiteId
     * @return \Illuminate\Http\Response
     */
    public function show($habitantId, $activiteId)
    {
        $habitant = Habitant::findOrFail($habitantId);
        $this->authorize('view', $habitant);

        $activite = Activity::where('id', $activiteId)
            ->where('subject_type', Habitant::class)
            ->where('subject_id', $habitantId)
            ->with('causer')
            ->firstOrFail();

        $changements = [];
        
        if ($activite->event === 'updated') {
            $anciennesValeurs = $activite->properties['old'] ?? [];
            $nouvellesValeurs = $activite->properties['attributes'] ?? [];
            
            foreach ($nouvellesValeurs as $champ => $nouvelleValeur) {
                // Ne pas afficher les champs de suivi
                if (in_array($champ, ['updated_at', 'created_at'])) {
                    continue;
                }
                
                $ancienneValeur = $anciennesValeurs[$champ] ?? null;
                
                if ($ancienneValeur != $nouvelleValeur) {
                    $changements[] = [
                        'champ' => $this->getLibelleChamp($champ),
                        'ancienne_valeur' => $this->formaterValeur($champ, $ancienneValeur),
                        'nouvelle_valeur' => $this->formaterValeur($champ, $nouvelleValeur),
                    ];
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'activite' => [
                    'id' => $activite->id,
                    'description' => $activite->description,
                    'causer' => $activite->causer ? [
                        'id' => $activite->causer->id,
                        'name' => $activite->causer->name,
                        'email' => $activite->causer->email,
                    ] : null,
                    'ip_address' => $activite->properties['ip_address'] ?? null,
                    'user_agent' => $activite->properties['user_agent'] ?? null,
                    'created_at' => $activite->created_at->format('d/m/Y H:i:s'),
                    'changements' => $changements,
                ]
            ]
        ]);
    }

    /**
     * Retourne le libellé lisible d'un champ
     */
    private function getLibelleChamp($champ)
    {
        $libelles = [
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'date_naissance' => 'Date de naissance',
            'lieu_naissance' => 'Lieu de naissance',
            'telephone' => 'Téléphone',
            'email' => 'Email',
            'adresse' => 'Adresse',
            'code_postal' => 'Code postal',
            'ville' => 'Ville',
            'pays' => 'Pays',
            'numero_secu_sociale' => 'Numéro de sécurité sociale',
            'situation_familiale' => 'Situation familiale',
            'profession' => 'Profession',
            'employeur' => 'Employeur',
            'revenus' => 'Revenus',
            'regime_social' => 'Régime social',
            'caisse_retraite' => 'Caisse de retraite',
            'mutuelle' => 'Mutuelle',
            'medecin_traitant' => 'Médecin traitant',
            'groupe_sanguin' => 'Groupe sanguin',
            'allergies' => 'Allergies',
            'traitements' => 'Traitements',
            'handicap' => 'Handicap',
            'moyen_deplacement' => 'Moyen de déplacement',
            'contact_urgence_nom' => 'Contact d\'urgence - Nom',
            'contact_urgence_telephone' => 'Contact d\'urgence - Téléphone',
            'contact_urgence_lien' => 'Contact d\'urgence - Lien',
            'remarques' => 'Remarques',
        ];

        return $libelles[$champ] ?? $champ;
    }

    /**
     * Formate une valeur selon son type
     */
    private function formaterValeur($champ, $valeur)
    {
        if ($valeur === null) {
            return 'Non renseigné';
        }

        if (is_array($valeur)) {
            return json_encode($valeur, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if ($champ === 'date_naissance' && $valeur) {
            return \Carbon\Carbon::parse($valeur)->format('d/m/Y');
        }

        if (is_bool($valeur)) {
            return $valeur ? 'Oui' : 'Non';
        }

        return $valeur;
    }
}
