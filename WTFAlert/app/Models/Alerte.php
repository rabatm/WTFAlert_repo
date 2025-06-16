<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    use HasFactory;

    protected $fillable = [
        'habitant_id',
        'type',
        'titre',
        'titre_final',
        'description',
        'description_finale',
        'localisation',
        'latitude',
        'longitude',
        'surplace',
        'anonyme',
        'statut',
        'admin_id',
        'date_validation',
        'commentaire_admin',
        'visible_mobile',
        'envoyer_mail',
    ];

    protected $casts = [
        'date_validation' => 'datetime',
        'surplace' => 'boolean',
        'anonyme' => 'boolean',
        'visible_mobile' => 'boolean',
        'envoyer_mail' => 'boolean',
    ];

    // Statuts possibles
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_VALIDE = 'valide';
    const STATUT_REJETE = 'rejete';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_ARCHIVE = 'archive';

    public function habitant()
    {
        return $this->belongsTo(Habitant::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    // Méthodes utilitaires
    public function isEnAttente()
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    public function isValide()
    {
        return $this->statut === self::STATUT_VALIDE;
    }

    public function isRejete()
    {
        return $this->statut === self::STATUT_REJETE;
    }

    public function isArchive()
    {
        return $this->statut === self::STATUT_ARCHIVE;
    }

    // Récupère le titre à afficher (final si validé, original sinon)
    public function getTitreAffichage()
    {
        return $this->titre_final ?: $this->titre;
    }

    // Récupère la description à afficher (finale si validée, originale sinon)
    public function getDescriptionAffichage()
    {
        return $this->description_finale ?: $this->description;
    }

    // Scope pour les alertes visibles sur mobile
    public function scopeVisibleMobile($query)
    {
        return $query->where('visible_mobile', true);
    }

    // Scope pour les alertes à envoyer par mail
    public function scopeAEnvoyerParMail($query)
    {
        return $query->where('envoyer_mail', true);
    }

    // Scope pour exclure les alertes archivées
    public function scopeNonArchivees($query)
    {
        return $query->where('statut', '!=', self::STATUT_ARCHIVE);
    }
}
