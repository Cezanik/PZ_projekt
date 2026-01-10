<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OcenyController;

// Przekierowanie ze strony głównej
Route::get('/', function () {
    return redirect()->route('login');
});

// --- GOŚCIE ---
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
    });
});

// --- ZALOGOWANI ---
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    // === ADMIN ===
    Route::controller(AdminController::class)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Użytkownicy
            Route::get('/users', 'indexUsers')->name('users.index');
            Route::get('/users/create', 'createUser')->name('user.create');
            Route::post('/users', 'storeUser')->name('user.store');
            Route::get('/users/{user}/edit', 'editUser')->name('user.edit');
            Route::put('/users/{user}', 'updateUser')->name('user.update');
            Route::delete('/users/{user}', 'destroyUser')->name('user.destroy');

            // Klasy
            Route::get('/klasy', 'indexKlasy')->name('klasy.index');
            Route::get('/klasy/create', 'createKlasa')->name('klasa.create');
            Route::post('/klasy', 'storeKlasa')->name('klasa.store');
            Route::get('/klasy/{klasa}/edit', 'editKlasa')->name('klasa.edit');
            Route::put('/klasy/{klasa}', 'updateKlasa')->name('klasa.update');
            Route::delete('/klasy/{klasa}', 'destroyKlasa')->name('klasa.destroy');

            // Przedmioty
            Route::get('/przedmioty', 'indexPrzedmioty')->name('przedmioty.index');
            Route::get('/przedmioty/create', 'createPrzedmiot')->name('przedmiot.create');
            Route::post('/przedmioty', 'storePrzedmiot')->name('przedmiot.store');
            Route::get('/przedmioty/{przedmiot}/edit', 'editPrzedmiot')->name('przedmiot.edit');
            Route::put('/przedmioty/{przedmiot}', 'updatePrzedmiot')->name('przedmiot.update');
            Route::delete('/przedmioty/{przedmiot}', 'destroyPrzedmiot')->name('przedmiot.destroy');

            // Przydziały (Admin zarządza przydziałami)
            Route::get('/przydzialy', 'indexPrzydzialy')->name('przydzialy.index');
            Route::get('/przydzialy/nauczyciel', 'createPrzydzial')->name('przydzial.nauczyciel');
            Route::post('/przydzialy/nauczyciel', 'storePrzydzial')->name('przydzial.store');
            Route::get('/przydzialy/nauczyciel/{przydzial}/edit', 'editPrzydzial')->name('przydzial.edit');
            Route::put('/przydzialy/nauczyciel/{przydzial}', 'updatePrzydzial')->name('przydzial.update');
            Route::delete('/przydzialy/nauczyciel/{przydzial}', 'destroyPrzydzial')->name('przydzial.destroy');

            // Przypisywanie (Uczniowie/Rodzice)
            Route::get('/przydzialy/lista-uczen-klasa', 'indexUczenKlasa')->name('uczen.klasa.index');
            Route::get('/przydzialy/uczen-klasa', 'createUczenKlasa')->name('uczen.klasa.create');
            Route::post('/uczen-klasa', 'storeUczenKlasa')->name('uczen.przypisz');
            Route::get('/przydzialy/uczen-klasa/{uczen}/{klasa}/edit', 'editUczenKlasa')->name('uczen.klasa.edit');
            Route::put('/przydzialy/uczen-klasa/{uczen}/{klasa}', 'updateUczenKlasa')->name('uczen.klasa.update');
            Route::delete('/przydzialy/uczen-klasa/{uczen}/{klasa}', 'destroyUczenKlasa')->name('uczen.klasa.destroy');
            
            Route::get('/przydzialy/lista-rodzic-uczen', 'indexRodzicUczen')->name('rodzic.uczen.index');
            Route::get('/przydzialy/rodzic-uczen', 'createRodzicUczen')->name('rodzic.uczen.create');
            Route::post('/rodzic-uczen', 'storeRodzicUczen')->name('rodzic.powiaz');
            Route::get('/przydzialy/rodzic-uczen/{rodzic}/{uczen}/edit', 'editRodzicUczen')->name('rodzic.uczen.edit');
            Route::put('/przydzialy/rodzic-uczen/{rodzic}/{uczen}', 'updateRodzicUczen')->name('rodzic.uczen.update');
            Route::delete('/przydzialy/rodzic-uczen/{rodzic}/{uczen}', 'destroyRodzicUczen')->name('rodzic.uczen.destroy');
        });

    // === MODUŁ NAUCZYCIELA (Tej sekcji brakowało!) ===
    // Musi być oddzielona od Admina, bo ma inny prefix i nazwę
    Route::middleware(['role:nauczyciel'])->group(function () {
        Route::controller(OcenyController::class)
            ->prefix('nauczyciel')
            ->name('nauczyciel.') 
            ->group(function () {
                Route::get('/przydzialy', 'indexPrzydzialy')->name('przydzialy.index');
                Route::get('/arkusz/{klasa}/{przedmiot}', 'showArkusz')->name('arkusz.show');
                Route::get('/ocena/dodaj/{uczen}/{przedmiot}', 'create')->name('ocena.create');
                Route::get('/ocena/edytuj/{ocena}', 'edit')->name('ocena.edit');
                Route::get('/ocena/historia/{ocena}', 'showHistoria')->name('ocena.historia');
                Route::get('/historia-aktywnosci', 'myHistory')->name('historia.index');
                Route::get('/oceny/uczen/{uczen}/{przedmiot}', 'showUczenOceny')->name('oceny.uczen.details');
            });
    });

    // === OBSŁUGA OCEN (Zapis/Edycja) ===
    Route::controller(OcenyController::class)->group(function () {
        Route::post('/ocena', 'store')->name('ocena.store');
        Route::put('/ocena/{ocena}', 'update')->name('ocena.update'); // Ujednoliciłem parametr na {ocena}
        Route::delete('/ocena/{ocena}', 'destroy')->name('ocena.destroy');
        Route::post('/ocena/revert/{historia_id}', 'revert')->name('ocena.revert');
        

        // Uczeń i Rodzic
        Route::get('/moje-oceny', 'myGrades')->name('oceny.uczen');
        Route::get('/oceny-dzieci', 'childrenGrades')->name('oceny.rodzic');
        Route::get('/moje-oceny/klasa/{klasa}', [OcenyController::class, 'showGradesInClass'])
        ->name('uczen.oceny.klasa');
    });
    // --- RODZIC ---
    // Grupa tras dla rodzica
    Route::middleware('role:rodzic')->prefix('rodzic')->name('rodzic.')->group(function () {
        // 1. Wyświetlanie ocen konkretnego dziecka
        Route::get('/oceny/{child}', [OcenyController::class, 'showChildGrades'])
            ->name('dziecko.oceny');

        // 2. Historia zmian konkretnej oceny (dla rodzica)
        Route::get('/ocena/historia/{ocena}', [OcenyController::class, 'showGradeHistoryForParent'])
            ->name('ocena.historia');
    });
});

