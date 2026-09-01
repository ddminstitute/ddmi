@extends('layouts.banking')
@section('title','Record Cheque')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('cheques.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Record Cheque</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('cheques.store') }}">@csrf
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Account <span class="text-danger">*</span></label><select name="account_id" class="form-select" required><option value="">— Select —</option>@foreach($accounts as $acc)<option value="{{ $acc->id }}">{{ $acc->account_number }} — {{ $acc->customer?->name??$acc->user?->name }}</option>@endforeach</select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Cheque Type <span class="text-danger">*</span></label><select name="cheque_type" class="form-select" required><option value="received">Received (deposit)</option><option value="issued">Issued (payment)</option></select></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Cheque Number <span class="text-danger">*</span></label><input type="text" name="cheque_number" class="form-control" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Amount (₹) <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-text">₹</span><input type="number" name="amount" class="form-control" step="0.01" min="1" required></div></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Cheque Date <span class="text-danger">*</span></label><input type="date" name="cheque_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Drawee Bank</label><input type="text" name="drawee_bank" class="form-control" placeholder="SBI, HDFC..."></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Drawer Name</label><input type="text" name="drawer_name" class="form-control"></div>
    <div class="col-md-6 mb-3"><label class="form-label">Deposit Date</label><input type="date" name="deposit_date" class="form-control"></div>
</div>
<div class="mb-3"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('cheques.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Record Cheque</button></div>
</form>
</div></div></div></div>
@endsection
