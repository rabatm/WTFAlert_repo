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
        Schema::create('secteurs_mairie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('mairie_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Pour éviter les doublons
            $table->unique(['secteur_id', 'mairie_id']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('secteurs_mairie');
    }
};
