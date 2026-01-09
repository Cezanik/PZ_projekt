<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Ocena;
use App\Models\OcenaHistoria;
use App\Models\NauczycielPrzedmiotKlasa;
use App\Models\User;
use App\Models\Klasa;
use App\Models\Przedmiot;

// Import Requestów
use App\Http\Requests\StoreOcenaRequest;
use App\Http\Requests\UpdateOcenaRequest;

class OcenyController extends Controller
{
    // ==========================================
    // SEKCJA NAUCZYCIELA
    // ==========================================

    /**
     * 1. Lista przydziałów (Klasy i Przedmioty nauczyciela)
     */
    public function indexPrzydzialy()
    {
        $nauczycielId = Auth::id();

        // Pobieramy unikalne przydziały dla zalogowanego nauczyciela
        $przydzialy = NauczycielPrzedmiotKlasa::with(['klasa', 'przedmiot'])
            ->where('nauczyciel_id', $nauczycielId)
            ->get();

        return view('nauczyciel.lista_przydzialow', compact('przydzialy'));
    }

    /**
     * 2. Widok Arkusza (Tylko podgląd listy uczniów i ocen)
     */
    public function showArkusz($klasaId, $przedmiotId)
    {
        // Weryfikacja czy nauczyciel ma prawo do tej klasy i przedmiotu
        $maDostep = NauczycielPrzedmiotKlasa::where('nauczyciel_id', Auth::id())
            ->where('klasa_id', $klasaId)
            ->where('przedmiot_id', $przedmiotId)
            ->exists();

        if (!$maDostep) {
            abort(403, 'Brak dostępu do tej klasy/przedmiotu.');
        }

        $klasa = Klasa::findOrFail($klasaId);
        $przedmiot = Przedmiot::findOrFail($przedmiotId);

        // Pobieramy uczniów z danej klasy wraz z ocenami z KONKRETNEGO przedmiotu
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

    /**
     * 3. Formularz dodawania oceny (NOWE)
     */
    public function create($uczenId, $przedmiotId)
    {
        $uczen = User::findOrFail($uczenId);
        $przedmiot = Przedmiot::findOrFail($przedmiotId);

        // Opcjonalnie: Dodatkowa weryfikacja czy nauczyciel uczy tego ucznia
        
        return view('nauczyciel.ocena_create', compact('uczen', 'przedmiot'));
    }

    /**
     * 4. Zapis nowej oceny (Obsługa formularza)
     */
    public function store(StoreOcenaRequest $request)
    {
        $data = $request->validated();

        Ocena::create([
            'uczen_id'         => $data['uczen_id'],
            'nauczyciel_id'    => Auth::id(),
            'przedmiot_id'     => $data['przedmiot_id'],
            'wartosc'          => $data['wartosc'],
            'opis'             => $data['opis'],
            'data_wystawienia' => now(),
        ]);

        // Pobieramy klasę ucznia, aby wrócić do odpowiedniego arkusza
        $uczen = User::find($data['uczen_id']);
        // Zakładamy, że uczeń ma jedną klasę (relacja klasaUcznia)
        $klasaId = $uczen->klasaUcznia()->first()->id;

        return redirect()
            ->route('nauczyciel.arkusz.show', ['klasa' => $klasaId, 'przedmiot' => $data['przedmiot_id']])
            ->with('success', 'Ocena została wystawiona.');
    }

    /**
     * 5. Formularz edycji oceny (NOWE)
     */
    public function edit(Ocena $ocena)
    {
        // Sprawdzenie czy nauczyciel edytuje SWOJĄ ocenę
        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Możesz edytować tylko wystawione przez siebie oceny.');
        }

        return view('nauczyciel.ocena_edit', compact('ocena'));
    }

    /**
     * 6. Aktualizacja oceny z historią zmian
     */
    public function update(UpdateOcenaRequest $request, Ocena $ocena)
    {
        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Brak uprawnień do edycji tej oceny.');
        }

        $data = $request->validated();

        DB::transaction(function () use ($request, $ocena, $data) {
            // A. Zapis starej wersji do historii
            OcenaHistoria::create([
                'ocena_id'         => $ocena->id,
                'stara_wartosc'    => $ocena->wartosc,
                'stara_opis'       => $ocena->opis,
                'data_zmiany'      => now(),
                'powod_zmiany'     => $data['powod_zmiany'],
                'zmienil_user_id'  => Auth::id(),
            ]);

            // B. Aktualizacja oceny
            $ocena->update([
                'wartosc' => $data['wartosc'],
                'opis'    => $data['opis'],
            ]);
        });

        // Przekierowanie powrotne do arkusza
        $klasaId = $ocena->uczen->klasaUcznia()->first()->id;

        return redirect()
            ->route('nauczyciel.arkusz.show', ['klasa' => $klasaId, 'przedmiot' => $ocena->przedmiot_id])
            ->with('success', 'Ocena została zaktualizowana.');
    }

    /**
     * 7. Historia zmian oceny
     */
    public function showHistoria(Ocena $ocena)
    {
        // Sprawdzamy, czy nauczyciel wystawił tę ocenę (lub ma prawo wglądu)
        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Brak uprawnień do przeglądania historii tej oceny.');
        }

        $historia = OcenaHistoria::where('ocena_id', $ocena->id)
                    ->orderBy('data_zmiany', 'desc')
                    ->get();

        return view('nauczyciel.historia_zmian', compact('ocena', 'historia'));
    }

    // Opcjonalnie: Przywracanie oceny z historii (było w routingu 'revert')
    public function revert($historiaId)
    {
        $wpisHistorii = OcenaHistoria::findOrFail($historiaId);
        $ocena = Ocena::findOrFail($wpisHistorii->ocena_id);

        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Brak uprawnień.');
        }

        // Logika przywracania (zamiana miejscami)
        DB::transaction(function () use ($ocena, $wpisHistorii) {
            // Zapisujemy obecny stan jako historię (że przywrócono)
            OcenaHistoria::create([
                'ocena_id' => $ocena->id,
                'stara_wartosc' => $ocena->wartosc,
                'stara_opis' => $ocena->opis,
                'data_zmiany' => now(),
                'powod_zmiany' => 'Przywrócenie wersji z dnia ' . $wpisHistorii->data_zmiany,
                'zmienil_user_id' => Auth::id(),
            ]);

            // Przywracamy stare wartości
            $ocena->update([
                'wartosc' => $wpisHistorii->stara_wartosc,
                'opis' => $wpisHistorii->stara_opis
            ]);
        });

        return back()->with('success', 'Przywrócono wersję oceny.');
    }


    // ==========================================
    // SEKCJA UCZNIA I RODZICA (Zgodność z routes/web.php)
    // ==========================================

    public function myGrades()
    {
        $uczen = Auth::user();
        
        // Pobierz oceny pogrupowane przedmiotami
        $oceny = $uczen->ocenyOtrzymane()->with('przedmiot', 'nauczyciel')->get()->groupBy('przedmiot.nazwa');

        return view('uczen.oceny', compact('oceny'));
    }

    public function childrenGrades()
    {
        $rodzic = Auth::user();
        
        // Pobierz dzieci z ich ocenami
        $dzieci = $rodzic->dzieci()->with(['ocenyOtrzymane.przedmiot', 'ocenyOtrzymane.nauczyciel'])->get();

        return view('rodzic.oceny', compact('dzieci'));
    }
}
