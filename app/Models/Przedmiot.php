<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Przedmiot extends Model
{
    protected $table = 'przedmioty';
    use HasFactory;

    // Relacja do ocen z tego przedmiotu
    public function oceny()
    {
        return $this->hasMany(Ocena::class, 'przedmiot_id');
    }
}
