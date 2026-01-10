<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\NauczycielPrzedmiotKlasa; 

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'login' => 'Nieprawidłowe dane logowania.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // === TO JEST KLUCZOWE DLA DASHBOARDU NAUCZYCIELA ===
    public function dashboard()
    {
        $user = Auth::user();

        // Jeśli nauczyciel -> pobierz jego przydziały i przekaż do widoku
        if ($user->role === 'nauczyciel') {
            $przydzialy = NauczycielPrzedmiotKlasa::with(['klasa', 'przedmiot'])
                ->where('nauczyciel_id', $user->id)
                ->get();

            // Przekazujemy zmienną 'przydzialy' do widoku
            return view('nauczyciel.dashboard', compact('przydzialy'));
        }
        if ($user->role === 'uczen')
    {
        $klasy = $user->klasaUcznia; 
        return view('uczen.dashboard', compact('klasy'));
    }
    if ($user->role === 'rodzic'){
        $dzieci = $user->dzieci; 
        return view('rodzic.dashboard', compact('dzieci'));
    }
    {
        $klasy = $user->klasaUcznia; 
        return view('uczen.dashboard', compact('klasy'));
    }
        return match ($user->role) {
            'admin'      => view('admin.dashboard'),
            // 'nauczyciel' obsłużony wyżej
            'uczen'      => view('uczen.dashboard'),
            'rodzic'     => view('rodzic.dashboard'),
            default      => abort(403, 'Brak przypisanej roli.'),
        };
    }
}
