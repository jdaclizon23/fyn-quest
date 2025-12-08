<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403); // or redirect somewhere
        }
        return $next($request);
    }
}
