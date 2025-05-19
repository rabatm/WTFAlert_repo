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
        'description',
        'localisation',
        'latitude',
        'longitude',
        'anonyme',
        'statut',
    ];

    public function habitant()
    {
        return $this->belongsTo(Habitant::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
