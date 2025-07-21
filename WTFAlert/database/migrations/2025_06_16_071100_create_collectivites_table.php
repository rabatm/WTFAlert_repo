<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectivitesTable extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('collectivites');
    }
}
