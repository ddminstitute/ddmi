@extends('layouts.banking')
@section('title','New Collection Plan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>New Collection Plan</h5>
    <a href="{{ route('collection-plans.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('collection-plans.store') }}">
@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label>Customer <span class="text-danger">*</span></label>
        <select name="customer_id" class="form-select" required>
            <option value="">Select Customer</option>
            @foreach($customers as $c)
            <option value="{{ $c->id }}" {{ old('customer_id')==$c->id?'selected':'' }}>{{ $c->name }} ({{ $c->customer_id }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label>Plan Name <span class="text-danger">*</span></label>
        <input type="text" name="plan_name" value="{{ old('plan_name') }}" class="form-control" required placeholder="e.g. Daily Saving Plan">
    </div>
    <div class="col-md-4">
        <label>Collection Type <span class="text-danger">*</span></label>
        <select name="collection_type" class="form-select" required>
            <option value="daily" {{ old('collection_type')=='daily'?'selected':'' }}>Daily</option>
            <option value="weekly" {{ old('collection_type')=='weekly'?'selected':'' }}>Weekly</option>
            <option value="monthly" {{ old('collection_type')=='monthly'?'selected':'' }}>Monthly</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Collection Amount (₹) <span class="text-danger">*</span></label>
        <input type="number" name="collection_amount" value="{{ old('collection_amount') }}" class="form-control" required min="1" step="0.01" placeholder="0.00">
    </div>
    <div class="col-md-4">
        <label>Total Installments</label>
        <input type="number" name="total_installments" value="{{ old('total_installments') }}" class="form-control" min="1" placeholder="e.g. 365">
    </div>
    <div class="col-md-4">
        <label>Start Date <span class="text-danger">*</span></label>
        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label>End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Maturity Amount (₹)</label>
        <input type="number" name="maturity_amount" value="{{ old('maturity_amount') }}" class="form-control" step="0.01" placeholder="Expected maturity amount">
    </div>
    <div class="col-12">
        <label>Notes</label>
        <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes...">{{ old('notes') }}</textarea>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Create Plan</button>
        <a href="{{ route('collection-plans.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
