<?php

namespace App\Http\Middleware;

use App\Models\Otp;
use Closure;
use Illuminate\Http\Request;

class RequireOtp
{
    /**
     * Enforce OTP on high-value transactions (>= ₹1,00,000).
     * OTP must have been verified in the last 5 minutes.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('post')) {
            return $next($request);
        }

        $amount = (float) $request->input('amount', 0);
        $threshold = 100000; // ₹1,00,000

        if ($amount < $threshold) {
            return $next($request);
        }

        $otp = $request->input('otp');
        $user = auth()->user();

        if (! $otp) {
            // Send OTP and ask user to enter it
            $phone = $user?->phone ?? optional($user)->customer?->phone;
            if ($phone) {
                $generated = Otp::generate($phone);
                // SMS sent via NotificationService if available
                try {
                    app(\App\Services\NotificationService::class)->sendSms(
                        $phone,
                        "Your CoreAxis OTP for high-value transaction: {$generated->otp}. Valid for 5 minutes."
                    );
                } catch (\Throwable $e) {
                    // log silently
                }
            }
            return back()->with('otp_required', true)
                ->with('info', 'An OTP has been sent to your registered mobile. Enter it below to authorize this transaction.');
        }

        // Verify OTP
        $phone = $user?->phone ?? optional($user)->customer?->phone;
        if (! $phone || ! Otp::verify($phone, $otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput();
        }

        return $next($request);
    }
}
