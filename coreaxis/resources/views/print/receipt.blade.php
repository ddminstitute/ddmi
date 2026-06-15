@extends('layouts.print')
@section('print-title','Transaction Receipt — '.$transaction->reference_number)
@section('print-content')
<div class="doc-title"><i class="bi bi-receipt me-2"></i>Transaction Receipt</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="info-row"><span class="info-label">Receipt No:</span><span class="fw-bold">{{ $transaction->reference_number }}</span></div>
        <div class="info-row"><span class="info-label">Date & Time:</span><span>{{ $transaction->created_at->format('d M Y h:i A') }}</span></div>
        <div class="info-row"><span class="info-label">Transaction Type:</span><span class="text-capitalize">{{ str_replace('_',' ',$transaction->transaction_type) }}</span></div>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="info-row justify-content-md-end"><span class="info-label">Account Number:</span><span class="fw-bold">{{ $transaction->account->account_number }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Account Holder:</span><span>{{ $transaction->account->customer?->name ?? $transaction->account->user?->name }}</span></div>
    </div>
</div>
<hr>
<div class="text-center my-4">
    <div style="font-size:2.5rem;font-weight:800;color:{{ in_array($transaction->transaction_type,['deposit','transfer_in']) ? '#2e7d32' : '#c62828' }}">
        {{ in_array($transaction->transaction_type,['deposit','transfer_in']) ? '+' : '−' }}₹{{ number_format($transaction->amount,2) }}
    </div>
    <div class="text-muted mt-1">{{ $transaction->description }}</div>
</div>
<hr>
<div class="row g-2">
    <div class="col-md-6">
        <div class="info-row"><span class="info-label">Balance Before:</span><span>₹{{ number_format($transaction->balance_before ?? ($transaction->balance_after - (in_array($transaction->transaction_type,['deposit','transfer_in']) ? $transaction->amount : -$transaction->amount)),2) }}</span></div>
        <div class="info-row"><span class="info-label">Balance After:</span><span class="fw-bold text-success">₹{{ number_format($transaction->balance_after,2) }}</span></div>
    </div>
    @if($transaction->reference_number)
    <div class="col-md-6 text-md-end">
        <div class="info-row justify-content-md-end"><span class="info-label">Reference:</span><span class="small">{{ $transaction->reference_number }}</span></div>
    </div>
    @endif
</div>
<div class="text-center mt-4">
    <span class="badge bg-success fs-6 px-4 py-2"><i class="bi bi-check-circle me-2"></i>Transaction Successful</span>
</div>
@endsection
