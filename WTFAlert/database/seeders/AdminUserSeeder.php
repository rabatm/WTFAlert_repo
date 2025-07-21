<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Exécuter le seeder.
     */
    public function run(): void
    {
        // Créer l'utilisateur admin
        $admin = User::create([
            'prenom' => 'Admin',
            'nom' => 'Système',
            'email' => 'admin@wtfalert.fr',
            'password' => Hash::make('password'),
            'telephone_mobile' => '0600000000',
            'is_admin' => true,
        ]);

        // Vérifier si le rôle super_admin existe, sinon le créer
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        
        // Attribuer le rôle à l'utilisateur
        $admin->assignRole($superAdminRole);

        $this->command->info('Utilisateur administrateur créé avec succès !');
        $this->command->info('Email: admin@wtfalert.fr');
        $this->command->info('Mot de passe: password');
    }
}
