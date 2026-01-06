<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OcenyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Przekierowanie ze strony głównej
Route::get('/', function () {
    return redirect()->route('login');
});

// --- GOŚCIE (niezalogowani) ---
Route::middleware('guest')->group(function () {
    // PDF str. 5: Grupowanie tras dla LoginController
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
    });
});

// --- ZALOGOWANI ---
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    // === GRUPA ADMINA ===
    Route::controller(AdminController::class)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            
            // --- 1. UŻYTKOWNICY ---
            Route::get('/users', 'indexUsers')->name('users.index');
            Route::get('/users/create', 'createUser')->name('user.create');
            Route::post('/users', 'storeUser')->name('user.store');
            
            // Edycja i Usuwanie
            Route::get('/users/{user}/edit', 'editUser')->name('user.edit');
            Route::put('/users/{user}', 'updateUser')->name('user.update');
            Route::delete('/users/{user}', 'destroyUser')->name('user.destroy');

            // --- 2. KLASY ---
            Route::get('/klasy', 'indexKlasy')->name('klasy.index');
            Route::get('/klasy/create', 'createKlasa')->name('klasa.create');
            Route::post('/klasy', 'storeKlasa')->name('klasa.store');

            // Edycja i Usuwanie
            Route::get('/klasy/{klasa}/edit', 'editKlasa')->name('klasa.edit');
            Route::put('/klasy/{klasa}', 'updateKlasa')->name('klasa.update');
            Route::delete('/klasy/{klasa}', 'destroyKlasa')->name('klasa.destroy');

            // --- 3. PRZEDMIOTY ---
            Route::get('/przedmioty', 'indexPrzedmioty')->name('przedmioty.index');
            Route::get('/przedmioty/create', 'createPrzedmiot')->name('przedmiot.create');
            Route::post('/przedmioty', 'storePrzedmiot')->name('przedmiot.store');

            // Edycja i Usuwanie
            Route::get('/przedmioty/{przedmiot}/edit', 'editPrzedmiot')->name('przedmiot.edit');
            Route::put('/przedmioty/{przedmiot}', 'updatePrzedmiot')->name('przedmiot.update');
            Route::delete('/przedmioty/{przedmiot}', 'destroyPrzedmiot')->name('przedmiot.destroy');

        // 4. Przydziały (Nauczyciel)
        Route::get('/przydzialy', 'indexPrzydzialy')->name('przydzialy.index');
        Route::get('/przydzialy/nauczyciel', 'createPrzydzial')->name('przydzial.nauczyciel');
        Route::post('/przydzialy/nauczyciel', 'storePrzydzial')->name('przydzial.store');
        Route::get('/przydzialy/nauczyciel/{przydzial}/edit', 'editPrzydzial')->name('przydzial.edit');
    Route::put('/przydzialy/nauczyciel/{przydzial}', 'updatePrzydzial')->name('przydzial.update');
    Route::delete('/przydzialy/nauczyciel/{przydzial}', 'destroyPrzydzial')->name('przydzial.destroy');

        // 5. Przypisywanie (Uczniowie/Rodzice)
        // Uczeń -> Klasa
        Route::get('/przydzialy/lista-uczen-klasa', 'indexUczenKlasa')->name('uczen.klasa.index');
        Route::get('/przydzialy/uczen-klasa', 'createUczenKlasa')->name('uczen.klasa.create');
        Route::post('/uczen-klasa', 'storeUczenKlasa')->name('uczen.przypisz');
        // Edycja: zmiana klasy dla konkretnego ucznia
Route::get('/przydzialy/uczen-klasa/{uczen}/{klasa}/edit', 'editUczenKlasa')->name('uczen.klasa.edit');
Route::put('/przydzialy/uczen-klasa/{uczen}/{klasa}', 'updateUczenKlasa')->name('uczen.klasa.update');
// Usuwanie: odłączenie ucznia od klasy
Route::delete('/przydzialy/uczen-klasa/{uczen}/{klasa}', 'destroyUczenKlasa')->name('uczen.klasa.destroy');
        
        // Rodzic -> Uczeń
        Route::get('/przydzialy/lista-rodzic-uczen', 'indexRodzicUczen')->name('rodzic.uczen.index');
        Route::get('/przydzialy/rodzic-uczen', 'createRodzicUczen')->name('rodzic.uczen.create');
        Route::post('/rodzic-uczen', 'storeRodzicUczen')->name('rodzic.powiaz');
        Route::get('/przydzialy/rodzic-uczen/{rodzic}/{uczen}/edit', 'editRodzicUczen')->name('rodzic.uczen.edit');
Route::put('/przydzialy/rodzic-uczen/{rodzic}/{uczen}', 'updateRodzicUczen')->name('rodzic.uczen.update');
// Usuwanie: odłączenie rodzica od ucznia
Route::delete('/przydzialy/rodzic-uczen/{rodzic}/{uczen}', 'destroyRodzicUczen')->name('rodzic.uczen.destroy');
});
    
    // === OCENY (Nauczyciel / Uczeń / Rodzic) ===
    // Grupowanie OcenyController
    Route::controller(OcenyController::class)->group(function () {
        // Nauczyciel
        Route::post('/ocena', 'store')->name('ocena.store');
        Route::put('/ocena/{id}', 'update')->name('ocena.update');
        Route::post('/ocena/revert/{historia_id}', 'revert')->name('ocena.revert');

        // Uczeń i Rodzic
        Route::get('/moje-oceny', 'myGrades')->name('oceny.uczen');
        Route::get('/oceny-dzieci', 'childrenGrades')->name('oceny.rodzic');
    });
});
