@extends('layouts.banking')
@section('title','Loan Restructuring')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('loans.show',$loan) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Restructure Loan — {{ $loan->loan_number }}</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card"><div class="card-header fw-semibold">Current Terms</div><div class="card-body">
<div class="row g-2 mb-3 text-center">
    <div class="col-4"><div class="border rounded p-2"><div class="small text-muted">Outstanding</div><div class="fw-bold text-primary">₹{{ number_format($loan->outstanding_amount,2) }}</div></div></div>
    <div class="col-4"><div class="border rounded p-2"><div class="small text-muted">Current EMI</div><div class="fw-bold">₹{{ number_format($loan->emi_amount,2) }}</div></div></div>
    <div class="col-4"><div class="border rounded p-2"><div class="small text-muted">Rate</div><div class="fw-bold">{{ $loan->interest_rate }}%</div></div></div>
</div>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('loans.do-restructure',$loan) }}">@csrf
<div class="mb-3"><label class="form-label">New Tenure (Months) <span class="text-danger">*</span></label><input type="number" name="new_tenure_months" class="form-control" min="1" max="360" id="newTenure" required></div>
<div class="mb-3"><label class="form-label">New Interest Rate (% p.a.) — leave blank to keep current</label><input type="number" name="new_interest_rate" class="form-control" step="0.01" min="0" max="30" id="newRate" placeholder="{{ $loan->interest_rate }}"></div>
<div class="alert alert-info small" id="rstPreview" style="display:none">New EMI: <strong id="newEmi">—</strong></div>
<div class="mb-3"><label class="form-label">Reason for Restructuring <span class="text-danger">*</span></label><textarea name="reason" class="form-control" rows="2" required placeholder="Financial hardship, COVID impact..."></textarea></div>
<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('loans.show',$loan) }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-warning">Restructure Loan</button>
</div>
</form>
</div></div>
</div></div>
@push('scripts')
<script>
const outstanding = {{ $loan->outstanding_amount }};
const currentRate = {{ $loan->interest_rate }};
function calcNewEmi(){
    const n = parseInt(document.getElementById('newTenure').value)||0;
    const r = (parseFloat(document.getElementById('newRate').value)||currentRate)/100/12;
    if(!n) return;
    const emi = r>0 ? outstanding*r*Math.pow(1+r,n)/(Math.pow(1+r,n)-1) : outstanding/n;
    document.getElementById('newEmi').textContent = '₹'+emi.toFixed(2);
    document.getElementById('rstPreview').style.display = 'block';
}
['newTenure','newRate'].forEach(id=>document.getElementById(id).addEventListener('input',calcNewEmi));
</script>
@endpush
@endsection
