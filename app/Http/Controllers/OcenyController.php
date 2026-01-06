<?php

namespace App\Http\Controllers;

use App\Models\Ocena;
use App\Models\OcenaHistoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OcenyController extends Controller
{
    // --- DLA NAUCZYCIELA ---

    // Wystawienie nowej oceny
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

        return back()->with('success', 'Ocena wystawiona.');
    }

    // Edycja oceny (z zapisem do historii)
    public function update(Request $request, $id)
    {
        $ocena = Ocena::findOrFail($id);

        $nowaWartosc = $request->input('wartosc');
        $powod = $request->input('powod'); // Nauczyciel musi podać powód zmiany

        // 1. Zapisujemy starą wersję do historii
        OcenaHistoria::create([
            'ocena_id' => $ocena->id,
            'poprzednia_wartosc' => $ocena->wartosc,
            'nowa_wartosc' => $nowaWartosc,
            'data_zmiany' => Carbon::now(),
            'powod' => $powod
        ]);

        // 2. Aktualizujemy ocenę
        $ocena->wartosc = $nowaWartosc;
        $ocena->save();

        return back()->with('success', 'Ocena zaktualizowana.');
    }

    // Cofnięcie zmiany (Przywracanie poprzedniej wartości)
    public function revert($historia_id)
    {
        $historia = OcenaHistoria::findOrFail($historia_id);
        $ocena = $historia->ocena;

        // Przywracamy wartość z pola 'poprzednia_wartosc' rekordu historii
        $ocena->wartosc = $historia->poprzednia_wartosc;
        $ocena->save();

        // Usuwamy wpis z historii (lub oznaczamy jako cofnięty, zależy od wymagań)
        $historia->delete();

        return back()->with('success', 'Przywrócono poprzednią ocenę.');
    }

    // --- DLA UCZNIA ---

    public function myGrades()
    {
        $user = Auth::user();
        
        // Pobierz oceny pogrupowane po przedmiotach
        $oceny = Ocena::with('przedmiot', 'nauczyciel')
                      ->where('uczen_id', $user->id)
                      ->get()
                      ->groupBy('przedmiot.nazwa');

        return view('uczen.oceny', ['oceny' => $oceny]);
    }

    // --- DLA RODZICA ---

    public function childrenGrades()
    {
        $rodzic = Auth::user();
        
        // Pobieramy dzieci z ich ocenami
        $dzieci = $rodzic->dzieci()->with(['ocenyOtrzymane.przedmiot', 'ocenyOtrzymane.nauczyciel'])->get();

        return view('rodzic.dzieci', ['dzieci' => $dzieci]);
    }
}
