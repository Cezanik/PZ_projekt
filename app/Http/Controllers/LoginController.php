<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Pokaż formularz logowania
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Obsłuż przesłanie formularza (zaloguj)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        // Próba logowania
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Przekierowanie do metody dashboard(), która zdecyduje gdzie dalej
            return redirect()->intended('dashboard');
        }

        // Jeśli błąd
        return back()->withErrors([
            'login' => 'Nieprawidłowe dane logowania.',
        ])->onlyInput('login');
    }

    // 3. Wyloguj
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // 4. Panel startowy (Rozdzielnia ruchów)
    public function dashboard()
    {
        $user = Auth::user();

        // Sprawdzamy rolę i zwracamy odpowiedni widok
        return match ($user->role) {
            'admin'      => view('admin.dashboard'),
            'nauczyciel' => view('nauczyciel.dashboard'),
            'uczen'      => view('uczen.dashboard'),
            'rodzic'     => view('rodzic.dashboard'),
            default      => abort(403, 'Brak przypisanej roli.'),
        };
    }
}
