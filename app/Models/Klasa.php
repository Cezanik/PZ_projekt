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
        'wychowawca_id'
    ];

    // Relacja do wychowawcy
    public function wychowawca()
    {
        return $this->belongsTo(User::class, 'wychowawca_id');
    }

   public function uczniowie()
    {
        // Uwaga: tabela pośrednia 'uczniowie_klasy' też pewnie nie ma timestamps, 
        // więc w relacji many-to-many warto to zaznaczyć, jeśli będziesz używać pivotów
        return $this->belongsToMany(User::class, 'uczniowie_klasy', 'klasa_id', 'uczen_id');
    }

}
