<x-guest-layout>
    <h4 class="fw-700 mb-1" style="font-weight:700;color:#0A1628">Verify Email</h4>
    <p class="text-muted mb-4" style="font-size:.875rem">
        Thanks for signing up! Please verify your email address by clicking the link we just emailed you.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-3" style="border-radius:10px;font-size:.85rem;">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-auth w-100 mb-3">
            <i class="bi bi-envelope me-2"></i>Resend Verification Email
        </button>
    </form>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100" style="border-radius:10px;">
            <i class="bi bi-box-arrow-right me-2"></i>Log Out
        </button>
    </form>
</x-guest-layout>
