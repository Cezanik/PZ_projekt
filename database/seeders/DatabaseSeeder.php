<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Klasa;      // Dodany import
use App\Models\Przedmiot;  // Dodany import
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Administrator
        User::firstOrCreate(
            ['login' => 'admin'], 
            [
                'password' => Hash::make('admin123'), 
                'role' => 'admin',                    
                'imie' => 'Główny',
                'nazwisko' => 'Administrator',
            ]
        );

        // 2. Nauczyciel 
        $nauczyciel = User::firstOrCreate(
            ['login' => 'nauczyciel'],
            [
                'password' => Hash::make('nauczyciel123'),
                'role' => 'nauczyciel',
                'imie' => 'Jan',
                'nazwisko' => 'Kowalski',
            ]
        );

        // 3. Tworzenie  uczniów
        for ($i = 1; $i <= 5; $i++) {
            User::firstOrCreate(
                ['login' => "uczen$i"],
                [
                    'password' => Hash::make('uczen123'),
                    'role' => 'uczen',
                    'imie' => 'Uczeń',
                    'nazwisko' => "Numer $i",
                ]
            );
        }

        // 4. Tworzenie  rodziców
        for ($i = 1; $i <= 2; $i++) {
            User::firstOrCreate(
                ['login' => "rodzic$i"], 
                [
                    'password' => Hash::make('rodzic123'),
                    'role' => 'rodzic',
                    'imie' => 'Rodzic',
                    'nazwisko' => "Numer $i",
                ]
            );
        }

        // 5. Tworzenie Klasy

        Klasa::firstOrCreate(
            ['nazwa' => '1A'],
 
        );

        // 6. Tworzenie Przedmiotu
        Przedmiot::firstOrCreate(
            ['nazwa' => 'Matematyka']
        );
    }
}
