<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser les rôles et permissions en cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            // Tableau de bord
            'view_dashboard',
            
            // Gestion des mairies
            'view_mairies',
            'create_mairies',
            'edit_mairies',
            'delete_mairies',
            
            // Gestion des utilisateurs
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Gestion des foyers
            'view_foyers',
            'create_foyers',
            'edit_foyers',
            'delete_foyers',
            
            // Gestion des habitants
            'view_habitants',
            'create_habitants',
            'edit_habitants',
            'delete_habitants',
            
            // Gestion des alertes
            'view_alertes',
            'create_alertes',
            'edit_alertes',
            'delete_alertes',
            
            // Gestion des demandes de modification
            'view_demandes_modification',
            'process_demandes_modification',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Créer les rôles et leur attribuer des permissions
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $adminMairieRole = Role::create(['name' => 'admin_mairie']);
        $adminMairieRole->givePermissionTo([
            'view_dashboard',
            'view_mairies',
            'view_users',
            'view_foyers',
            'view_habitants',
            'view_alertes',
            'view_demandes_modification',
            'process_demandes_modification',
        ]);

        $gestionnaireRole = Role::create(['name' => 'gestionnaire']);
        $gestionnaireRole->givePermissionTo([
            'view_dashboard',
            'view_foyers',
            'view_habitants',
            'view_alertes',
        ]);

        // Créer un utilisateur super admin par défaut
        $superAdmin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $superAdmin->assignRole('super_admin');
    }
}
