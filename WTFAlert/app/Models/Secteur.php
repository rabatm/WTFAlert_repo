<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Secteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'secteur',
        'description',
        'mairie_id',
    ];

    /**
     * La mairie associée à ce secteur
     */
    public function mairie(): BelongsTo
    {
        return $this->belongsTo(Mairie::class);
    }

    /**
     * Les foyers associés à ce secteur
     */
    public function foyers(): BelongsToMany
    {
        return $this->belongsToMany(Foyer::class, 'foyer_secteur');
    }
}
