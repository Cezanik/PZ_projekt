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
    // Zastosowanie Route::controller (PDF str. 11 )
    Route::controller(AdminController::class)
        ->prefix('admin') // Dodajemy prefix URL (np. /admin/user)
        ->name('admin.')  // Dodajemy prefix nazw tras (PDF str. 10 )
        ->group(function () {
            
            // 1. Użytkownicy
            Route::get('/user', 'showUserForm')->name('user.create'); // Formularz
            Route::post('/user', 'createUser')->name('user.store');   // Akcja

            // 2. Klasy
            Route::get('/klasa', 'showKlasaForm')->name('klasa.create');
            Route::post('/klasa', 'createKlasa')->name('klasa.store');

            // 3. Przedmioty
            Route::get('/przedmiot', 'showPrzedmiotForm')->name('przedmiot.create');
            Route::post('/przedmiot', 'createPrzedmiot')->name('przedmiot.store');

            // 4. Przydziały (Nauczyciel)
            Route::get('/przydzial', 'showPrzydzialNauczycielaForm')->name('przydzial.nauczyciel');
            Route::post('/przydzial', 'assignTeacherToSubjectInClass')->name('przydzial.store');

            // 5. Przypisywanie uczniów
            Route::get('/uczen-klasa', 'showPrzydzialUczniaForm')->name('uczen.przydzial');
            Route::post('/uczen-klasa', 'assignStudentsToKlasaCustom')->name('uczen.przypisz');
            
            // 6. Rodzice
            Route::post('/rodzic-uczen', 'linkParentToStudent')->name('rodzic.powiaz');
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
