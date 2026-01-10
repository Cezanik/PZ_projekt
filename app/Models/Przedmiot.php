<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Przedmiot extends Model
{
    use HasFactory;

    protected $table = 'przedmioty';
    
    public $timestamps = false;

    protected $fillable = [
        'nazwa'
    ];

    public function oceny()
    {
        return $this->hasMany(Ocena::class, 'przedmiot_id');
    }
}
