<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('foyers', function (Blueprint $table) {
            $table->foreignId('mairie_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('foyers', function (Blueprint $table) {
            $table->dropForeign(['mairie_id']);
            $table->dropColumn('mairie_id');
        });
    }
};
