<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcenaHistoria extends Model
{
    protected $table = 'oceny_historia';
    use HasFactory;

    protected $fillable = [
        'ocena_id',
        'zmienil_user_id', 
        'stara_wartosc',
        'stara_opis',
        'data_zmiany',
        'powod_zmiany'
    ];

    public function ocena()
    {
        return $this->belongsTo(Ocena::class, 'ocena_id');
    }
    public function zmienilUser()
    {
        // 'zmienil_id' to nazwa klucza obcego w tabeli oceny_historia
        return $this->belongsTo(User::class, 'zmienil_id');
    }
}
