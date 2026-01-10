<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasa extends Model
{
    use HasFactory;
    protected $table = 'klasy';

    public $timestamps = false;

    protected $fillable = [
        'nazwa',      
    ];



   public function uczniowie()
    {
        return $this->belongsToMany(User::class, 'uczniowie_klasy', 'klasa_id', 'uczen_id');
    }

}
