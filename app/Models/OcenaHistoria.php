<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcenaHistoria extends Model
{
    protected $table = 'oceny_historia';
    use HasFactory;

    protected $fillable = [
    'ocena_id', 'stara_wartosc', 'stara_opis', 'data_zmiany', 'powod_zmiany', 'zmienil_user_id'
];

    public function ocena()
    {
        return $this->belongsTo(Ocena::class, 'ocena_id');
    }
}
