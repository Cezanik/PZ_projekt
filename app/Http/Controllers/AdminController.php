<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Klasa;
use App\Models\Przedmiot;
use App\Models\NauczycielPrzedmiotKlasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreKlasaRequest;
use App\Http\Requests\StorePrzedmiotRequest;
use App\Http\Requests\StorePrzydzialRequest;


class AdminController extends Controller
{
    // === 1. UŻYTKOWNICY (USERS) ===

    public function indexUsers()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function createUser() // Formularz
    {
        return view('admin.users.create');
    }

    public function storeUser(StoreUserRequest $request) // Zapis
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

    // === 2. KLASY (CLASSES) ===

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

    // === 3. PRZEDMIOTY (SUBJECTS) ===

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

    // === 4. PRZYDZIAŁY NAUCZYCIELI (TEACHER ASSIGNMENTS) ===

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

    // === 5. PRZYPISYWANIE UCZNIÓW I RODZICÓW ===

    // Uczeń -> Klasa
    public function createUczenKlasa()
    {
        $klasy = Klasa::all();
        $uczniowie = User::where('role', 'uczen')->get();
        return view('admin.przydzialy.uczen_klasa', compact('klasy', 'uczniowie'));
    }

    public function storeUczenKlasa(Request $request)
    {
        $request->validate([
            'klasa_id' => 'required|exists:klasy,id',
            'uczniowie_ids' => 'required|array',
            'uczniowie_ids.*' => 'exists:users,id'
        ]);

        $klasa = Klasa::findOrFail($request->klasa_id);
        $klasa->uczniowie()->syncWithoutDetaching($request->uczniowie_ids);
        
        return back()->with('success', 'Uczniowie przypisani.');
    }

    // Rodzic -> Uczeń
    public function createRodzicUczen()
    {
        $rodzice = User::where('role', 'rodzic')->get();
        $uczniowie = User::where('role', 'uczen')->get();
        return view('admin.przydzialy.rodzic_uczen', compact('rodzice', 'uczniowie'));
    }

    public function storeRodzicUczen(Request $request)
    {
        $request->validate([
            'rodzic_id' => 'required|exists:users,id',
            'uczen_id' => [
                'required',
                'exists:users,id',
                // Sprawdzamy unikalność pary w tabeli 'rodzic_uczen'
                Rule::unique('rodzic_uczen', 'uczen_id')->where(function ($query) use ($request) {
                    return $query->where('rodzic_id', $request->rodzic_id);
                }),
            ]
        ], [
            // Własny komunikat błędu
            'uczen_id.unique' => 'Ten uczeń jest już przypisany do tego rodzica.'
        ]);

        $rodzic = User::find($request->rodzic_id);
        $rodzic->dzieci()->attach($request->uczen_id);
        
        return back()->with('success', 'Rodzic powiązany.');
    }
}
