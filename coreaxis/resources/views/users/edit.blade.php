@extends('layouts.banking')
@section('title','Edit User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-primary"></i>Edit User — {{ $user->name }}</h5>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('users.update',$user) }}">
@csrf @method('PUT')
<div class="row g-3">
    <div class="col-md-6"><label>Full Name *</label><input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-control" required></div>
    <div class="col-md-6"><label>Email *</label><input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control" required></div>
    <div class="col-md-4"><label>Phone</label><input type="text" name="phone" value="{{ old('phone',$user->phone) }}" class="form-control"></div>
    <div class="col-md-4"><label>Role *</label>
        <select name="role" class="form-select" required>
            @foreach(['admin','manager','cashier','agent'] as $r)
            <option value="{{ $r }}" {{ old('role',$user->role ?? 'cashier')==$r?'selected':'' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label>Status</label>
        <select name="is_active" class="form-select">
            <option value="1" {{ ($user->is_active ?? true)?'selected':'' }}>Active</option>
            <option value="0" {{ !($user->is_active ?? true)?'selected':'' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-6"><label>New Password <small class="text-muted">(leave blank to keep current)</small></label><input type="password" name="password" class="form-control" placeholder="New password (optional)"></div>
    <div class="col-md-6"><label>Confirm Password</label><input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter new password"></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
