<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter collectivite_id à la table foyers
        Schema::table('foyers', function (Blueprint $table) {
            $table->foreignId('collectivite_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Ajouter collectivite_id à la table secteurs
        Schema::table('secteurs', function (Blueprint $table) {
            $table->foreignId('collectivite_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les colonnes collectivite_id
        Schema::table('secteurs', function (Blueprint $table) {
            $table->dropForeign(['collectivite_id']);
            $table->dropColumn('collectivite_id');
        });

        Schema::table('foyers', function (Blueprint $table) {
            $table->dropForeign(['collectivite_id']);
            $table->dropColumn('collectivite_id');
        });
    }
};
