<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UczenKlasa extends Model
{
    use HasFactory;

    protected $table = 'uczniowie_klasy';
    
    public $timestamps = false; 

    protected $fillable = [
        'klasa_id', 
        'uczen_id'
    ];
}
