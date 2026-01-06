<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Klasa;
use App\Models\Przedmiot;
use App\Models\NauczycielPrzedmiotKlasa;
use Illuminate\Support\Facades\Hash;

// Import wszystkich Requestów (Walidatorów)
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreKlasaRequest;
use App\Http\Requests\StorePrzedmiotRequest;
use App\Http\Requests\StorePrzydzialRequest;
use App\Http\Requests\StoreUczenKlasaRequest; // Nowy
use App\Http\Requests\StoreRodzicUczenRequest; // Nowy

class AdminController extends Controller
{
    // === 1. UŻYTKOWNICY ===

    public function indexUsers()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(StoreUserRequest $request)
    {
        User::create([
            'login' => $request->validated()['login'],
            'password' => Hash::make($request->validated()['password']),
            'role' => $request->validated()['role'],
            'imie' => $request->validated()['imie'],
            'nazwisko' => $request->validated()['nazwisko'],
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Użytkownik dodany.');
    }

    // === 2. KLASY ===

    public function indexKlasy()
    {
        $klasy = Klasa::with('wychowawca')->get();
        return view('admin.klasy.index', compact('klasy'));
    }

    public function createKlasa()
    {
        $nauczyciele = User::where('role', 'nauczyciel')->get();
        return view('admin.klasy.create', compact('nauczyciele'));
    }

    public function storeKlasa(StoreKlasaRequest $request)
    {
        Klasa::create($request->validated());
        return redirect()->route('admin.klasy.index')->with('success', 'Klasa utworzona.');
    }

    // === 3. PRZEDMIOTY ===

    public function indexPrzedmioty()
    {
        $przedmioty = Przedmiot::all();
        return view('admin.przedmioty.index', compact('przedmioty'));
    }

    public function createPrzedmiot()
    {
        return view('admin.przedmioty.create');
    }

    public function storePrzedmiot(StorePrzedmiotRequest $request)
    {
        Przedmiot::create($request->validated());
        return redirect()->route('admin.przedmioty.index')->with('success', 'Przedmiot dodany.');
    }

    // === 4. PRZYDZIAŁY (Nauczyciel -> Przedmiot) ===

    public function indexPrzydzialy()
    {
        $przydzialy = NauczycielPrzedmiotKlasa::with(['klasa', 'przedmiot', 'nauczyciel'])->get();
        return view('admin.przydzialy.index', compact('przydzialy'));
    }

    public function createPrzydzial()
    {
        $klasy = Klasa::all();
        $przedmioty = Przedmiot::all();
        $nauczyciele = User::where('role', 'nauczyciel')->get();
        return view('admin.przydzialy.nauczyciel', compact('klasy', 'przedmioty', 'nauczyciele'));
    }

    public function storePrzydzial(StorePrzydzialRequest $request)
    {
        NauczycielPrzedmiotKlasa::create($request->validated());
        return redirect()->route('admin.przydzialy.index')->with('success', 'Przydział utworzony.');
    }

    // === 5. PRZYPISYWANIE (Uczniowie / Rodzice) ===

    // A. Uczeń -> Klasa
    public function createUczenKlasa()
    {
        $klasy = Klasa::all();
        $uczniowie = User::where('role', 'uczen')->get();
        return view('admin.przydzialy.uczen_klasa', compact('klasy', 'uczniowie'));
    }

    public function storeUczenKlasa(StoreUczenKlasaRequest $request)
    {
        $klasa = Klasa::findOrFail($request->validated()['klasa_id']);
        
        // syncWithoutDetaching dodaje nowych uczniów, nie usuwając tych już przypisanych
        $klasa->uczniowie()->syncWithoutDetaching($request->validated()['uczniowie_ids']);
        
        return back()->with('success', 'Uczniowie zostali przypisani do klasy.');
    }

    // B. Rodzic -> Uczeń
    public function createRodzicUczen()
    {
        $rodzice = User::where('role', 'rodzic')->get();
        $uczniowie = User::where('role', 'uczen')->get();
        return view('admin.przydzialy.rodzic_uczen', compact('rodzice', 'uczniowie'));
    }

    public function storeRodzicUczen(StoreRodzicUczenRequest $request)
    {
        $rodzic = User::find($request->validated()['rodzic_id']);
        
        // Dzięki walidacji w Request wiemy, że to połączenie jeszcze nie istnieje
        $rodzic->dzieci()->attach($request->validated()['uczen_id']);
        
        return back()->with('success', 'Rodzic został powiązany z uczniem.');
    }
}
