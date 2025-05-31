<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMineProfile
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeUserId = $request->route('id');
        if ((string)$user->id !== (string)$routeUserId) {
            return response()->json(['message' => 'Forbidden. You can only update your own profile.'], 403);
        }

        return $next($request);
    }
}
