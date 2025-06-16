<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Mairie;
use App\Models\Secteur;
use App\Models\Foyer;
use App\Models\Habitant;
use App\Models\Alerte;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sectors
        $secteurNames = ['Nord', 'Sud', 'Est', 'Ouest', 'Centre'];
        $secteurSecteur = ['A', 'B', 'C', 'D', 'E'];
        $secteurs = [];

        foreach ($secteurNames as $nom) {
            $secteurs[] = Secteur::create([
                'nom' => $nom,
                'secteur' => $nom,
                'description' => 'Secteur ' . $nom . ' de la commune'
            ]);
        }

        // Create mairies
        $mairie = Mairie::create([
            'nom' => 'Mairie de Villeneuve',
            'adresse' => '1 Place de la Mairie',
            'code_postal' => '34000',
            'ville' => 'Villeneuve',
            'telephone' => '0467000000',
            'email' => 'contact@mairie-villeneuve.fr',
            'telephone' => '0467000001',
        ]);

        // Associate mairie with sectors
        $mairie->secteurs()->attach($secteurs[0]->id);
        $mairie->secteurs()->attach($secteurs[4]->id);

        // Create admin users
        $adminPassword = 'Admin123!';
        $adminUsers = [];

        $adminUsers[] = User::create([
            'nom' => 'Celavie',
            'prenom' => 'Martin',
            'email' => 'martin@wtfalert.com',
            'password' => Hash::make($adminPassword),
            'telephone_mobile' => '0601020304',
        ]);

        $adminUsers[] = User::create([
            'nom' => 'Dupont',
            'prenom' => 'Nicolas',
            'email' => 'nicolas@wtfalert.com',
            'password' => Hash::make($adminPassword),
            'telephone_mobile' => '0607080910',
        ]);

        // Associate users with mairie
        foreach ($adminUsers as $user) {
            $user->mairie()->attach($mairie->id, [
                'user_type' => 'administrateur',
                'contact_type' => 'principal',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create foyers
        $foyers = [];

        for ($i = 1; $i <= 10; $i++) {
            $foyer = Foyer::create([
                'mairie_id' => $mairie->id,
                'nom' => 'Foyer ' . fake()->lastName(),
                'numero_voie' => fake()->numberBetween(1, 100),
                'adresse' => fake()->streetName(),
                'complement_dadresse' => fake()->boolean(30) ? 'Appartement ' . fake()->numberBetween(1, 50) : null,
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
            ]);

            $foyers[] = $foyer;

            // Associate foyer with sectors (1-2 sectors per foyer)
            $numSectors = fake()->numberBetween(1, 2);
            $sectorIds = fake()->randomElements(array_column($secteurs, 'id'), $numSectors);
            $foyer->secteurs()->attach($sectorIds);
        }

        // Create habitants for admin users
        $adminHabitants = [];

        foreach ($adminUsers as $index => $user) {
            $habitant = Habitant::create([
                'user_id' => $user->id,
                'inscriptions' => ['admin'],
            ]);

            $adminHabitants[] = $habitant;

            // Associate habitant with foyer (admin users are responsables)
            $habitant->foyers()->attach($foyers[$index]->id, [
                'type_habitant' => 'responsable',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create regular users and habitants
        $roles = ['moderateur', 'alerte', 'standard'];

        for ($i = 2; $i < count($foyers); $i++) {
            // Generate 1-4 habitants per foyer
            $nbHabitants = fake()->numberBetween(1, 4);

            for ($j = 0; $j < $nbHabitants; $j++) {
                // Create user
                $user = User::create([
                    'nom' => fake()->lastName(),
                    'prenom' => fake()->firstName(),
                    'email' => fake()->unique()->safeEmail(),
                    'password' => Hash::make('password123'),
                    'telephone_mobile' => fake()->boolean(90) ? fake()->phoneNumber() : null,
                ]);

                // Random roles (1-2)
                $nbRoles = fake()->numberBetween(1, 2);
                $inscriptions = fake()->randomElements($roles, $nbRoles);

                // Create habitant
                $habitant = Habitant::create([
                    'user_id' => $user->id,
                    'inscriptions' => $inscriptions,
                ]);

                // Associate habitant with foyer
                // First habitant is responsable, others are regular habitants
                $typeHabitant = ($j === 0) ? 'responsable' : 'habitant';
                $habitant->foyers()->attach($foyers[$i]->id, [
                    'type_habitant' => $typeHabitant,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Create some alerts for habitants with 'alerte' role
                if (in_array('alerte', $inscriptions) && fake()->boolean(30)) {
                    $alerteTypes = ['danger', 'information', 'travaux', 'événement'];
                    $alerteStatuts = [
                        Alerte::STATUT_EN_ATTENTE,
                        Alerte::STATUT_VALIDE,
                        Alerte::STATUT_EN_COURS,
                        Alerte::STATUT_ARCHIVE
                    ];

                    $alerte = Alerte::create([
                        'habitant_id' => $habitant->id,
                        'type' => fake()->randomElement($alerteTypes),
                        'titre' => fake()->sentence(4),
                        'titre_final' => fake()->boolean(50) ? fake()->sentence(4) : null,
                        'description' => fake()->paragraph(),
                        'description_finale' => fake()->boolean(50) ? fake()->paragraph() : null,
                        'localisation' => $foyers[$i]->adresse . ', ' . $foyers[$i]->code_postal . ' ' . $foyers[$i]->ville,
                        'latitude' => $foyers[$i]->lattitude,
                        'longitude' => $foyers[$i]->longitude,
                        'surplace' => fake()->boolean(),
                        'anonyme' => fake()->boolean(20),
                        'statut' => fake()->randomElement($alerteStatuts),
                        'admin_id' => fake()->boolean(70) ? $adminUsers[array_rand($adminUsers)]->id : null,
                        'date_validation' => fake()->boolean(70) ? Carbon::now()->subDays(fake()->numberBetween(1, 30)) : null,
                        'commentaire_admin' => fake()->boolean(50) ? fake()->sentence(10) : null,
                        'visible_mobile' => fake()->boolean(80),
                        'envoyer_mail' => fake()->boolean(60),
                    ]);
                }
            }
        }

        // Display admin information in console
        $this->command->info('Admin users created:');
        $this->command->info('1. Martin Celavie');
        $this->command->info('   Login: martin@wtfalert.com');
        $this->command->info('   Password: ' . $adminPassword);
        $this->command->info('2. Nicolas Dupont');
        $this->command->info('   Login: nicolas@wtfalert.com');
        $this->command->info('   Password: ' . $adminPassword);
    }
}
