<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserPackage;

class CanScan
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $userPackage = UserPackage::where('user_id', $user->id)->first();

        if (!$userPackage || $userPackage->remaining_scans < 1) {
            return response()->json(['message' => 'No remaining scans available.'], 403);
        }

        return $next($request);
    }
}
