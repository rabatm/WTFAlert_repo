<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Habitant;
use Illuminate\Auth\Access\HandlesAuthorization;

class HabitantPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir l'historique de l'habitant.
     */
    public function view(User $user, Habitant $habitant): bool
    {
        // L'utilisateur peut voir l'historique s'il est admin
        // ou s'il est associé au foyer de l'habitant
        return $user->isAdmin() || 
               $habitant->foyers()
                   ->whereHas('users', function($query) use ($user) {
                       $query->where('users.id', $user->id);
                   })
                   ->exists();
    }
}
