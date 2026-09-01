@extends('layouts.banking')
@section('title','Verify Transaction PIN')
@section('content')
<div class="row justify-content-center"><div class="col-md-4">
<div class="card border-primary">
    <div class="card-header fw-semibold bg-primary text-white"><i class="bi bi-shield-lock me-2"></i>Transaction PIN Required</div>
    <div class="card-body">
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first('pin') }}</div>@endif
    <p class="text-muted small">Enter your 4-digit transaction PIN to continue.</p>
    <form method="POST" action="{{ route('pin.verify') }}">
        @csrf
        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        <div class="mb-3">
            <label class="form-label">Transaction PIN</label>
            <input type="password" name="pin" class="form-control form-control-lg text-center" maxlength="4" pattern="[0-9]{4}" inputmode="numeric" placeholder="••••" autofocus required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Verify PIN</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
    </form>
    </div>
</div>
<p class="text-center mt-2 small text-muted">Forgot your PIN? <a href="{{ route('pin.set') }}">Set a new PIN</a></p>
</div></div>
@endsection
