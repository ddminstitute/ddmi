@extends('layouts.banking')
@section('title','Add User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Add New User</h5>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('users.store') }}">
@csrf
<div class="row g-3">
    <div class="col-md-6"><label>Full Name *</label><input type="text" name="name" value="{{ old('name') }}" class="form-control" required placeholder="User's full name"></div>
    <div class="col-md-6"><label>Email Address *</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="user@example.com"></div>
    <div class="col-md-4"><label>Phone</label><input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+91 XXXXXXXXXX"></div>
    <div class="col-md-4"><label>Role *</label>
        <select name="role" class="form-select" required>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="cashier" selected>Cashier</option>
            <option value="agent">Agent</option>
        </select>
    </div>
    <div class="col-md-4"><label>Password *</label><input type="password" name="password" class="form-control" required placeholder="Min. 8 characters"></div>
    <div class="col-md-4 offset-md-4"><label>Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required placeholder="Re-enter password"></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Create User</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
