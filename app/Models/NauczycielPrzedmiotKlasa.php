<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NauczycielPrzedmiotKlasa extends Model
{
    use HasFactory;

    protected $table = 'nauczyciele_przedmioty_klasy';
    
    public $timestamps = false;

    protected $fillable = [
        'klasa_id',
        'nauczyciel_id',
        'przedmiot_id'
    ];

    public function klasa()
    {
        return $this->belongsTo(Klasa::class, 'klasa_id');
    }

    public function nauczyciel()
    {
        return $this->belongsTo(User::class, 'nauczyciel_id');
    }

    public function przedmiot()
    {
        return $this->belongsTo(Przedmiot::class, 'przedmiot_id');
    }
}
