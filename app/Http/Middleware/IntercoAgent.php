<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IntercoAgent
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
        $typeUser = $request->session()->get('type_user');

        if (!in_array($typeUser, [1, 2, 3])) {
            return redirect('/')->with('error', 'Accès interdit ! Veuillez vous connecter avec un compte autorisé.');
        }

        return $next($request);
    }
}
