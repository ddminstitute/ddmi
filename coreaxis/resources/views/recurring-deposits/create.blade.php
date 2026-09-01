@extends('layouts.banking')
@section('title','Open Recurring Deposit')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('recurring-deposits.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Open Recurring Deposit</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('recurring-deposits.store') }}">@csrf
<div class="mb-3"><label class="form-label">Account <span class="text-danger">*</span></label>
<select name="account_id" class="form-select" required>
    <option value="">— Select Account —</option>
    @foreach($accounts as $acc)<option value="{{ $acc->id }}">{{ $acc->account_number }} — {{ $acc->customer?->name ?? $acc->user?->name }} (Bal: ₹{{ number_format($acc->balance,2) }})</option>@endforeach
</select></div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Monthly Installment (₹) <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-text">₹</span><input type="number" name="monthly_installment" class="form-control" min="100" step="0.01" id="rdAmount" required></div></div>
    <div class="col-md-6 mb-3"><label class="form-label">Interest Rate (% p.a.) <span class="text-danger">*</span></label><input type="number" name="interest_rate" class="form-control" step="0.01" min="0" max="20" id="rdRate" required></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Tenure (Months) <span class="text-danger">*</span></label><input type="number" name="tenure_months" class="form-control" min="6" max="120" id="rdTenure" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Start Date <span class="text-danger">*</span></label><input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
</div>
<div class="alert alert-info" id="rdPreview" style="display:none">
    <strong>Total Deposit:</strong> <span id="rdTotal">—</span> &nbsp;|&nbsp;
    <strong>Est. Maturity:</strong> <span id="rdMat">—</span>
</div>
<div class="d-flex gap-2 justify-content-end mt-3">
    <a href="{{ route('recurring-deposits.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Open RD</button>
</div>
</form>
</div></div></div></div>
@push('scripts')
<script>
function calcRD(){
    const P=parseFloat(document.getElementById('rdAmount').value)||0;
    const r=(parseFloat(document.getElementById('rdRate').value)||0)/100/12;
    const n=parseInt(document.getElementById('rdTenure').value)||0;
    if(!P||!n){document.getElementById('rdPreview').style.display='none';return;}
    const total=P*n;
    const mat=r>0?Math.round(P*((Math.pow(1+r,n)-1)/r)*(1+r)*100)/100:total;
    document.getElementById('rdTotal').textContent='₹'+total.toLocaleString('en-IN',{minimumFractionDigits:2});
    document.getElementById('rdMat').textContent='₹'+mat.toLocaleString('en-IN',{minimumFractionDigits:2});
    document.getElementById('rdPreview').style.display='block';
}
['rdAmount','rdRate','rdTenure'].forEach(id=>document.getElementById(id).addEventListener('input',calcRD));
</script>
@endpush
@endsection
