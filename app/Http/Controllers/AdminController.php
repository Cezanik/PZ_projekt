<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Klasa;
use App\Models\Przedmiot;
use App\Models\NauczycielPrzedmiotKlasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // --- UŻYTKOWNICY ---

    // Tworzenie użytkownika (dowolna rola)
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,nauczyciel,uczen,rodzic',
            'imie' => 'required',
            'nazwisko' => 'required',
        ]);

        User::create([
            'login' => $validated['login'],
            'password' => Hash::make($validated['password']), // Haszowanie hasła!
            'role' => $validated['role'],
            'imie' => $validated['imie'],
            'nazwisko' => $validated['nazwisko'],
        ]);

        return back()->with('success', 'Użytkownik dodany.');
    }

    // --- KLASY ---

    // Tworzenie klasy i przypisanie wychowawcy
    public function createKlasa(Request $request)
    {
        $validated = $request->validate([
            'nazwa' => 'required|unique:klasy',
            'wychowawca_id' => 'required|exists:users,id', // Musi być ID istniejącego usera
        ]);

        Klasa::create($validated);
        return back()->with('success', 'Klasa utworzona.');
    }

    // Przypisywanie uczniów do klas (Masowe i pojedyncze)
    public function assignStudentsToKlasa(Request $request, Klasa $klasa)
    {
        // $request->uczniowie_ids to tablica ID, np. [1, 5, 12]
        $uczniowieIds = $request->input('uczniowie_ids');
        
        // Metoda sync dodaje nowych i usuwa tych, których nie ma na liście (dobre do edycji)
        // Metoda attach tylko dodaje (dobre do dodawania)
        $klasa->uczniowie()->syncWithoutDetaching($uczniowieIds);

        return back()->with('success', 'Uczniowie przypisani.');
    }

    // --- PRZEDMIOTY I NAUCZYCIELE ---

    public function createPrzedmiot(Request $request)
    {
        Przedmiot::create(['nazwa' => $request->nazwa]);
        return back()->with('success', 'Przedmiot dodany.');
    }

    // Kluczowe: Przypisanie nauczyciela do przedmiotu w klasie
    public function assignTeacherToSubjectInClass(Request $request)
    {
        NauczycielPrzedmiotKlasa::create([
            'klasa_id' => $request->klasa_id,
            'przedmiot_id' => $request->przedmiot_id,
            'nauczyciel_id' => $request->nauczyciel_id
        ]);

        return back()->with('success', 'Przydział utworzony.');
    }
    
    // Powiązanie Dziecka z Rodzicem
    public function linkParentToStudent(Request $request)
    {
        $rodzic = User::find($request->rodzic_id);
        $rodzic->dzieci()->attach($request->uczen_id);
        
        return back()->with('success', 'Rodzic powiązany z uczniem.');
    }
}
