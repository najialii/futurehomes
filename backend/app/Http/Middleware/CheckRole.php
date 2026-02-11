<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (!auth()->user()->hasAnyRole($roles)) {
            return response()->json([
                'message' => 'Forbidden. You do not have the required role to perform this action.',
                'required_roles' => $roles
            ], 403);
        }

        return $next($request);
    }
}