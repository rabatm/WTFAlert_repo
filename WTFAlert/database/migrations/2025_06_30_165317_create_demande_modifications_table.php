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
        Schema::create('demande_modifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('foyer_id')->constrained()->onDelete('cascade');
            $table->foreignId('habitant_id')->nullable()->constrained('habitants')->onDelete('set null');
            $table->enum('type', ['ajout_habitant', 'suppression_habitant', 'demande_info']);
            $table->text('message');
            $table->enum('statut', ['en_attente', 'approuvee', 'rejetee'])->default('en_attente');
            $table->json('donnees')->nullable(); // Pour stocker des données supplémentaires selon le type de demande
            $table->text('reponse_admin')->nullable();
            $table->timestamp('traitee_le')->nullable();
            $table->timestamps();
            
            // Index pour les recherches courantes
            $table->index(['user_id', 'foyer_id', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_modifications');
    }
};
