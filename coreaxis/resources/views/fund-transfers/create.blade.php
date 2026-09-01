@extends('layouts.banking')
@section('title','Initiate Fund Transfer')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('fund-transfers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Initiate Fund Transfer (NEFT / RTGS / IMPS)</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<form method="POST" action="{{ route('fund-transfers.store') }}">@csrf
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">From Account <span class="text-danger">*</span></label>
    <select name="account_id" class="form-select" required id="srcAcc">
        <option value="">— Select —</option>
        @foreach($accounts as $acc)<option value="{{ $acc->id }}" data-bal="{{ $acc->balance }}">{{ $acc->account_number }} — {{ $acc->customer?->name??$acc->user?->name }} (₹{{ number_format($acc->balance,2) }})</option>@endforeach
    </select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Transfer Mode <span class="text-danger">*</span></label>
    <select name="transfer_mode" class="form-select" required id="txMode">
        <option value="neft">NEFT</option><option value="rtgs">RTGS (min ₹2,00,000)</option>
        <option value="imps">IMPS</option><option value="upi">UPI</option>
    </select></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Beneficiary Name <span class="text-danger">*</span></label><input type="text" name="beneficiary_name" class="form-control" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Beneficiary Account No. <span class="text-danger">*</span></label><input type="text" name="beneficiary_account" class="form-control" required></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">IFSC Code <span class="text-danger">*</span></label><input type="text" name="beneficiary_ifsc" class="form-control" maxlength="11" placeholder="SBIN0001234" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Beneficiary Bank</label><input type="text" name="beneficiary_bank" class="form-control" placeholder="State Bank of India..."></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Amount (₹) <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-text">₹</span><input type="number" name="amount" class="form-control" step="0.01" min="1" id="txAmt" required></div></div>
    <div class="col-md-6 mb-3 d-flex align-items-end"><div class="alert alert-info mb-0 w-100 py-2" id="chargeInfo"><small>Charges will be calculated based on amount and mode.</small></div></div>
</div>
<div class="mb-3"><label class="form-label">Description / Purpose</label><input type="text" name="description" class="form-control" placeholder="Payment for..."></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('fund-transfers.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Initiate Transfer</button></div>
</form>
</div></div></div></div>
@push('scripts')
<script>
function calcCharges(){
    const amt=parseFloat(document.getElementById('txAmt').value)||0;
    const mode=document.getElementById('txMode').value;
    let ch=0;
    if(mode==='neft') ch=amt<=10000?2.5:amt<=100000?5:amt<=200000?15:25;
    else if(mode==='rtgs') ch=amt<=500000?25:50;
    else if(mode==='imps') ch=amt<=10000?2.5:amt<=100000?5:15;
    document.getElementById('chargeInfo').innerHTML=ch?`<small>Estimated charges: <strong>₹${ch}</strong> | Total debit: <strong>₹${(amt+ch).toLocaleString('en-IN',{minimumFractionDigits:2})}</strong></small>`:`<small>No charges for UPI</small>`;
}
['txAmt','txMode'].forEach(id=>document.getElementById(id).addEventListener('change',calcCharges));
</script>
@endpush
@endsection
