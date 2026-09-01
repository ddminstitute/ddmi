<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class SessionTimeout
{
    protected int $timeoutMinutes = 30;

    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) return $next($request);

        $lastActivity = session('last_activity_at');
        if ($lastActivity && (time() - $lastActivity) > ($this->timeoutMinutes * 60)) {
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Session expired due to inactivity. Please login again.');
        }
        session(['last_activity_at' => time()]);
        return $next($request);
    }
}
