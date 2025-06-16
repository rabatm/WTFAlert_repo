<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone_mobile',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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
}
