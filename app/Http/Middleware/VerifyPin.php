<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!session('pin_verified')) {
            session(['pin_redirect' => $request->url()]);
            return redirect()->route('pin.verify')
                ->with('warning', 'Veuillez saisir votre code PIN pour continuer.');
        }

        return $next($request);
    }
}
