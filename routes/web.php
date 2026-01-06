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

// Strona główna przekierowuje do logowania
Route::get('/', function () {
    return redirect()->route('login');
});

// --- GOŚCIE (niezalogowani) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- ZALOGOWANI ---
Route::middleware('auth')->group(function () {
    
    // Wylogowanie
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard (LoginController decyduje który widok pokazać)
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    // Trasy ADMINA (można dodać middleware sprawdzający rolę)
    Route::post('/admin/user', [AdminController::class, 'createUser']);
    Route::post('/admin/klasa', [AdminController::class, 'createKlasa']);
    Route::post('/admin/przydzial', [AdminController::class, 'assignTeacherToSubjectInClass']);
    // Wewnątrz grupy middleware auth / admin:
Route::post('/admin/przedmiot', [AdminController::class, 'createPrzedmiot']);

// Dla powiązania rodzic-uczeń (dodaj metodę w AdminController jeśli jej nie ma, lub użyj istniejącej)
Route::post('/admin/rodzic-uczen', [AdminController::class, 'linkParentToStudent']); 

// Dla przypisania ucznia do klasy (metoda assignStudentsToKlasa przyjmuje 'klasa' w URL, trzeba to dostosować)
// Uproszczona wersja trasy dla formularza powyżej:
Route::post('/admin/przypisz-ucznia', function(\Illuminate\Http\Request $request) {
    $klasa = \App\Models\Klasa::findOrFail($request->klasa_id);
    $controller = new \App\Http\Controllers\AdminController;
    return $controller->assignStudentsToKlasa($request, $klasa);
});
    
    // Trasy NAUCZYCIELA
    Route::post('/ocena', [OcenyController::class, 'store']);
    Route::put('/ocena/{id}', [OcenyController::class, 'update']);
    Route::post('/ocena/revert/{historia_id}', [OcenyController::class, 'revert']);

    // Trasy UCZNIA
    Route::get('/moje-oceny', [OcenyController::class, 'myGrades']);

    // Trasy RODZICA
    Route::get('/oceny-dzieci', [OcenyController::class, 'childrenGrades']);
});

