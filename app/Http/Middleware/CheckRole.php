<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Acesso negado. Você não tem permissão para acessar esta página.');
    }
}
