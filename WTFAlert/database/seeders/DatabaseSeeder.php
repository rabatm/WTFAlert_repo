<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer 10 foyers avec des données factices
        $foyers = [];
        $secteurs = ['Nord', 'Sud', 'Est', 'Ouest', 'Centre'];
        
        for ($i = 1; $i <= 10; $i++) {
            $foyers[] = [
                'id' => $i,
                'nom' => 'Foyer ' . fake()->lastName(),
                'numero_voie' => fake()->numberBetween(1, 100),
                'adresse' => fake()->streetName(),
                'complement_dadresse' => fake()->boolean(30) ? 'Appartement ' . fake()->numberBetween(1, 50) : null,
                'secteur' => json_encode([$secteurs[array_rand($secteurs)]]),
                'code_postal' => fake()->numberBetween(10000, 99999),
                'ville' => fake()->city(),
                'telephone_fixe' => fake()->boolean(60) ? fake()->phoneNumber() : null,
                'info' => fake()->boolean(40) ? fake()->text(100) : null,
                'animaux' => fake()->boolean(50) ? fake()->randomElement(['chien', 'chat', 'chien et chat', 'autres']) : null,
                'lattitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'geoloc_sdis' => fake()->boolean(20) ? fake()->regexify('[A-Z]{2}[0-9]{4}') : null,
                'internet' => fake()->boolean(80) ? fake()->randomElement(['fibre', 'adsl', 'satellite']) : null,
                'non_connecte' => fake()->boolean(10),
                'vulnerable' => fake()->boolean(20),
                'indication' => fake()->boolean(30) ? fake()->text(150) : null,
                'periode_naissance' => fake()->randomElement(['1940-1959', '1960-1979', '1980-1999', '2000-2020']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        
        DB::table('foyers')->insert($foyers);
        
        // Créer les deux administrateurs spécifiques
        $adminPassword = 'Admin123!';
        $admins = [
            [
                'foyer_id' => 1,
                'nom_hb' => 'Celavie',
                'prenom_hb' => 'Martin',
                'telephone_mobile' => '0601020304',
                'mail' => 'martin@wtfalert.com',
                'inscriptions' => json_encode(['admin']),
                'motdepasse' => Hash::make($adminPassword),
                'email_verified_at' => Carbon::now(),
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'foyer_id' => 2,
                'nom_hb' => 'Dupont',
                'prenom_hb' => 'Nicolas',
                'telephone_mobile' => '0607080910',
                'mail' => 'nicolas@wtfalert.com',
                'inscriptions' => json_encode(['admin']),
                'motdepasse' => Hash::make($adminPassword),
                'email_verified_at' => Carbon::now(),
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];
        
        DB::table('habitants')->insert($admins);
        
        // Afficher les informations des administrateurs dans la console
        $this->command->info('Admin utilisateurs créés :');
        $this->command->info('1. Martin Celavie');
        $this->command->info('   Login: martin@wtfalert.com');
        $this->command->info('   Mot de passe: ' . $adminPassword);
        $this->command->info('2. Nicolas Dupont');
        $this->command->info('   Login: nicolas@wtfalert.com');
        $this->command->info('   Mot de passe: ' . $adminPassword);
        
        // Créer des habitants supplémentaires
        $roles = ['moderateur', 'alerte', 'standard'];
        $habitants = [];
        
        foreach ($foyers as $foyer) {
            // Sauter les foyers des admins
            if ($foyer['id'] <= 2) {
                continue;
            }
            
            // Générer entre 1 et 4 habitants par foyer
            $nbHabitants = fake()->numberBetween(1, 4);
            
            for ($j = 1; $j <= $nbHabitants; $j++) {
                // Attribution aléatoire de 1 à 2 rôles
                $inscriptions = [];
                $nbRoles = fake()->numberBetween(1, 2);
                $roleKeys = array_rand($roles, $nbRoles);
                
                if (is_array($roleKeys)) {
                    foreach ($roleKeys as $key) {
                        $inscriptions[] = $roles[$key];
                    }
                } else {
                    $inscriptions[] = $roles[$roleKeys];
                }
                
                $habitants[] = [
                    'foyer_id' => $foyer['id'],
                    'nom_hb' => fake()->lastName(),
                    'prenom_hb' => fake()->firstName(),
                    'telephone_mobile' => fake()->boolean(90) ? fake()->phoneNumber() : null,
                    'mail' => fake()->unique()->safeEmail(),
                    'inscriptions' => json_encode($inscriptions),
                    'motdepasse' => Hash::make('password123'),
                    'email_verified_at' => fake()->boolean(80) ? Carbon::now() : null,
                    'remember_token' => fake()->boolean(30) ? fake()->regexify('[A-Za-z0-9]{60}') : null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }
        
        if (!empty($habitants)) {
            DB::table('habitants')->insert($habitants);
        }
    }
}