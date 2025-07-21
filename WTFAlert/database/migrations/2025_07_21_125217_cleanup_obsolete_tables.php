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
        // Supprimer les tables obsolètes qui pourraient exister
        Schema::dropIfExists('collectivite_user');
        Schema::dropIfExists('collectivites');
        Schema::dropIfExists('secteurs_collectivite');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ces tables ne doivent pas être recréées
        // car elles sont remplacées par la nouvelle structure
    }
};
