@extends('layouts.banking')
@section('title','Saving Plan Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-piggy-bank me-2 text-primary"></i>{{ $savingPlan->plan_name }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('saving-plans.edit',$savingPlan) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('saving-plans.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="card"><div class="card-body">
    <div class="row g-3">
        <div class="col-md-3"><div class="text-muted small">Plan Code</div><div class="fw-bold">{{ $savingPlan->plan_code }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Plan Type</div><span class="badge bg-{{ $savingPlan->getTypeBadge() }} px-3">{{ ucfirst($savingPlan->plan_type) }}</span></div>
        <div class="col-md-3"><div class="text-muted small">Minimum Amount</div><div class="fw-bold text-primary">₹{{ number_format($savingPlan->minimum_amount,2) }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Interest Rate</div><div class="fw-bold text-success">{{ $savingPlan->interest_rate }}% p.a.</div></div>
        <div class="col-md-3"><div class="text-muted small">Tenure</div><div class="fw-semibold">{{ $savingPlan->tenure_months ? $savingPlan->tenure_months.' months' : 'Open-ended' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Status</div><span class="badge bg-{{ $savingPlan->is_active?'success':'secondary' }}">{{ $savingPlan->is_active?'Active':'Inactive' }}</span></div>
        @if($savingPlan->description)
        <div class="col-12"><div class="text-muted small mb-1">Description</div><p class="mb-0">{{ $savingPlan->description }}</p></div>
        @endif
    </div>
</div></div>
@endsection
