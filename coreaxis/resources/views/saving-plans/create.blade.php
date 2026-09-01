@extends('layouts.banking')
@section('title','New Saving Plan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-piggy-bank me-2 text-primary"></i>New Saving Plan</h5>
    <a href="{{ route('saving-plans.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('saving-plans.store') }}">
@csrf
<div class="row g-3">
    <div class="col-md-6"><label>Plan Name *</label><input type="text" name="plan_name" value="{{ old('plan_name') }}" class="form-control" required placeholder="e.g. Daily Bachat Yojana"></div>
    <div class="col-md-3"><label>Plan Type *</label>
        <select name="plan_type" class="form-select" required>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly" selected>Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div>
    <div class="col-md-3"><label>Minimum Amount (₹) *</label><input type="number" name="minimum_amount" value="{{ old('minimum_amount',0) }}" class="form-control" required step="0.01" min="0"></div>
    <div class="col-md-3"><label>Interest Rate (% p.a.) *</label><input type="number" name="interest_rate" value="{{ old('interest_rate',0) }}" class="form-control" required step="0.01" min="0" max="100"></div>
    <div class="col-md-3"><label>Tenure (Months)</label><input type="number" name="tenure_months" value="{{ old('tenure_months') }}" class="form-control" min="1" placeholder="Leave blank for open"></div>
    <div class="col-12"><label>Description</label><textarea name="description" class="form-control" rows="3" placeholder="Describe this saving plan...">{{ old('description') }}</textarea></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Create Plan</button>
        <a href="{{ route('saving-plans.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
