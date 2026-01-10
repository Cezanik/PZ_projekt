<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'password',
        'role',
        'imie',
        'nazwisko',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function klasaUcznia()
    {
        return $this->belongsToMany(Klasa::class, 'uczniowie_klasy', 'uczen_id', 'klasa_id');
    }

    public function ocenyOtrzymane()
    {
        return $this->hasMany(Ocena::class, 'uczen_id');
    }

    public function ocenyWystawione()
    {
        return $this->hasMany(Ocena::class, 'nauczyciel_id');
    }
    public function dzieci()
    {
        return $this->belongsToMany(User::class, 'rodzic_uczen', 'rodzic_id', 'uczen_id');
    }
    
    public function rodzice()
    {
        return $this->belongsToMany(User::class, 'rodzic_uczen', 'uczen_id', 'rodzic_id');
    }
    
}
