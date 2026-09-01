@extends('layouts.banking')
@section('title','Issue Demand Draft')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('demand-drafts.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Issue Demand Draft / Pay Order</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<form method="POST" action="{{ route('demand-drafts.store') }}">@csrf
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Account (Debit) <span class="text-danger">*</span></label><select name="account_id" class="form-select" required><option value="">— Select —</option>@foreach($accounts as $acc)<option value="{{ $acc->id }}">{{ $acc->account_number }} — {{ $acc->customer?->name??$acc->user?->name }}</option>@endforeach</select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Instrument Type <span class="text-danger">*</span></label><select name="instrument_type" class="form-select" required><option value="demand_draft">Demand Draft</option><option value="pay_order">Pay Order</option></select></div>
</div>
<div class="mb-3"><label class="form-label">Payee Name <span class="text-danger">*</span></label><input type="text" name="payee_name" class="form-control" required></div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Payable At (City)</label><input type="text" name="payable_at_city" class="form-control"></div>
    <div class="col-md-6 mb-3"><label class="form-label">Payable At (Bank)</label><input type="text" name="payable_at_bank" class="form-control"></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Amount (₹) <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-text">₹</span><input type="number" name="amount" class="form-control" step="0.01" min="100" id="ddAmt" required></div></div>
    <div class="col-md-6 mb-3 d-flex align-items-end"><div class="alert alert-info mb-0 w-100 py-2 small" id="ddChargeInfo">Enter amount to see charges</div></div>
</div>
<div class="mb-3"><label class="form-label">Issue Date <span class="text-danger">*</span></label><input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('demand-drafts.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Issue Demand Draft</button></div>
</form>
</div></div></div></div>
@push('scripts')
<script>
document.getElementById('ddAmt').addEventListener('input',function(){
    const amt=parseFloat(this.value)||0;
    const ch=amt<=10000?50:amt<=100000?100:200;
    document.getElementById('ddChargeInfo').innerHTML=`Charges: <strong>₹${ch}</strong> | Total: <strong>₹${(amt+ch).toLocaleString('en-IN',{minimumFractionDigits:2})}</strong>`;
});
</script>
@endpush
@endsection
