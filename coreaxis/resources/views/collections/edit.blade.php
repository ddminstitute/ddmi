@extends('layouts.banking')
@section('title','Edit Collection Plan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-primary"></i>Edit Plan — {{ $collectionPlan->plan_number }}</h5>
    <a href="{{ route('collection-plans.show',$collectionPlan) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('collection-plans.update',$collectionPlan) }}">
@csrf @method('PUT')
<div class="row g-3">
    <div class="col-md-6"><label>Plan Name *</label><input type="text" name="plan_name" value="{{ old('plan_name',$collectionPlan->plan_name) }}" class="form-control" required></div>
    <div class="col-md-3"><label>Collection Amount (₹) *</label><input type="number" name="collection_amount" value="{{ old('collection_amount',$collectionPlan->collection_amount) }}" class="form-control" required step="0.01"></div>
    <div class="col-md-3"><label>Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ old('status',$collectionPlan->status)=='active'?'selected':'' }}>Active</option>
            <option value="completed" {{ old('status',$collectionPlan->status)=='completed'?'selected':'' }}>Completed</option>
            <option value="closed" {{ old('status',$collectionPlan->status)=='closed'?'selected':'' }}>Closed</option>
        </select>
    </div>
    <div class="col-md-3"><label>Total Installments</label><input type="number" name="total_installments" value="{{ old('total_installments',$collectionPlan->total_installments) }}" class="form-control" min="1"></div>
    <div class="col-md-3"><label>End Date</label><input type="date" name="end_date" value="{{ old('end_date',$collectionPlan->end_date?->format('Y-m-d')) }}" class="form-control"></div>
    <div class="col-md-3"><label>Maturity Amount (₹)</label><input type="number" name="maturity_amount" value="{{ old('maturity_amount',$collectionPlan->maturity_amount) }}" class="form-control" step="0.01"></div>
    <div class="col-12"><label>Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$collectionPlan->notes) }}</textarea></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Update Plan</button>
        <a href="{{ route('collection-plans.show',$collectionPlan) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
