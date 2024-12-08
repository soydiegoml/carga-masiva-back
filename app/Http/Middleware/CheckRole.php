<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Verifica que el usuario esté autenticado y tenga el rol correcto
        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request);
        }

        // Si el usuario no tiene el rol adecuado, redirigir o devolver error
        return redirect('login'); // O puedes devolver un mensaje de error
    }
}
