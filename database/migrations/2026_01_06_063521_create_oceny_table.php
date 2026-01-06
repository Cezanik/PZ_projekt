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
        Schema::create('oceny', function (Blueprint $table) {
        $table->id();
        $table->foreignId('uczen_id')->constrained('users');
        $table->foreignId('nauczyciel_id')->constrained('users');
        $table->foreignId('przedmiot_id')->constrained('przedmioty');
        
        $table->decimal('wartosc', 3, 2);
        $table->string('opis');
        $table->date('data_wystawienia');
        
        
        $table->timestamps(); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oceny');
    }
};
