<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMairiesTable extends Migration
{
    public function up()
    {
        Schema::create('mairies', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('addressDeLaMairie');
            $table->string('postal_codeDeLaMairie');
            $table->string('cityDeLaMairie');
            $table->string('phoneDeLaMairie')->nullable();
            $table->string('emailsDeLaMairie')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mairies');
    }
}
