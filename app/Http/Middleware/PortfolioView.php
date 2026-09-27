<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
    if (!addon_is_activated('portfolio_system')) {
        return $next($request);
    }

    // The home controller already renders the portfolio homepage for guests
    // and unverified users; redirecting this route to itself causes a loop.
    if ($request->routeIs('home')) {
        return $next($request);
    }

    $user = auth()->user();

    // Guest users
    if (!$user) {
        return redirect()->route('home');
    }

    // User not verified
    if ($user->verification_status == 0) {
        return redirect()->route('home');
    }

    // Shop exists & not verified (avoid error)
    if ($user->shop && $user->shop->verification_status == 0) {
        return redirect()->route('home');
    }

    return $next($request);
    }
}
