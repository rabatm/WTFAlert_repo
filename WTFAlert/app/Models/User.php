<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone_mobile',
        'is_admin',
        'email_verified_at',
        'remember_token',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];



    public function habitant()
    {
        return $this->hasOne(Habitant::class);
    }

    public function mairie()
    {
        return $this->belongsToMany(Mairie::class)
                    ->withPivot('user_type', 'contact_type')
                    ->withTimestamps();
    }
    
    /**
     * Vérifie si l'utilisateur est un administrateur
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
}
