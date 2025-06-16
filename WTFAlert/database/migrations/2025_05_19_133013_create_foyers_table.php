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
        Schema::create('foyers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('numero_voie')->nullable();
            $table->string('adresse');
            $table->string('complement_dadresse')->nullable();
            $table->integer('code_postal');
            $table->string('ville');
            $table->string('telephone_fixe')->nullable();
            $table->text('info')->nullable();
            $table->string('animaux')->nullable();
            $table->string('lattitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('geoloc_sdis')->nullable();
            $table->string('internet')->nullable();
            $table->boolean('non_connecte')->default(false);
            $table->boolean('vulnerable')->default(false);
            $table->text('indication')->nullable();
            $table->string('periode_naissance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foyers');
    }
};
