@extends('layouts.banking')
@section('title','Apply for Loan')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Loan Application</h5>
</div>
<div class="row g-3">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-file-earmark-text me-2"></i>Application Details</div>
    <div class="card-body">
        <form method="POST" action="{{ route('loans.store') }}" id="loanForm">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Linked Account <span class="text-danger">*</span></label>
                <select name="account_id" class="form-select @error('account_id') is-invalid @enderror" required>
                    <option value="">Select account...</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ old('account_id')==$acc->id?'selected':'' }}>{{ $acc->account_number }} — {{ $acc->user->name }}</option>
                    @endforeach
                </select>
                @error('account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Loan Type <span class="text-danger">*</span></label>
                <select name="loan_type" class="form-select" required>
                    <option value="">Select type...</option>
                    <option value="personal" {{ old('loan_type')=='personal'?'selected':'' }}>Personal Loan</option>
                    <option value="home" {{ old('loan_type')=='home'?'selected':'' }}>Home Loan</option>
                    <option value="auto" {{ old('loan_type')=='auto'?'selected':'' }}>Auto Loan</option>
                    <option value="business" {{ old('loan_type')=='business'?'selected':'' }}>Business Loan</option>
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Principal Amount ($) <span class="text-danger">*</span></label>
                    <input type="number" name="principal_amount" id="principal" class="form-control" value="{{ old('principal_amount') }}" min="100" step="0.01" required oninput="calcEmi()">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Annual Interest Rate (%) <span class="text-danger">*</span></label>
                    <input type="number" name="interest_rate" id="rate" class="form-control" value="{{ old('interest_rate',10) }}" min="0" max="100" step="0.01" required oninput="calcEmi()">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tenure (Months) <span class="text-danger">*</span></label>
                <select name="tenure_months" id="tenure" class="form-select" required onchange="calcEmi()">
                    @foreach([6,12,18,24,36,48,60,84,120,180,240,360] as $m)
                    <option value="{{ $m }}" {{ old('tenure_months',12)==$m?'selected':'' }}>{{ $m }} months ({{ round($m/12,1) }} yrs)</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Purpose</label>
                <textarea name="purpose" class="form-control" rows="2" placeholder="Brief description of loan purpose...">{{ old('purpose') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Submit Application</button>
        </form>
    </div>
</div>
</div>
<div class="col-lg-5">
<div class="card border-primary">
    <div class="card-header bg-primary text-white"><i class="bi bi-calculator me-2"></i>EMI Calculator</div>
    <div class="card-body">
        <div class="text-center mb-3">
            <div class="text-muted small">Monthly EMI</div>
            <div class="display-5 fw-bold text-primary" id="emiDisplay">$0.00</div>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Principal</span><span id="principalDisplay">$0.00</span></div>
        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Total Interest</span><span id="interestDisplay" class="text-warning">$0.00</span></div>
        <div class="d-flex justify-content-between fw-bold"><span>Total Payable</span><span id="totalDisplay" class="text-danger">$0.00</span></div>
    </div>
</div>
</div>
</div>
</div>
</div>
@push('scripts')
<script>
function calcEmi(){
    const p = parseFloat(document.getElementById('principal').value)||0;
    const r = parseFloat(document.getElementById('rate').value)/12/100;
    const n = parseInt(document.getElementById('tenure').value)||12;
    let emi = 0;
    if(p && r){ emi = p * r * Math.pow(1+r,n) / (Math.pow(1+r,n)-1); }
    else if(p){ emi = p/n; }
    const total = emi * n;
    const interest = total - p;
    document.getElementById('emiDisplay').textContent = '$'+emi.toFixed(2);
    document.getElementById('principalDisplay').textContent = '$'+p.toFixed(2);
    document.getElementById('interestDisplay').textContent = '$'+interest.toFixed(2);
    document.getElementById('totalDisplay').textContent = '$'+total.toFixed(2);
}
calcEmi();
</script>
@endpush
@endsection
