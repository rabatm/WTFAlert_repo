<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 

class Habitant extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; 

    protected $fillable = [
        'foyer_id',
        'nom_hb',
        'prenom_hb',
        'telephone_mobile',
        'mail',
        'inscriptions',
        'motdepasse',
    ];

    protected $hidden = [
        'motdepasse',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'inscriptions' => 'array',
    ];

    public function getAuthPassword()
    {
        return $this->motdepasse;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function foyer()
    {
        return $this->belongsTo(Foyer::class);
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }
}
