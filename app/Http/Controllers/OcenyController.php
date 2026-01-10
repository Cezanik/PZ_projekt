<?php

namespace App\Http\Controllers;

use App\Models\Ocena;
use App\Models\OcenaHistoria;
use App\Models\NauczycielPrzedmiotKlasa;
use App\Models\User;
use App\Models\Klasa;
use App\Models\Przedmiot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Pamiętaj o importach Requestów!
use App\Http\Requests\StoreOcenaRequest;
use App\Http\Requests\UpdateOcenaRequest;

class OcenyController extends Controller
{
    // === DLA NAUCZYCIELA: WIDOKI ===

    // 1. Lista przydziałów (To naprawia link "Dziennik" w dashboardzie)
    public function indexPrzydzialy()
    {
        $nauczycielId = Auth::id();
        $przydzialy = NauczycielPrzedmiotKlasa::with(['klasa', 'przedmiot'])
            ->where('nauczyciel_id', $nauczycielId)
            ->get();

        return view('nauczyciel.lista_przydzialow', compact('przydzialy'));
    }

    // 2. Arkusz ocen
    public function showArkusz($klasaId, $przedmiotId)
    {
        // Weryfikacja dostępu (uproszczona)
        $maDostep = NauczycielPrzedmiotKlasa::where('nauczyciel_id', Auth::id())
            ->where('klasa_id', $klasaId)
            ->where('przedmiot_id', $przedmiotId)
            ->exists();

        if (!$maDostep) {
            abort(403, 'Brak dostępu do tej klasy/przedmiotu.');
        }

        $klasa = Klasa::findOrFail($klasaId);
        $przedmiot = Przedmiot::findOrFail($przedmiotId);

        $uczniowie = User::where('role', 'uczen')
            ->whereHas('klasaUcznia', function($q) use ($klasaId) {
                $q->where('klasa_id', $klasaId);
            })
            ->with(['ocenyOtrzymane' => function($q) use ($przedmiotId) {
                $q->where('przedmiot_id', $przedmiotId);
            }])
            ->get();

        return view('nauczyciel.arkusz_ocen', compact('klasa', 'przedmiot', 'uczniowie'));
    }

    // 3. Formularz dodawania
    public function create($uczenId, $przedmiotId)
    {
        $uczen = User::findOrFail($uczenId);
        $przedmiot = Przedmiot::findOrFail($przedmiotId);
        return view('nauczyciel.ocena_create', compact('uczen', 'przedmiot'));
    }

