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
        Schema::create('nauczyciele_przedmioty_klasy', function (Blueprint $table) {
        $table->id();
        $table->foreignId('klasa_id')->constrained('klasy');
        $table->foreignId('nauczyciel_id')->constrained('users');
        $table->foreignId('przedmiot_id')->constrained('przedmioty');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nauczyciele_przedmioty_klasy');
    }
};
