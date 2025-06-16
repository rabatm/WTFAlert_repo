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
        Schema::create('habitant_foyer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habitant_id')->constrained()->onDelete('cascade');
            $table->foreignId('foyer_id')->constrained()->onDelete('cascade');
            $table->enum('type_habitant', ['habitant', 'responsable'])->default('habitant');
            $table->timestamps();

            // Un habitant ne peut avoir qu'un seul type par foyer
            $table->unique(['habitant_id', 'foyer_id']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitant_foyer');
    }
};
