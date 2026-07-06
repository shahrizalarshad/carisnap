<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhotographerHasProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== UserRole::Photographer) {
            abort(403);
        }

        if ($user->profile && $request->routeIs('photographer.onboarding')) {
            return redirect('/photographer');
        }

        if (! $user->profile && ! $request->routeIs('photographer.onboarding')) {
            return redirect()->route('photographer.onboarding');
        }

        return $next($request);
    }
}
