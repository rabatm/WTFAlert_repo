<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Secteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'secteur',
        'description',
    ];

    /**
     * Les mairies associées à ce secteur
     */
    public function mairie(): BelongsToMany
    {
        return $this->belongsToMany(Mairies::class, 'secteur_mairie');
    }

    /**
     * Les foyers associés à ce secteur
     */
    public function foyers(): BelongsToMany
    {
        return $this->belongsToMany(Foyer::class, 'foyer_secteur');
    }
}
