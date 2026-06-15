@extends('layouts.banking')
@section('title', 'My Profile')
@section('content')
<div class="row g-4">
    {{-- Update Profile Info --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-circle me-2"></i>Profile Information</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('patch')
                    <div class="mb-3">
                        <label class="form-label fw-500">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-500">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2 me-1"></i>Save Changes
                    </button>
                    @if(session('status') === 'profile-updated')
                        <span class="text-success ms-3 small"><i class="bi bi-check-circle me-1"></i>Saved!</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-lock me-2"></i>Change Password</div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('put')
                    <div class="mb-3">
                        <label class="form-label fw-500">Current Password</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">New Password</label>
                        <input type="password" name="password"
                            class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-500">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-shield-lock me-1"></i>Update Password
                    </button>
                    @if(session('status') === 'password-updated')
                        <span class="text-success ms-3 small"><i class="bi bi-check-circle me-1"></i>Updated!</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="col-12">
        <div class="card border-danger" style="border-width:1.5px">
            <div class="card-header text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone — Delete Account</div>
            <div class="card-body">
                <p class="text-muted small mb-3">Once your account is deleted, all data will be permanently removed. This action cannot be undone.</p>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-1"></i>Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Are you sure you want to delete your account? Enter your password to confirm.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
                    @csrf @method('delete')
                    <div class="mb-3">
                        <label class="form-label fw-500">Password</label>
                        <input type="password" name="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Enter your password">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="deleteForm" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>Yes, Delete Account
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
