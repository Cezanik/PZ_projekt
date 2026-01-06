<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       User::firstOrCreate(
            ['login' => 'admin'], // Szukamy po loginie
            [
                'password' => Hash::make('admin123'), // Hasło (zahaszowane!)
                'role' => 'admin',                    // Rola zgodna z enumem
                'imie' => 'Główny',
                'nazwisko' => 'Administrator',
            ]
        );

        User::firstOrCreate(
            ['login' => 'nauczyciel'],
            [
                'password' => Hash::make('nauczyciel123'),
                'role' => 'nauczyciel',
                'imie' => 'Jan',
                'nazwisko' => 'Kowalski',
            ]
        );
    }
}
