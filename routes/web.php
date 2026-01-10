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

// --- STRONA GŁÓWNA (Redirect) ---
Route::get('/', function () {
    return redirect()->route('login');
});

// --- GOŚCIE (Logowanie) ---
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
    });
});

// --- ZALOGOWANI UŻYTKOWNICY ---
Route::middleware('auth')->group(function () {

    // Wspólne dla zalogowanych
    Route::controller(LoginController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });

    // ====================================================
    // 1. ADMIN
    // ====================================================
    Route::middleware(['role:admin'])->group(function () {
        Route::controller(AdminController::class)
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                // Zarządzanie Użytkownikami
                Route::get('/users', 'indexUsers')->name('users.index');
                Route::get('/users/create', 'createUser')->name('user.create');
                Route::post('/users', 'storeUser')->name('user.store');
                Route::get('/users/{user}/edit', 'editUser')->name('user.edit');
                Route::put('/users/{user}', 'updateUser')->name('user.update');
                Route::delete('/users/{user}', 'destroyUser')->name('user.destroy');

                // Zarządzanie Klasami
                Route::get('/klasy', 'indexKlasy')->name('klasy.index');
                Route::get('/klasy/create', 'createKlasa')->name('klasa.create');
                Route::post('/klasy', 'storeKlasa')->name('klasa.store');
                Route::get('/klasy/{klasa}/edit', 'editKlasa')->name('klasa.edit');
                Route::put('/klasy/{klasa}', 'updateKlasa')->name('klasa.update');
                Route::delete('/klasy/{klasa}', 'destroyKlasa')->name('klasa.destroy');

                // Zarządzanie Przedmiotami
                Route::get('/przedmioty', 'indexPrzedmioty')->name('przedmioty.index');
                Route::get('/przedmioty/create', 'createPrzedmiot')->name('przedmiot.create');
                Route::post('/przedmioty', 'storePrzedmiot')->name('przedmiot.store');
                Route::get('/przedmioty/{przedmiot}/edit', 'editPrzedmiot')->name('przedmiot.edit');
                Route::put('/przedmioty/{przedmiot}', 'updatePrzedmiot')->name('przedmiot.update');
                Route::delete('/przedmioty/{przedmiot}', 'destroyPrzedmiot')->name('przedmiot.destroy');

                // Przydziały: Nauczyciel -> Przedmiot -> Klasa
                Route::get('/przydzialy', 'indexPrzydzialy')->name('przydzialy.index');
                Route::get('/przydzialy/nauczyciel', 'createPrzydzial')->name('przydzial.nauczyciel');
                Route::post('/przydzialy/nauczyciel', 'storePrzydzial')->name('przydzial.store');
                Route::get('/przydzialy/nauczyciel/{przydzial}/edit', 'editPrzydzial')->name('przydzial.edit');
                Route::put('/przydzialy/nauczyciel/{przydzial}', 'updatePrzydzial')->name('przydzial.update');
                Route::delete('/przydzialy/nauczyciel/{przydzial}', 'destroyPrzydzial')->name('przydzial.destroy');

                // Powiązania: Uczeń -> Klasa
                Route::get('/przydzialy/lista-uczen-klasa', 'indexUczenKlasa')->name('uczen.klasa.index');
                Route::get('/przydzialy/uczen-klasa', 'createUczenKlasa')->name('uczen.klasa.create');
                Route::post('/uczen-klasa', 'storeUczenKlasa')->name('uczen.przypisz');
                Route::get('/przydzialy/uczen-klasa/{uczen}/{klasa}/edit', 'editUczenKlasa')->name('uczen.klasa.edit');
                Route::put('/przydzialy/uczen-klasa/{uczen}/{klasa}', 'updateUczenKlasa')->name('uczen.klasa.update');
                Route::delete('/przydzialy/uczen-klasa/{uczen}/{klasa}', 'destroyUczenKlasa')->name('uczen.klasa.destroy');
                
                // Powiązania: Rodzic -> Uczeń
                Route::get('/przydzialy/lista-rodzic-uczen', 'indexRodzicUczen')->name('rodzic.uczen.index');
                Route::get('/przydzialy/rodzic-uczen', 'createRodzicUczen')->name('rodzic.uczen.create');
                Route::post('/rodzic-uczen', 'storeRodzicUczen')->name('rodzic.powiaz');
                Route::get('/przydzialy/rodzic-uczen/{rodzic}/{uczen}/edit', 'editRodzicUczen')->name('rodzic.uczen.edit');
                Route::put('/przydzialy/rodzic-uczen/{rodzic}/{uczen}', 'updateRodzicUczen')->name('rodzic.uczen.update');
                Route::delete('/przydzialy/rodzic-uczen/{rodzic}/{uczen}', 'destroyRodzicUczen')->name('rodzic.uczen.destroy');
            });
    });

    // ====================================================
    // 2. NAUCZYCIEL
    // ====================================================
    Route::middleware(['role:nauczyciel'])->group(function () {
        Route::controller(OcenyController::class)
            ->prefix('nauczyciel')
            ->name('nauczyciel.')
            ->group(function () {
                // Widoki
                Route::get('/przydzialy', 'indexPrzydzialy')->name('przydzialy.index');
                Route::get('/arkusz/{klasa}/{przedmiot}', 'showArkusz')->name('arkusz.show');
                Route::get('/oceny/uczen/{uczen}/{przedmiot}', 'showUczenOceny')->name('oceny.uczen.details');
                Route::get('/historia-aktywnosci', 'myHistory')->name('historia.index');

                // Formularze ocen
                Route::get('/ocena/dodaj/{uczen}/{przedmiot}', 'create')->name('ocena.create');
                Route::get('/ocena/edytuj/{ocena}', 'edit')->name('ocena.edit');
                Route::get('/ocena/historia/{ocena}', 'showHistoria')->name('ocena.historia');

                // Akcje (CRUD Ocen - przeniesione tutaj dla bezpieczeństwa)
                Route::post('/ocena', 'store')->name('ocena.store');
                Route::put('/ocena/{ocena}', 'update')->name('ocena.update');
                Route::delete('/ocena/{ocena}', 'destroy')->name('ocena.destroy');
                Route::post('/ocena/revert/{historia_id}', 'revert')->name('ocena.revert');
            });
    });

    // ====================================================
    // 3. RODZIC
    // ====================================================
    Route::middleware(['role:rodzic'])->group(function () {
        Route::controller(OcenyController::class)
            ->prefix('rodzic')
            ->name('rodzic.')
            ->group(function () {
                Route::get('/oceny/{child}', 'showChildGrades')->name('dziecko.oceny');
                Route::get('/ocena/historia/{ocena}', 'showGradeHistoryForParent')->name('ocena.historia');
            });
    });

    // ====================================================
    // 4. UCZEŃ
    // ====================================================
    Route::middleware(['role:uczen'])->group(function () {
        Route::controller(OcenyController::class)
            ->prefix('uczen')
            ->name('uczen.')
            ->group(function () {
                Route::get('/moje-oceny/klasa/{klasa}', 'showGradesInClass')->name('oceny.klasa');
            });
    });

});