    // 4. Formularz edycji
    public function edit(Ocena $ocena)
    {
        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Brak uprawnień.');
        }
        return view('nauczyciel.ocena_edit', compact('ocena'));
    }

    // 5. Historia
    public function showHistoria(Ocena $ocena)
    {
        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Brak uprawnień.');
        }
        $historia = OcenaHistoria::where('ocena_id', $ocena->id)
                    ->orderBy('data_zmiany', 'desc')
                    ->get();
        return view('nauczyciel.historia_zmian', compact('ocena', 'historia'));
    }
    public function showUczenOceny($uczenId, $przedmiotId)
    {
        $uczen = User::findOrFail($uczenId);
        $przedmiot = Przedmiot::findOrFail($przedmiotId);
        
        // Pobieramy oceny z tego przedmiotu dla tego ucznia
        $oceny = Ocena::where('uczen_id', $uczenId)
                      ->where('przedmiot_id', $przedmiotId)
                      ->orderBy('created_at', 'desc') // Najnowsze na górze
                      ->get();

        // Potrzebujemy ID klasy, żeby zrobić przycisk "Wróć"
        $klasaId = $uczen->klasaUcznia()->first()->id ?? 0;

        return view('nauczyciel.uczen_oceny', compact('uczen', 'przedmiot', 'oceny', 'klasaId'));
    }

    // 7. Usuwanie oceny
    public function destroy(Ocena $ocena)
    {
        // Sprawdzenie czy to ocena wystawiona przez zalogowanego nauczyciela
        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Możesz usuwać tylko oceny wystawione przez siebie.');
        }

        $ocena->delete();

        return back()->with('success', 'Ocena została usunięta.');
    }
    public function myHistory()
    {
        // Pobieramy wpisy z historii, gdzie 'zmienil_user_id' to obecny użytkownik
        $historia = OcenaHistoria::where('zmienil_user_id', Auth::id())
            ->with(['ocena.uczen', 'ocena.przedmiot']) // Eager loading relacji
            ->orderBy('data_zmiany', 'desc')
            ->get();

        return view('nauczyciel.historia_globalna', compact('historia'));
    }

    // === AKCJE (Store / Update / Revert) ===

    public function store(StoreOcenaRequest $request)
    {
        $validated = $request->validated();

        Ocena::create([
            'uczen_id' => $validated['uczen_id'],
            'nauczyciel_id' => Auth::id(),
            'przedmiot_id' => $validated['przedmiot_id'],
            'wartosc' => $validated['wartosc'],
            'opis' => $validated['opis'] ?? '',
            'data_wystawienia' => Carbon::now()
        ]);

        // Powrót do arkusza
        $uczen = User::find($validated['uczen_id']);
        $klasaId = $uczen->klasaUcznia()->first()->id ?? 0;

        return redirect()
            ->route('nauczyciel.arkusz.show', ['klasa' => $klasaId, 'przedmiot' => $validated['przedmiot_id']])
            ->with('success', 'Ocena wystawiona.');
    }

    public function update(UpdateOcenaRequest $request, Ocena $ocena)
    {
        // UpdateOcenaRequest sprawdza role, tutaj sprawdzamy właściciela
        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Możesz edytować tylko swoje oceny.');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($request, $ocena, $validated) {
            // Zapis do historii (dostosowałem nazwy kolumn do Twoich migracji/kodu)
            OcenaHistoria::create([
                'ocena_id' => $ocena->id,
                'stara_wartosc' => $ocena->wartosc, // lub 'poprzednia_wartosc' zależnie od migracji
                'stara_opis' => $ocena->opis,
                'data_zmiany' => Carbon::now(),
                'powod_zmiany' => $validated['powod_zmiany'], // lub 'powod'
                'zmienil_user_id' => Auth::id()
            ]);

            $ocena->update([
                'wartosc' => $validated['wartosc'],
                'opis' => $validated['opis']
            ]);
        });

        $klasaId = $ocena->uczen->klasaUcznia()->first()->id ?? 0;
        return redirect()
            ->route('nauczyciel.arkusz.show', ['klasa' => $klasaId, 'przedmiot' => $ocena->przedmiot_id])
            ->with('success', 'Ocena zaktualizowana.');
    }

    public function revert($historia_id)
    {
        $historia = OcenaHistoria::findOrFail($historia_id);
        $ocena = $historia->ocena; // Upewnij się, że masz relację w modelu

        if ($ocena->nauczyciel_id !== Auth::id()) {
             abort(403, 'Brak uprawnień.');
        }

        // Przywracanie
        $staraWartosc = $historia->stara_wartosc; // lub $historia->poprzednia_wartosc
        $staraOpis = $historia->stara_opis; 

        $ocena->update([
            'wartosc' => $staraWartosc,
            'opis' => $staraOpis
        ]);
        
        // Logujemy revert w historii
        OcenaHistoria::create([
            'ocena_id' => $ocena->id,
            'stara_wartosc' => $ocena->wartosc,
            'stara_opis' => $ocena->opis,
            'data_zmiany' => Carbon::now(),
            'powod_zmiany' => 'Cofnięcie zmiany (ID historii: ' . $historia_id . ')',
            'zmienil_user_id' => Auth::id()
        ]);

        return back()->with('success', 'Przywrócono poprzednią ocenę.');
    }

    // --- UCZEŃ / RODZIC ---
    public function myGrades()
{
    $user = Auth::user();
    $oceny = Ocena::with('przedmiot', 'nauczyciel')
                  ->where('uczen_id', $user->id)
                  ->get()
                  ->groupBy('przedmiot.nazwa');
                  
    return view('uczen.oceny', compact('oceny'));
}

    public function childrenGrades()
    {
        $rodzic = Auth::user();
        $dzieci = $rodzic->dzieci()->with(['ocenyOtrzymane.przedmiot', 'ocenyOtrzymane.nauczyciel'])->get();
        return view('rodzic.oceny', compact('dzieci'));
    }
}
