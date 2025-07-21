<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1: Ajouter la colonne mairie_id à la table secteurs (si elle n'existe pas déjà)
        if (!Schema::hasColumn('secteurs', 'mairie_id')) {
            Schema::table('secteurs', function (Blueprint $table) {
                $table->foreignId('mairie_id')->nullable()->constrained()->onDelete('cascade');
            });
        }

        // Étape 2: Migrer les données de la table pivot vers la nouvelle colonne
        // On prend seulement la première relation si un secteur était associé à plusieurs mairies
        if (Schema::hasTable('secteurs_mairie')) {
            DB::statement('
                UPDATE secteurs
                SET mairie_id = (
                    SELECT mairie_id
                    FROM secteurs_mairie
                    WHERE secteurs_mairie.secteur_id = secteurs.id
                    LIMIT 1
                )
            ');
        }

        // Étape 2.5: Vérifier s'il y a des secteurs sans mairie et les gérer
        $secteursWithoutMairie = DB::table('secteurs')->whereNull('mairie_id')->count();

        if ($secteursWithoutMairie > 0) {
            // Option 1: Supprimer les secteurs sans mairie
            DB::table('secteurs')->whereNull('mairie_id')->delete();

            // Option 2: Ou créer une mairie par défaut (commenté)
            // $defaultMairie = DB::table('mairies')->first();
            // if ($defaultMairie) {
            //     DB::table('secteurs')->whereNull('mairie_id')->update(['mairie_id' => $defaultMairie->id]);
            // }
        }

        // Étape 3: Rendre la colonne mairie_id obligatoire (seulement si elle existe)
        if (Schema::hasColumn('secteurs', 'mairie_id')) {
            Schema::table('secteurs', function (Blueprint $table) {
                $table->foreignId('mairie_id')->nullable(false)->change();
            });
        }

        // Étape 4: Supprimer la table pivot secteurs_mairie
        Schema::dropIfExists('secteurs_mairie');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la table pivot secteurs_mairie
        Schema::create('secteurs_mairie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('mairie_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['secteur_id', 'mairie_id']);
        });

        // Migrer les données vers la table pivot
        DB::statement('
            INSERT INTO secteurs_mairie (secteur_id, mairie_id, created_at, updated_at)
            SELECT id, mairie_id, NOW(), NOW()
            FROM secteurs
            WHERE mairie_id IS NOT NULL
        ');

        // Supprimer la colonne mairie_id de la table secteurs
        Schema::table('secteurs', function (Blueprint $table) {
            $table->dropForeign(['mairie_id']);
            $table->dropColumn('mairie_id');
        });
    }
};
