<?php

namespace App\Http\Requests\Auth;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Check if account is locked out via locked_until column
        $user = \App\Models\User::where('email', $this->email)->first();
        if ($user && $user->locked_until && now()->lt($user->locked_until)) {
            $minutes = now()->diffInMinutes($user->locked_until, false);
            throw ValidationException::withMessages([
                'email' => "Account locked. Try again in {$minutes} minute(s).",
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // Increment failed_login_attempts and lock after 5 failures
            if ($user) {
                $attempts = ($user->failed_login_attempts ?? 0) + 1;
                $updates = ['failed_login_attempts' => $attempts, 'last_failed_login_at' => now()];
                if ($attempts >= 5) {
                    $updates['locked_until'] = now()->addMinutes(30);
                }
                $user->update($updates);
            }

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Reset on successful login
        if ($user) {
            $user->update(['failed_login_attempts' => 0, 'locked_until' => null, 'last_login_at' => now(), 'last_login_ip' => $this->ip()]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
