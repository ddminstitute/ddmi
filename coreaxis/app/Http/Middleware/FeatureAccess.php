<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FeatureAccess
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        if (!auth()->check()) return redirect()->route('login');
        if (!auth()->user()->hasFeature($feature)) {
            abort(403, 'Access denied. This feature is not enabled for your role.');
        }
        return $next($request);
    }
}
