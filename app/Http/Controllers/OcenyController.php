<?php

namespace App\Http\Controllers;

use App\Models\Ocena;
use App\Models\OcenaHistoria;
use App\Models\NauczycielPrzedmiotKlasa;
use App\Models\User;
use App\Models\Klasa;
use App\Models\Przedmiot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Requests\StoreOcenaRequest;
use App\Http\Requests\UpdateOcenaRequest;

class OcenyController extends Controller
{
    public function indexPrzydzialy()
    {
        $nauczycielId = Auth::id();
        $przydzialy = NauczycielPrzedmiotKlasa::with(['klasa', 'przedmiot'])
            ->where('nauczyciel_id', $nauczycielId)
            ->get();

        return view('nauczyciel.lista_przydzialow', compact('przydzialy'));
    }

    public function showArkusz($klasaId, $przedmiotId)
    {
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

    public function create($uczenId, $przedmiotId)
    {
        $uczen = User::findOrFail($uczenId);
        $przedmiot = Przedmiot::findOrFail($przedmiotId);
        return view('nauczyciel.ocena_create', compact('uczen', 'przedmiot'));
    }

    public function edit(Ocena $ocena)
    {
        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Brak uprawnień.');
        }
        return view('nauczyciel.ocena_edit', compact('ocena'));
    }

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
        

        $oceny = Ocena::where('uczen_id', $uczenId)
                      ->where('przedmiot_id', $przedmiotId)
                      ->orderBy('created_at', 'desc') 
                      ->get();

 
        $klasaId = $uczen->klasaUcznia()->first()->id ?? 0;

        return view('nauczyciel.uczen_oceny', compact('uczen', 'przedmiot', 'oceny', 'klasaId'));
    }

    public function destroy(Ocena $ocena)
    {

        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Możesz usuwać tylko oceny wystawione przez siebie.');
        }

        $ocena->delete();

        return back()->with('success', 'Ocena została usunięta.');
    }
    public function myHistory()
    {
 
        $historia = OcenaHistoria::where('zmienil_user_id', Auth::id())
            ->with(['ocena.uczen', 'ocena.przedmiot']) 
            ->orderBy('data_zmiany', 'desc')
            ->get();

        return view('nauczyciel.historia_globalna', compact('historia'));
    }

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

        if ($ocena->nauczyciel_id !== Auth::id()) {
            abort(403, 'Możesz edytować tylko swoje oceny.');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($request, $ocena, $validated) {
           
            OcenaHistoria::create([
                'ocena_id' => $ocena->id,
                'stara_wartosc' => $ocena->wartosc, 
                'stara_opis' => $ocena->opis,
                'data_zmiany' => Carbon::now(),
                'powod_zmiany' => $validated['powod_zmiany'], 
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

    // --- UCZEŃ / RODZIC ---
    public function showGradesInClass(Klasa $klasa)
{
    $user = Auth::user();

    if (!$user->klasaUcznia->contains($klasa->id)) {
        abort(403, 'Nie jesteś przypisany do tej klasy.');
    }


    $oceny = Ocena::with(['przedmiot', 'nauczyciel'])
                ->where('uczen_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('przedmiot.nazwa');

    return view('uczen.oceny_klasa', compact('oceny', 'klasa'));
}

    public function showChildGrades(User $child)
    {
        $rodzic = Auth::user();

        if (!$rodzic->dzieci->contains($child->id)) {
            abort(403, 'To nie jest Twoje dziecko.');
        }

        $oceny = Ocena::with(['przedmiot', 'nauczyciel'])
            ->where('uczen_id', $child->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('przedmiot.nazwa');

        return view('rodzic.oceny_dziecka', compact('child', 'oceny'));
    }

    public function showGradeHistoryForParent(Ocena $ocena)
    {
        $rodzic = Auth::user();

        if (!$rodzic->dzieci->contains($ocena->uczen_id)) {
            abort(403, 'Brak uprawnień do przeglądania tej oceny.');
        }

        $historia = OcenaHistoria::where('ocena_id', $ocena->id)
            ->orderBy('data_zmiany', 'desc')
            ->with('zmienilUser') 
            ->get();

        return view('rodzic.historia_oceny', compact('ocena', 'historia'));
    }
}
