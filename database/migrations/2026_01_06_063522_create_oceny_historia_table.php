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
        Schema::create('oceny_historia', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ocena_id')->constrained('oceny')->onDelete('cascade');
        
        $table->decimal('poprzednia_wartosc', 3, 2);
        $table->decimal('nowa_wartosc', 3, 2);
        $table->dateTime('data_zmiany');
        $table->string('powod');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oceny_historia');
    }
};
