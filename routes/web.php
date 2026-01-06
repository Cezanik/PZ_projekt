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
            
            // 1. Użytkownicy
            Route::get('/users', 'indexUsers')->name('users.index');
            Route::get('/users/create', 'createUser')->name('user.create'); // Formularz
            Route::post('/users', 'storeUser')->name('user.store');         // Zapis

            // 2. Klasy
            Route::get('/klasy', 'indexKlasy')->name('klasy.index');
            Route::get('/klasy/create', 'createKlasa')->name('klasa.create');
            Route::post('/klasy', 'storeKlasa')->name('klasa.store');

            // 3. Przedmioty
            Route::get('/przedmioty', 'indexPrzedmioty')->name('przedmioty.index');
            Route::get('/przedmioty/create', 'createPrzedmiot')->name('przedmiot.create');
            Route::post('/przedmioty', 'storePrzedmiot')->name('przedmiot.store');

            // 4. Przydziały (Nauczyciel -> Przedmiot)
            Route::get('/przydzialy', 'indexPrzydzialy')->name('przydzialy.index');
            Route::get('/przydzialy/nauczyciel', 'createPrzydzial')->name('przydzial.nauczyciel');
            Route::post('/przydzialy/nauczyciel', 'storePrzydzial')->name('przydzial.store');

            // 5. Uczniowie i Rodzice
            // Uczeń -> Klasa
            Route::get('/przydzialy/uczen-klasa', 'createUczenKlasa')->name('uczen.klasa.create');
            Route::post('/uczen-klasa', 'storeUczenKlasa')->name('uczen.przypisz');
            
            // Rodzic -> Uczeń
            Route::get('/przydzialy/rodzic-uczen', 'createRodzicUczen')->name('rodzic.uczen.create');
            Route::post('/rodzic-uczen', 'storeRodzicUczen')->name('rodzic.powiaz');
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
