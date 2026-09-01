@extends('layouts.banking')
@section('title','Loan Collaterals')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('loans.show',$loan) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Collaterals / Security — {{ $loan->loan_number }}</h5>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="row">
<div class="col-lg-6">
<div class="card mb-3"><div class="card-header fw-semibold">Add Collateral</div><div class="card-body">
<form method="POST" action="{{ route('loans.add-collateral',$loan) }}">@csrf
<div class="mb-2"><label class="form-label form-label-sm">Type *</label><select name="collateral_type" class="form-select form-select-sm" required>@foreach(['gold','property','vehicle','fd','other'] as $t)<option value="{{ $t }}">{{ ucfirst($t) }}</option>@endforeach</select></div>
<div class="mb-2"><label class="form-label form-label-sm">Description *</label><input type="text" name="description" class="form-control form-control-sm" required></div>
<div class="mb-2"><label class="form-label form-label-sm">Estimated Value (₹) *</label><input type="number" name="estimated_value" class="form-control form-control-sm" step="0.01" required></div>
<div class="mb-2"><label class="form-label form-label-sm">Valuation Date</label><input type="date" name="valuation_date" class="form-control form-control-sm"></div>
<div class="mb-3"><label class="form-label form-label-sm">Charge Created Date</label><input type="date" name="charge_created_date" class="form-control form-control-sm"></div>
<button type="submit" class="btn btn-primary btn-sm">Add Collateral</button>
</form>
</div></div>
</div>
<div class="col-lg-6">
<div class="card"><div class="card-header fw-semibold">Existing Collaterals</div><div class="card-body p-0">
@forelse($collaterals as $c)
<div class="p-3 border-bottom">
    <div class="d-flex justify-content-between">
        <div class="fw-semibold">{{ ucfirst($c->collateral_type) }} — {{ $c->description }}</div>
        <span class="badge bg-{{ $c->status==='active'?'success':'secondary' }}">{{ ucfirst($c->status) }}</span>
    </div>
    <div class="small text-muted mt-1">Value: ₹{{ number_format($c->estimated_value,2) }}
        @if($c->valuation_date) · Valued: {{ $c->valuation_date->format('d M Y') }}@endif
    </div>
</div>
@empty
<div class="text-center text-muted py-4">No collaterals recorded</div>
@endforelse
</div></div>
</div>
</div>
@endsection
