<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocena extends Model
{
    protected $table = 'oceny';
    use HasFactory;

    // Relacje
    public function uczen()
    {
        return $this->belongsTo(User::class, 'uczen_id');
    }

    public function nauczyciel()
    {
        return $this->belongsTo(User::class, 'nauczyciel_id');
    }

    public function przedmiot()
    {
        return $this->belongsTo(Przedmiot::class, 'przedmiot_id');
    }

    // Relacja do historii zmian tej oceny
    public function historia()
    {
        return $this->hasMany(OcenaHistoria::class, 'ocena_id');
    }

}
