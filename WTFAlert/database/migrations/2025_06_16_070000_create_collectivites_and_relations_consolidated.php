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
        // 1. Créer la table collectivites
        Schema::create('collectivites', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('addressDeLaCollectivite');
            $table->string('postal_codeDeLaCollectivite');
            $table->string('cityDeLaCollectivite');
            $table->string('phoneDeLaCollectivite')->nullable();
            $table->string('emailsDeLaCollectivite')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });

        // 2. Créer la table pivot collectivite_user
        Schema::create('collectivite_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collectivite_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('user_type', ['administrateur', 'gestionnaire', 'observateur'])
                  ->default('observateur');
            $table->enum('contact_type', ['urgence', 'info', 'toutes'])
                  ->default('toutes');
            $table->boolean('actif')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Un utilisateur ne peut avoir qu'une entrée par collectivité
            $table->unique(['collectivite_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer dans l'ordre inverse de création
        Schema::dropIfExists('collectivite_user');
        Schema::dropIfExists('collectivites');
    }
};
