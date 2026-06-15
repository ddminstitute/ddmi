@extends('layouts.banking')
@section('title','Open New Account')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('accounts.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Open New Bank Account</h5>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('accounts.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                    <option value="">Select Customer</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id')==$user->id?'selected':'' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Account Type <span class="text-danger">*</span></label>
                <select name="account_type" class="form-select @error('account_type') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    <option value="savings" {{ old('account_type')=='savings'?'selected':'' }}>Savings Account</option>
                    <option value="checking" {{ old('account_type')=='checking'?'selected':'' }}>Checking Account</option>
                    <option value="current" {{ old('account_type')=='current'?'selected':'' }}>Current Account</option>
                    <option value="fixed_deposit" {{ old('account_type')=='fixed_deposit'?'selected':'' }}>Fixed Deposit</option>
                </select>
                @error('account_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Initial Deposit ($) <span class="text-danger">*</span></label>
                    <input type="number" name="initial_deposit" class="form-control @error('initial_deposit') is-invalid @enderror" value="{{ old('initial_deposit',0) }}" min="0" step="0.01" required>
                    @error('initial_deposit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                    <select name="currency" class="form-select">
                        <option value="USD" {{ old('currency','USD')=='USD'?'selected':'' }}>USD — US Dollar</option>
                        <option value="EUR" {{ old('currency')=='EUR'?'selected':'' }}>EUR — Euro</option>
                        <option value="GBP" {{ old('currency')=='GBP'?'selected':'' }}>GBP — British Pound</option>
                        <option value="PKR" {{ old('currency')=='PKR'?'selected':'' }}>PKR — Pakistani Rupee</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes...">{{ old('notes') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Open Account</button>
                <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
