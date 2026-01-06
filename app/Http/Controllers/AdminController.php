<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Klasa;
use App\Models\Przedmiot;
use App\Models\NauczycielPrzedmiotKlasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\StoreUserRequest;

class AdminController extends Controller
{
    // --- METODY WYŚWIETLAJĄCE WIDOKI (GET) ---
    // Zgodnie z PDF str. 8[cite: 1615], kontroler powinien mieć metodę zwracającą widok

    public function showUserForm()
    {
        return view('admin.users.create');
    }

    public function showKlasaForm()
    {
        $nauczyciele = User::where('role', 'nauczyciel')->get();
        return view('admin.klasy.create', compact('nauczyciele'));
    }

    public function showPrzedmiotForm()
    {
        return view('admin.przedmioty.create');
    }

    public function showPrzydzialNauczycielaForm()
    {
        $klasy = Klasa::all();
        $przedmioty = Przedmiot::all();
        $nauczyciele = User::where('role', 'nauczyciel')->get();
        return view('admin.przydzialy.nauczyciel', compact('klasy', 'przedmioty', 'nauczyciele'));
    }

    public function showPrzydzialUczniaForm()
    {
        $klasy = Klasa::all();
        $uczniowie = User::where('role', 'uczen')->get();
        $rodzice = User::where('role', 'rodzic')->get();
        return view('admin.przydzialy.uczen', compact('klasy', 'uczniowie', 'rodzice'));
    }

    // --- METODY LOGIKI BIZNESOWEJ (POST) ---

    public function createUser(StoreUserRequest $request)
    {
        // Tutaj dane są już zwalidowane!
        // Pobieramy je metodą $request->validated()
        $validated = $request->validated();

        User::create([
            'login' => $validated['login'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'imie' => $validated['imie'],
            'nazwisko' => $validated['nazwisko'],
        ]);

        return redirect()->route('admin.user.create')->with('success', 'Użytkownik dodany.');
    }

    public function createKlasa(Request $request)
    {
        $validated = $request->validate([
            'nazwa' => 'required|unique:klasy',
            'wychowawca_id' => 'required|exists:users,id',
        ]);

        Klasa::create($validated);
        return redirect()->route('admin.klasa.create')->with('success', 'Klasa utworzona.');
    }

    public function createPrzedmiot(Request $request)
    {
        Przedmiot::create(['nazwa' => $request->nazwa]);
        return redirect()->route('admin.przedmiot.create')->with('success', 'Przedmiot dodany.');
    }

    public function assignTeacherToSubjectInClass(Request $request)
    {
        NauczycielPrzedmiotKlasa::create([
            'klasa_id' => $request->klasa_id,
            'przedmiot_id' => $request->przedmiot_id,
            'nauczyciel_id' => $request->nauczyciel_id
        ]);
        return back()->with('success', 'Przydział utworzony.');
    }
    
    // Wrapper pomocniczy
    public function assignStudentsToKlasaCustom(Request $request)
    {
        $klasa = Klasa::findOrFail($request->klasa_id);
        return $this->assignStudentsToKlasa($request, $klasa);
    }

    public function assignStudentsToKlasa(Request $request, Klasa $klasa)
    {
        $uczniowieIds = $request->input('uczniowie_ids');
        $klasa->uczniowie()->syncWithoutDetaching($uczniowieIds);
        return back()->with('success', 'Uczniowie przypisani.');
    }

    public function linkParentToStudent(Request $request)
    {
        $rodzic = User::find($request->rodzic_id);
        $rodzic->dzieci()->attach($request->uczen_id);
        return back()->with('success', 'Rodzic powiązany z uczniem.');
    }
}
