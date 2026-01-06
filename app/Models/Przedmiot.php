<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Przedmiot extends Model
{
    use HasFactory;

    protected $table = 'przedmioty';
    
    // WYMAGANE: Brak kolumn created_at/updated_at w bazie
    public $timestamps = false;

    // WYMAGANE: Pola dozwolone do zapisu
    protected $fillable = [
        'nazwa'
    ];

    public function oceny()
    {
        return $this->hasMany(Ocena::class, 'przedmiot_id');
    }
}
