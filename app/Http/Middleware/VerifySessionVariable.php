<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifySessionVariable
{
    public function handle($request, Closure $next)
    {
        
        if (!session()->has('get_prop')) {
            return redirect()->route('polizas')->with('error', 'La variable de sesión no existe.');
        }

        return $next($request);
    }
}
