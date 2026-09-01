<x-guest-layout>
    <h4 class="fw-700 mb-1" style="font-weight:700;color:#0A1628">Confirm Password</h4>
    <p class="text-muted mb-4" style="font-size:.875rem">This is a secure area. Please confirm your password before continuing.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-4">
            <label for="password">Password</label>
            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                required autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-auth w-100">
            <i class="bi bi-shield-lock me-2"></i>Confirm
        </button>
    </form>
</x-guest-layout>
