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
        Schema::create('uczniowie_klasy', function (Blueprint $table) {
        $table->id();

        $table->foreignId('klasa_id')->constrained('klasy')->onDelete('cascade');
        $table->foreignId('uczen_id')->constrained('users')->onDelete('cascade');
        
        
        $table->unique(['klasa_id', 'uczen_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uczniowie_klasy');
    }
};
