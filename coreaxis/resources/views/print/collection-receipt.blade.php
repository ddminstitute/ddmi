@extends('layouts.print')
@section('print-title','Collection Receipt — '.$entry->receipt_number)
@section('print-content')
<div class="doc-title"><i class="bi bi-receipt-cutoff me-2"></i>Collection Receipt</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="info-row"><span class="info-label">Receipt Number:</span><span class="fw-bold">{{ $entry->receipt_number }}</span></div>
        <div class="info-row"><span class="info-label">Collection Date:</span><span>{{ $entry->collected_at ? \Carbon\Carbon::parse($entry->collected_at)->format('d M Y h:i A') : $entry->created_at->format('d M Y h:i A') }}</span></div>
        <div class="info-row"><span class="info-label">Plan Number:</span><span>{{ $entry->collectionPlan->plan_number }}</span></div>
        <div class="info-row"><span class="info-label">Plan Type:</span><span>{{ ucfirst($entry->collectionPlan->collection_type) }}</span></div>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="info-row justify-content-md-end"><span class="info-label">Customer Name:</span><span class="fw-bold">{{ $entry->collectionPlan->customer?->name }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Customer ID:</span><span>{{ $entry->collectionPlan->customer?->customer_id }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Mobile:</span><span>{{ $entry->collectionPlan->customer?->phone }}</span></div>
    </div>
</div>
<hr>
<div class="text-center my-4">
    <div class="text-muted mb-1">Amount Collected</div>
    <div style="font-size:3rem;font-weight:900;color:#1565C0">₹{{ number_format($entry->amount,2) }}</div>
    <div class="mt-2">
        <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-check-circle me-2"></i>Payment Received</span>
    </div>
</div>
<hr>
<div class="row g-2">
    <div class="col-md-6">
        <div class="info-row"><span class="info-label">Installment No:</span><span>{{ $entry->installment_number }}</span></div>
        <div class="info-row"><span class="info-label">Collected By:</span><span>{{ $entry->collected_by ?? '—' }}</span></div>
        @if($entry->notes)
        <div class="info-row"><span class="info-label">Notes:</span><span>{{ $entry->notes }}</span></div>
        @endif
    </div>
    <div class="col-md-6 text-md-end">
        <div class="info-row justify-content-md-end"><span class="info-label">Total Collected:</span><span class="fw-bold">₹{{ number_format($entry->collectionPlan->totalCollected(),2) }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Installments Paid:</span><span>{{ $entry->collectionPlan->paidInstallments() }} / {{ $entry->collectionPlan->total_installments }}</span></div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-6"><div class="border-top pt-3 text-center"><div class="small text-muted">Customer Signature</div></div></div>
    <div class="col-6"><div class="border-top pt-3 text-center"><div class="small text-muted">Collector Signature</div></div></div>
</div>
@endsection
