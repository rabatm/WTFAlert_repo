<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mairie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'addressDeLaMairie',
        'postal_codeDeLaMairie',
        'cityDeLaMairie',
        'phoneDeLaMairie',
        'emailsDeLaMairie',
        'phone',
        'email',
        'website',
    ];

    public function getFullAddressAttribute()
    {
        return "{$this->addressDeLaMairie}, {$this->postal_codeDeLaMairie} {$this->cityDeLaMairie}";
    }

    public function getContactInfoAttribute()
    {
        return [
            'phone' => $this->phoneDeLaMairie,
            'email' => $this->emailsDeLaMairie,
            'website' => $this->website,
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('user_type', 'contact_type')
                    ->withTimestamps();
    }

    public function foyers()
    {
        return $this->hasMany(Foyer::class);
    }

    // Méthodes helper pour récupérer les utilisateurs par type
    public function maires()
    {
        return $this->users()->wherePivot('user_type', 'maire');
    }

    public function administrateurs()
    {
        return $this->users()->wherePivot('user_type', 'administrateur');
    }

    public function secteurs(): HasMany
    {
        return $this->hasMany(Secteur::class);
    }
}
