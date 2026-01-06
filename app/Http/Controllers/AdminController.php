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
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateKlasaRequest;
use App\Http\Requests\UpdatePrzedmiotRequest;
use App\Http\Requests\UpdateUczenKlasaRequest;
use App\Http\Requests\UpdateRodzicUczenRequest;
use App\Http\Requests\UpdatePrzydzialRequest;

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

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Zmieniamy Request na UpdateUserRequest
    public function updateUser(UpdateUserRequest $request, User $user)
    {
        // Dane są już zweryfikowane. Pobieramy je metodą validated()
        $data = $request->validated();

        // Obsługa hasła (jeśli puste, usuwamy z tablicy, żeby nie nadpisało starego pustym stringiem)
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('message', 'Dane użytkownika zaktualizowane poprawnie.');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return back()->with('message', 'Użytkownik został usunięty.');
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


    public function editKlasa(Klasa $klasa)
    {
        $nauczyciele = User::where('role', 'nauczyciel')->get();
        return view('admin.klasy.edit', compact('klasa', 'nauczyciele'));
    }

    // Zmieniamy Request na UpdateKlasaRequest
    public function updateKlasa(UpdateKlasaRequest $request, Klasa $klasa)
    {
        $klasa->update($request->validated());

        return redirect()->route('admin.klasy.index')
            ->with('message', 'Klasa zaktualizowana poprawnie.');
    }

    public function destroyKlasa(Klasa $klasa)
    {
        $klasa->delete();
        return back()->with('message', 'Klasa została usunięta.');
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
    public function editPrzedmiot(Przedmiot $przedmiot)
    {
        return view('admin.przedmioty.edit', compact('przedmiot'));
    }

    // Zmieniamy Request na UpdatePrzedmiotRequest
    public function updatePrzedmiot(UpdatePrzedmiotRequest $request, Przedmiot $przedmiot)
    {
        $przedmiot->update($request->validated());

        return redirect()->route('admin.przedmioty.index')
            ->with('message', 'Przedmiot zaktualizowany poprawnie.');
    }
    public function destroyPrzedmiot(Przedmiot $przedmiot)
    {
        $przedmiot->delete();
        return back()->with('message', 'Przedmiot usunięty.');
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

    public function editPrzydzial(NauczycielPrzedmiotKlasa $przydzial)
    {
        $klasy = Klasa::all();
        $przedmioty = Przedmiot::all();
        $nauczyciele = User::where('role', 'nauczyciel')->get();

        return view('admin.przydzialy.edit_nauczyciel', compact('przydzial', 'klasy', 'przedmioty', 'nauczyciele'));
    }

    public function updatePrzydzial(UpdatePrzydzialRequest $request, NauczycielPrzedmiotKlasa $przydzial)
    {
        // Aktualizujemy rekord zwalidowanymi danymi
        $przydzial->update($request->validated());

        return redirect()->route('admin.przydzialy.index')
            ->with('message', 'Przydział został zaktualizowany.');
    }

    public function destroyPrzydzial(NauczycielPrzedmiotKlasa $przydzial)
    {
        $przydzial->delete();
        return back()->with('message', 'Przydział został usunięty.');
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
    public function editUczenKlasa(User $uczen, Klasa $klasa)
    {
        // Pobieramy wszystkie klasy do listy rozwijanej
        $dostepneKlasy = Klasa::all();
        return view('admin.przydzialy.edit_uczen_klasa', compact('uczen', 'klasa', 'dostepneKlasy'));
    }

    public function updateUczenKlasa(UpdateUczenKlasaRequest $request, User $uczen, Klasa $klasa)
    {
        // 1. Odłączamy starą klasę
        $uczen->klasaUcznia()->detach($klasa->id);
        
        // 2. Przypisujemy nową klasę
        // Używamy syncWithoutDetaching lub attach, aby dodać nowe powiązanie
        $uczen->klasaUcznia()->attach($request->nowa_klasa_id);

        return redirect()->route('admin.uczen.klasa.index')
            ->with('message', 'Przypisanie ucznia do klasy zostało zaktualizowane.');
    }

    public function destroyUczenKlasa(User $uczen, Klasa $klasa)
    {
        $uczen->klasaUcznia()->detach($klasa->id);
        
        return back()->with('message', 'Uczeń został wypisany z klasy.');
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
    public function indexUczenKlasa()
    {
        // Pobieramy uczniów, którzy mają przypisaną klasę
        // Używamy 'has', żeby nie pokazywać uczniów bez klasy (opcjonalne)
        $uczniowie = User::where('role', 'uczen')
            ->has('klasaUcznia') 
            ->with('klasaUcznia')
            ->get();

        return view('admin.przydzialy.index_uczen_klasa', compact('uczniowie'));
    }

    public function indexRodzicUczen()
    {
        // Pobieramy rodziców wraz z ich dziećmi
        $rodzice = User::where('role', 'rodzic')
            ->has('dzieci')
            ->with('dzieci')
            ->get();

        return view('admin.przydzialy.index_rodzic_uczen', compact('rodzice'));
    }
    public function editRodzicUczen(User $rodzic, User $uczen)
    {
        // Pobieramy wszystkich uczniów do wyboru
        $wszyscyUczniowie = User::where('role', 'uczen')->get();
        return view('admin.przydzialy.edit_rodzic_uczen', compact('rodzic', 'uczen', 'wszyscyUczniowie'));
    }

    public function updateRodzicUczen(UpdateRodzicUczenRequest $request, User $rodzic, User $uczen)
    {
        // 1. Odłączamy stare dziecko (ucznia)
        $rodzic->dzieci()->detach($uczen->id);
        
        // 2. Przypisujemy nowego ucznia
        $rodzic->dzieci()->attach($request->nowy_uczen_id);

        return redirect()->route('admin.rodzic.uczen.index')
            ->with('message', 'Powiązanie rodzic-uczeń zostało zaktualizowane.');
    }

    public function destroyRodzicUczen(User $rodzic, User $uczen)
    {
        $rodzic->dzieci()->detach($uczen->id);
        
        return back()->with('message', 'Powiązanie rodzic-uczeń zostało usunięte.');
    }
}
