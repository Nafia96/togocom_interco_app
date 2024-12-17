<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NotConnected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->session()->get('id') == null)
         {
            return redirect('/')->with('error', 'Vous avez été déconnecté! Veuillez vous connecter à nouveau.');
        }
        return $next($request);
    }
}
