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
            
            
            $table->decimal('stara_wartosc', 3, 2); 
            $table->string('stara_opis')->nullable(); 
            
            $table->dateTime('data_zmiany');
            $table->string('powod_zmiany');
            
            
            $table->foreignId('zmienil_user_id')->constrained('users');

            $table->timestamps(); 
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
