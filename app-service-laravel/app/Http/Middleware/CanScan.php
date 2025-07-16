<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserPackage;
use Carbon\Carbon;

class CanScan
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $userPackage = UserPackage::where('user_id', $user->id)->first();

        if (!$userPackage || $userPackage->active == 0) {
            return response()->json(['message' => 'Nažalost, pretplata nije aktivna'], 405);
        }

        if ($userPackage->expiration_date) {
            $expirationDate = Carbon::parse($userPackage->expiration_date)->startOfDay();
            $yesterday = now()->subDay()->startOfDay();
            if ($expirationDate->eq($yesterday)) {
                return response()->json(['message' => 'Nažalost, pretplata je istekla'], 403);
            }
        }

        if ($userPackage->remaining_scans < 1) {
            return response()->json(['message' => 'Nažalost, nema dostupnih AI tokena'], 403);
        }

        return $next($request);
    }
}
