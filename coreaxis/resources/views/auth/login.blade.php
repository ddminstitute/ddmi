<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-success mb-3" style="border-radius:10px;font-size:.85rem;">
            {{ session('status') }}
        </div>
    @endif

    <h4 class="fw-700 mb-1" style="font-weight:700;color:#0A1628">Welcome back</h4>
    <p class="text-muted mb-4" style="font-size:.875rem">Sign in to your CoreAxis account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required autofocus autocomplete="username" placeholder="admin@coreaxis.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password">Password</label>
            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                required autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:.85rem">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-auth w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>

        <p class="text-center mt-3 mb-0" style="font-size:.85rem;color:#6b7280">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </p>
    </form>
</x-guest-layout>
