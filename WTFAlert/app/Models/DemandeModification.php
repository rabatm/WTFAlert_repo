<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Foyer;
use App\Models\Habitant;

class DemandeModification extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'foyer_id',
        'habitant_id',
        'type',
        'message',
        'statut',
        'donnees',
        'reponse_admin',
        'traitee_le',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'donnees' => 'array',
        'traitee_le' => 'datetime',
    ];

    /**
     * Les types de demande disponibles.
     */
    public const TYPES = [
        'ajout_habitant' => 'Ajout d\'un habitant',
        'suppression_habitant' => 'Suppression d\'un habitant',
        'demande_info' => 'Demande d\'information',
    ];

    /**
     * Les statuts disponibles.
     */
    public const STATUTS = [
        'en_attente' => 'En attente',
        'approuvee' => 'Approuvée',
        'rejetee' => 'Rejetée',
    ];

    /**
     * Relation avec l'utilisateur qui a créé la demande.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le foyer concerné.
     */
    public function foyer()
    {
        return $this->belongsTo(Foyer::class);
    }

    /**
     * Relation avec l'habitant concerné (si applicable).
     */
    public function habitant()
    {
        return $this->belongsTo(Habitant::class);
    }
}
