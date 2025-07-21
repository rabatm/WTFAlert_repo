<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
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
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur a le rôle admin ou super_admin
        if (!Auth::user()->hasAnyRole(['super_admin', 'admin_collectivite'])) {
            // Rediriger vers la page d'accueil avec un message d'erreur
            return redirect('/')->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}
