<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Schema::create('mairie_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mairie_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Type d'utilisateur dans cette mairie
            $table->enum('user_type', ['maire', 'secretaire', 'adjoint', 'administrateur', 'membre'])
                  ->default('membre');

            // Type de contact
            $table->enum('contact_type', ['info', 'alerte', 'warning', 'accident', 'urgence', 'toutes'])
                  ->nullable();

            $table->timestamps();

            // Un utilisateur ne peut avoir qu'une entrée par mairie
            $table->unique(['mairie_id', 'user_id']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('mairie_user');
    }
};
