<x-guest-layout>
    <h4 class="fw-700 mb-1" style="font-weight:700;color:#0A1628">Set New Password</h4>
    <p class="text-muted mb-4" style="font-size:.875rem">Choose a strong password for your account.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email"
                value="{{ old('email', $request->email) }}"
                class="form-control @error('email') is-invalid @enderror"
                required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password">New Password</label>
            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                required autocomplete="new-password" placeholder="Min. 8 characters">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="form-control" required autocomplete="new-password" placeholder="Re-enter password">
        </div>

        <button type="submit" class="btn btn-auth w-100">
            <i class="bi bi-lock me-2"></i>Reset Password
        </button>
    </form>
</x-guest-layout>
