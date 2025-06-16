<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inscriptions',
    ];

    protected $casts = [
        'inscriptions' => 'array',
    ];

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
