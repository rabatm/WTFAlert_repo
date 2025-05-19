<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $userRoles = json_decode($request->user()->inscriptions) ?? [];
        
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Non autorisé'], 403);
    }
}
