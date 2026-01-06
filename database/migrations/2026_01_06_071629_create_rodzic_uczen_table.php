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
        Schema::create('rodzic_uczen', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rodzic_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('uczen_id')->constrained('users')->onDelete('cascade');
        $table->unique(['rodzic_id', 'uczen_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rodzic_uczen');
    }
};
