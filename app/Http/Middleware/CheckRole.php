<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // 1. czy użytkownik jest zalogowany
        if (!Auth::check()) {
            return redirect('login');
        }
        // 2. czy użytkownik ma odpowiednią rolę      
        if (Auth::user()->role !== $role) {
            abort(403, 'Brak uprawnień do tej sekcji.');
        }

        return $next($request);
    }
}
