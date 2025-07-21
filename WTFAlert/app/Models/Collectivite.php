<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collectivite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'addressDeLaCollectivite',
        'postal_codeDeLaCollectivite',
        'cityDeLaCollectivite',
        'phoneDeLaCollectivite',
        'emailsDeLaCollectivite',
        'phone',
        'email',
        'website',
    ];

    public function getFullAddressAttribute()
    {
        return "{$this->addressDeLaCollectivite}, {$this->postal_codeDeLaCollectivite} {$this->cityDeLaCollectivite}";
    }

    public function getContactInfoAttribute()
    {
        return [
            'phone' => $this->phoneDeLaCollectivite,
            'email' => $this->emailsDeLaCollectivite,
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
    public function collectivites()
    {
        return $this->users()->wherePivot('user_type', 'collectivite');
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
