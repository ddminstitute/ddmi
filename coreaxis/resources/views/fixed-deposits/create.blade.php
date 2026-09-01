@extends('layouts.banking')
@section('title','Open Fixed Deposit')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('fixed-deposits.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Open Fixed Deposit</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('fixed-deposits.store') }}">@csrf
<div class="mb-3"><label class="form-label">Account <span class="text-danger">*</span></label>
<select name="account_id" class="form-select" required>
    <option value="">— Select Account —</option>
    @foreach($accounts as $acc)<option value="{{ $acc->id }}">{{ $acc->account_number }} — {{ $acc->customer?->name ?? $acc->user?->name }} (Bal: ₹{{ number_format($acc->balance,2) }})</option>@endforeach
</select></div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Principal Amount (₹) <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-text">₹</span><input type="number" name="principal_amount" class="form-control" min="500" step="0.01" required></div></div>
    <div class="col-md-6 mb-3"><label class="form-label">Interest Rate (% p.a.) <span class="text-danger">*</span></label><input type="number" name="interest_rate" class="form-control" step="0.01" min="0" max="20" required></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Tenure (Months) <span class="text-danger">*</span></label><input type="number" name="tenure_months" class="form-control" min="1" max="120" required id="tenureMonths"></div>
    <div class="col-md-6 mb-3"><label class="form-label">Compounding <span class="text-danger">*</span></label>
    <select name="compounding" class="form-select" required>
        <option value="quarterly">Quarterly</option><option value="monthly">Monthly</option>
        <option value="half_yearly">Half Yearly</option><option value="yearly">Yearly</option>
        <option value="on_maturity">On Maturity (Simple)</option>
    </select></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Start Date <span class="text-danger">*</span></label><input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
    <div class="col-md-6 mb-3 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="auto_renew" value="1"><label class="form-check-label">Auto Renew on Maturity</label></div></div>
</div>
<div class="alert alert-info" id="fdPreview" style="display:none">
    <strong>Estimated Maturity Amount:</strong> <span id="maturityAmt">—</span>
    <span class="ms-3"><strong>Interest Earned:</strong> <span id="interestAmt">—</span></span>
</div>
<div class="d-flex gap-2 justify-content-end mt-3">
    <a href="{{ route('fixed-deposits.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Open Fixed Deposit</button>
</div>
</form>
</div></div>
</div></div>
@push('scripts')
<script>
function calcFD() {
    const P = parseFloat(document.querySelector('[name=principal_amount]').value) || 0;
    const r = parseFloat(document.querySelector('[name=interest_rate]').value) || 0;
    const n = parseInt(document.getElementById('tenureMonths').value) || 0;
    const comp = document.querySelector('[name=compounding]').value;
    if (!P || !r || !n) { document.getElementById('fdPreview').style.display='none'; return; }
    let nFreq = {monthly:12,quarterly:4,half_yearly:2,yearly:1,on_maturity:1}[comp] || 4;
    let mat = comp === 'on_maturity' ? P * (1 + (r/100) * (n/12)) : P * Math.pow(1 + (r/100/nFreq), nFreq * n/12);
    mat = Math.round(mat*100)/100;
    document.getElementById('maturityAmt').textContent = '₹' + mat.toLocaleString('en-IN',{minimumFractionDigits:2});
    document.getElementById('interestAmt').textContent = '₹' + (mat-P).toLocaleString('en-IN',{minimumFractionDigits:2});
    document.getElementById('fdPreview').style.display = 'block';
}
document.querySelectorAll('[name=principal_amount],[name=interest_rate],[name=compounding],#tenureMonths').forEach(el => el.addEventListener('input',calcFD));
</script>
@endpush
@endsection
