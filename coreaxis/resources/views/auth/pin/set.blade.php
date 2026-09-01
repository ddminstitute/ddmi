@extends('layouts.banking')
@section('title','Set Transaction PIN')
@section('content')
<div class="row justify-content-center"><div class="col-md-5">
<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-lock me-2 text-primary"></i>Set Transaction PIN</div>
    <div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <p class="text-muted small">Set a 4-digit PIN used to authorize high-value transactions and sensitive operations.</p>
    <form method="POST" action="{{ route('pin.set') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">New PIN (4 digits) <span class="text-danger">*</span></label>
            <input type="password" name="pin" class="form-control" maxlength="4" pattern="[0-9]{4}" inputmode="numeric" placeholder="••••" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm PIN <span class="text-danger">*</span></label>
            <input type="password" name="pin_confirmation" class="form-control" maxlength="4" pattern="[0-9]{4}" inputmode="numeric" placeholder="••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-lock-fill me-2"></i>Save PIN</button>
    </form>
    </div>
</div>
</div></div>
@endsection
