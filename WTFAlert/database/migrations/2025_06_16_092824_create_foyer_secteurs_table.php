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
        Schema::create('foyer_secteur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('foyer_id')->constrained()->onDelete('cascade');
            $table->foreignId('secteur_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Pour éviter les doublons
            $table->unique(['foyer_id', 'secteur_id']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('foyer_secteur');
    }
};
