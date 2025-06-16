<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Foyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'mairie_id',
        'nom',
        'numero_voie',
        'adresse',
        'complement_dadresse',
        'code_postal',
        'ville',
        'telephone_fixe',
        'info',
        'animaux',
        'latitude',
        'longitude',
        'geoloc_sdis',
        'internet',
        'non_connecte',
        'vulnerable',
        'indication',
        'periode_naissance',
    ];

    protected $casts = [
        'non_connecte' => 'boolean',
        'vulnerable' => 'boolean',
    ];

    public function mairie()
    {
        return $this->belongsTo(Mairie::class);
    }

    public function habitants()
    {
        return $this->belongsToMany(Habitant::class, 'habitants_foyer')
                    ->withPivot('type_habitant')
                    ->withTimestamps();
    }

    // Méthode helper pour récupérer le responsable du foyer
    public function responsable()
    {
        return $this->habitants()->wherePivot('type_habitant', 'responsable')->first();
    }

    // Méthode helper pour récupérer tous les habitants simples
    public function habitantsSimples()
    {
        return $this->habitants()->wherePivot('type_habitant', 'habitant');
    }

    public function secteurs(): BelongsToMany
    {
        return $this->belongsToMany(Secteur::class, 'foyers_secteurs');
    }
}
