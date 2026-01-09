<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;

class LoginController extends Controller
{
    // 1. Pokaż formularz logowania
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Obsłuż przesłanie formularza (zaloguj)
    public function login(StoreLoginRequest $request)
    {
        // Dane są już zweryfikowane przez LoginRequest
        $credentials = $request->validated();

        // Próba logowania
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Jeśli błąd
        return back()->withErrors([
            'login' => 'Nieprawidłowe dane logowania.',
        ])->onlyInput('login');
    }

    public function registrationForm()
    {
        return view('auth.register');
    }

    public function register(StoreUserRequest $request)
    {
        
        $data = $request->validated();

        // Tworzymy użytkownika
        // Hashujemy hasło, ponieważ StoreUserRequest przekazuje je jako jawny tekst
        $user = User::create([
            'login'    => $data['login'],
            'password' => Hash::make($data['password']),
            'role'     => 'uczen',
            'imie'     => $data['imie'],
            'nazwisko' => $data['nazwisko'],
        ]);

        // Opcjonalnie: Automatyczne logowanie po rejestracji
        Auth::login($user);

        // Przekierowanie do dashboardu
        return redirect()->route('dashboard');
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
