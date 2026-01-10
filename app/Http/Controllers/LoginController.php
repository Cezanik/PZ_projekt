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

   public function dashboard()
    {
        $user = Auth::user();

        // 1. ADMIN
        if ($user->role === 'admin') {
            return view('admin.dashboard');
        }

        // 2. NAUCZYCIEL
        if ($user->role === 'nauczyciel') {
            $przydzialy = NauczycielPrzedmiotKlasa::with(['klasa', 'przedmiot'])
                ->where('nauczyciel_id', $user->id)
                ->get();

            return view('nauczyciel.dashboard', compact('przydzialy'));
        }

        // 3. UCZEŃ
        if ($user->role === 'uczen') {
            $klasy = $user->klasaUcznia; 
            return view('uczen.dashboard', compact('klasy'));
        }

        // 4. RODZIC
        if ($user->role === 'rodzic') {
            $dzieci = $user->dzieci; 
            return view('rodzic.dashboard', compact('dzieci'));
        }


        abort(403, 'Brak przypisanej roli.');
    }
}
