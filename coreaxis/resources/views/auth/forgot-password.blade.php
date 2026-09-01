<x-guest-layout>
    <h4 class="fw-700 mb-1" style="font-weight:700;color:#0A1628">Reset Password</h4>
    <p class="text-muted mb-4" style="font-size:.875rem">Enter your email to receive a password reset link.</p>

    @if (session('status'))
        <div class="alert alert-success mb-3" style="border-radius:10px;font-size:.85rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required autofocus placeholder="you@example.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-auth w-100">
            <i class="bi bi-envelope me-2"></i>Send Reset Link
        </button>
        <p class="text-center mt-3 mb-0" style="font-size:.85rem;color:#6b7280">
            <a href="{{ route('login') }}">Back to login</a>
        </p>
    </form>
</x-guest-layout>
