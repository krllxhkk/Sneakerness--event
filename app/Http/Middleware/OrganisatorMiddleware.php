<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganisatorMiddleware
{
    /**
     * Controleert of de ingelogde gebruiker een organisator is.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->rol !== 'organisator') {
            return redirect()->route('home');
        }

        return $next($request);
    }
}