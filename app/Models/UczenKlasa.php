<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UczenKlasa extends Model
{
    use HasFactory;

    protected $table = 'uczniowie_klasy';
    
    // WYMAGANE: Wyłączamy obsługę czasu, bo migracja nie ma $table->timestamps()
    public $timestamps = false; 

    // WYMAGANE: Pozwalamy na wpisywanie tych pól (klucze obce)
    protected $fillable = [
        'klasa_id', 
        'uczen_id'
    ];
}
