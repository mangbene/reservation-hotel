<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter.');
        }

        if ($request->user()->role !== $role) {
            abort(403, 'Accès non autorisé à cette section.');
        }

        return $next($request);
    }
}