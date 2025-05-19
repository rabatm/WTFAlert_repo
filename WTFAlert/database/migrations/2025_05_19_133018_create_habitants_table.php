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
        Schema::create('habitants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('foyer_id')->constrained()->onDelete('cascade');
            $table->string('nom_hb');
            $table->string('prenom_hb');
            $table->string('telephone_mobile')->nullable();
            $table->string('mail')->unique();
            $table->json('inscriptions')->nullable();
            $table->string('motdepasse');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitants');
    }
};
