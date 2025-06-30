<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;
use App\Models\User;

class Habitant extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'inscriptions',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'telephone',
        'email',
        'adresse',
        'code_postal',
        'ville',
        'pays',
        'numero_secu_sociale',
        'situation_familiale',
        'profession',
        'employeur',
        'revenus',
        'regime_social',
        'caisse_retraite',
        'mutuelle',
        'medecin_traitant',
        'groupe_sanguin',
        'allergies',
        'traitements',
        'handicap',
        'moyen_deplacement',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'contact_urgence_lien',
        'remarques',
    ];

    protected $casts = [
        'inscriptions' => 'array',
        'date_naissance' => 'date',
        'revenus' => 'decimal:2',
        'allergies' => 'array',
        'traitements' => 'array',
        'handicap' => 'boolean',
    ];

    /**
     * Configuration du suivi d'activité
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nom',
                'prenom',
                'date_naissance',
                'lieu_naissance',
                'telephone',
                'email',
                'adresse',
                'code_postal',
                'ville',
                'pays',
                'numero_secu_sociale',
                'situation_familiale',
                'profession',
                'employeur',
                'revenus',
                'regime_social',
                'caisse_retraite',
                'mutuelle',
                'medecin_traitant',
                'groupe_sanguin',
                'allergies',
                'traitements',
                'handicap',
                'moyen_deplacement',
                'contact_urgence_nom',
                'contact_urgence_telephone',
                'contact_urgence_lien',
                'remarques',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->logExcept(['updated_at'])
            ->setDescriptionForEvent(function(string $eventName) {
                return "L'habitant a été {$eventName}";
            });
    }

    /**
     * Définit l'utilisateur responsable de la modification
     */
    public function tapActivity(ActivityContract $activity, string $eventName)
    {
        $activity->causer_id = auth()->id() ?? $this->user_id;
        $activity->causer_type = User::class;
        
        // Vérifier si properties est un tableau ou un objet
        $properties = is_array($activity->properties) 
            ? $activity->properties 
            : $activity->properties->toArray();
            
        $activity->properties = array_merge($properties, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foyers()
    {
        return $this->belongsToMany(Foyer::class, 'habitants_foyer')
                    ->withPivot('type_habitant')
                    ->withTimestamps();
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }

    // Méthodes helper pour récupérer les foyers par type
    public function foyersResponsable()
    {
        return $this->foyers()->wherePivot('type_habitant', 'responsable');
    }

    public function foyersSimple()
    {
        return $this->foyers()->wherePivot('type_habitant', 'habitant');
    }
}
