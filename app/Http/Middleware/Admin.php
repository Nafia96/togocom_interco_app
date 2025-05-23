<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
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
        if ($request->session()->get('is_admin') != 3) {
            return redirect('/')->with('error', 'Accès intedit! Veuillez vous connecter.');
        }
        return $next($request);
    }
}
