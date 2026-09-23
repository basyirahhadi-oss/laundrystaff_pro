<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Simple role gate based on a `role` column on the users table
 * (e.g. 'admin' or 'staff'). Register in app/Http/Kernel.php:
 *
 *   protected $middlewareAliases = [
 *       ...
 *       'role' => \App\Http\Middleware\EnsureUserHasRole::class,
 *   ];
 *
 * Then use in routes as: ->middleware('role:admin')
 *
 * If you already use spatie/laravel-permission, skip this file
 * entirely and use its own `role:admin` middleware instead.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'You are not authorized to access this section.');
        }

        return $next($request);
    }
}
