<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerPortal
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error','Please log in to access the customer portal.');
        }
        if (auth()->user()->role !== 'customer') {
            abort(403, 'Access denied. This area is for customers only.');
        }
        return $next($request);
    }
}
