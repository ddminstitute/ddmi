<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireTransactionPin
{
    /**
     * Require transaction PIN for high-value transactions (>= ₹50,000).
     * PIN verification is cached in session for 10 minutes.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('post')) {
            return $next($request);
        }

        $user = auth()->user();
        if (! $user || ! $user->transaction_pin) {
            return $next($request); // No PIN set — skip enforcement
        }

        $amount = (float) $request->input('amount', 0);
        $threshold = 50000; // ₹50,000

        if ($amount < $threshold) {
            return $next($request);
        }

        $verifiedAt = session('pin_verified_at');
        if ($verifiedAt && now()->timestamp - $verifiedAt <= 600) {
            return $next($request);
        }

        return redirect()->route('pin.verify', ['redirect' => $request->fullUrl()])
            ->with('info', 'Transaction PIN required for amounts above ₹50,000.');
    }
}
