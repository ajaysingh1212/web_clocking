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
            $remembered = $request->cookie('eemot_remember');

            if ($remembered) {
                try {
                    $payload = json_decode(decrypt($remembered), true);

                    if (! empty($payload['token'])) {
                        session(['api.auth' => $payload]);
                        session()->regenerate();

                        return $next($request);
                    }
                } catch (\Throwable $exception) {
                    // Ignore and fall back to login.
                }
            }

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Please sign in to continue.']);
        }

        return $next($request);
    }
}
