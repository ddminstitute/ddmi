@extends('layouts.portal')
@section('title','My Profile')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-person me-2 text-primary"></i>My Profile</h5>
<div class="row g-3">
    <div class="col-md-6"><div class="card"><div class="card-header">Account Information</div><div class="card-body">
        <table class="table table-borderless mb-0">
            <tr><td class="text-muted">Name</td><td class="fw-semibold">{{ auth()->user()->name }}</td></tr>
            <tr><td class="text-muted">Email</td><td>{{ auth()->user()->email ?? '—' }}</td></tr>
            <tr><td class="text-muted">Phone</td><td>{{ auth()->user()->phone ?? '—' }}</td></tr>
            <tr><td class="text-muted">Role</td><td><span class="badge bg-primary">Customer</span></td></tr>
        </table>
    </div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-header">Change Password</div><div class="card-body">
        <form method="POST" action="{{ route('portal.profile.password') }}">@csrf
            <div class="mb-3"><label class="form-label">Current Password <span class="text-danger">*</span></label><input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>@error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label">New Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control" required minlength="8"></div>
            <div class="mb-3"><label class="form-label">Confirm New Password <span class="text-danger">*</span></label><input type="password" name="password_confirmation" class="form-control" required></div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div></div></div>
</div>
@endsection
