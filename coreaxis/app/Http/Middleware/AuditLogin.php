<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AuditLogin
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('post') && str_contains($request->path(), 'login') && auth()->check()) {
            $user = auth()->user();
            $user->update(['last_login_at' => now(), 'login_attempts' => 0]);
            ActivityLog::record('login', 'User logged in: ' . $user->email);
        }

        return $response;
    }
}
