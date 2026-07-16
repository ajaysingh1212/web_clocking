<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! token()) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Please sign in to continue.']);
        }

        return $next($request);
    }
}
