<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'numero_voie',
        'adresse',
        'complement_dadresse',
        'secteur',
        'code_postal',
        'ville',
        'telephone_fixe',
        'info',
        'animaux',
        'lattitude',
        'longitude',
        'geoloc_sdis',
        'internet',
        'non_connecte',
        'vulnerable',
        'indication',
        'periode_naissance',
    ];

    protected $casts = [
        'secteur' => 'array',
        'non_connecte' => 'boolean',
        'vulnerable' => 'boolean',
    ];

    public function habitants()
    {
        return $this->hasMany(Habitant::class);
    }
}
