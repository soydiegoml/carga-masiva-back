<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;

class AuthenticateAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Token recibido: ', ['token' => $request->bearerToken()]);
        if (!$request->bearerToken() || !Auth::guard('api')->check()) {
            return response()->json(['message' => 'Token inválido o sesión expirada'], 401)->header('Content-Type', 'application/json; charset=UTF-8');
        }
        return $next($request);
    }
}
