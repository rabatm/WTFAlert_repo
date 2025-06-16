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
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habitant_id')->constrained();
            $table->enum('type', ['info', 'danger', 'alert', 'accident'])->default('info');
            $table->string('titre');
            $table->string('titre_final')->nullable();
            $table->text('description');
            $table->text('description_finale')->nullable();
            $table->string('localisation')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('surplace')->default(false);
            $table->boolean('anonyme')->default(false);
            $table->enum('statut', ['en_attente', 'valide', 'rejete', 'en_cours', 'archive'])->default('en_attente');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamp('date_validation')->nullable();
            $table->text('commentaire_admin')->nullable();
            $table->boolean('visible_mobile')->default(false);
            $table->boolean('envoyer_mail')->default(false);
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
