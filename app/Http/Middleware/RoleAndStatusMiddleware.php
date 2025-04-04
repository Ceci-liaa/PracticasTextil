<?php

namespace App\Http\Middleware;

use Closure;

class RoleAndStatusMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user || !$user->status || !$user->hasAnyRole($roles)) {
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}


