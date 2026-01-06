<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcenaHistoria extends Model
{
    protected $table = 'oceny_historia';
    use HasFactory;

    public function ocena()
    {
        return $this->belongsTo(Ocena::class, 'ocena_id');
    }
}
