@extends('layouts.print')
@section('print-title','Loan Sanction Certificate — '.$loan->loan_number)
@section('print-content')
<div class="doc-title"><i class="bi bi-award me-2"></i>Loan Sanction Certificate</div>
<div class="text-center mb-3">
    <div style="font-size:1rem;font-weight:700;color:#1565C0;text-transform:uppercase;letter-spacing:1px">This is to certify that the following loan has been sanctioned and disbursed</div>
</div>
<hr>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="info-row"><span class="info-label">Loan Number:</span><span class="fw-bold">{{ $loan->loan_number }}</span></div>
        <div class="info-row"><span class="info-label">Borrower Name:</span><span class="fw-bold">{{ $loan->customer?->name ?? $loan->user?->name }}</span></div>
        @if($loan->customer)
        <div class="info-row"><span class="info-label">Customer ID:</span><span>{{ $loan->customer->customer_id }}</span></div>
        <div class="info-row"><span class="info-label">Mobile:</span><span>{{ $loan->customer->phone }}</span></div>
        @endif
        <div class="info-row"><span class="info-label">Loan Type:</span><span class="text-capitalize">{{ str_replace('_',' ',$loan->loan_type) }}</span></div>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="info-row justify-content-md-end"><span class="info-label">Loan Amount:</span><span class="fw-bold fs-5 text-primary">₹{{ number_format($loan->amount,2) }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Interest Rate:</span><span>{{ $loan->interest_rate }}% per annum</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Tenure:</span><span>{{ $loan->tenure_months }} months</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Monthly EMI:</span><span class="fw-bold text-success">₹{{ number_format($loan->emi_amount,2) }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Disbursed On:</span><span>{{ $loan->disbursed_at ? \Carbon\Carbon::parse($loan->disbursed_at)->format('d M Y') : '—' }}</span></div>
    </div>
</div>
<hr>
@php
    $totalPayable = $loan->emi_amount * $loan->tenure_months;
    $totalInterest = $totalPayable - $loan->amount;
@endphp
<div class="row g-2 mb-3">
    <div class="col-4"><div class="border rounded p-2 text-center"><div class="small text-muted">Principal</div><div class="fw-bold text-primary">₹{{ number_format($loan->amount,2) }}</div></div></div>
    <div class="col-4"><div class="border rounded p-2 text-center"><div class="small text-muted">Total Interest</div><div class="fw-bold text-warning">₹{{ number_format($totalInterest,2) }}</div></div></div>
    <div class="col-4"><div class="border rounded p-2 text-center"><div class="small text-muted">Total Payable</div><div class="fw-bold text-danger">₹{{ number_format($totalPayable,2) }}</div></div></div>
</div>
@if($loan->emiSchedules && $loan->emiSchedules->count())
<h6 class="fw-bold mt-3 mb-2">EMI Schedule</h6>
<table class="table table-bordered table-sm">
    <thead><tr><th>#</th><th>Due Date</th><th class="text-end">EMI (₹)</th><th class="text-end">Principal (₹)</th><th class="text-end">Interest (₹)</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($loan->emiSchedules->take(12) as $emi)
        <tr>
            <td>{{ $emi->installment_number }}</td>
            <td>{{ \Carbon\Carbon::parse($emi->due_date)->format('d M Y') }}</td>
            <td class="text-end">{{ number_format($emi->emi_amount,2) }}</td>
            <td class="text-end">{{ number_format($emi->principal_component,2) }}</td>
            <td class="text-end">{{ number_format($emi->interest_component,2) }}</td>
            <td><span class="badge bg-{{ $emi->status==='paid'?'success':($emi->status==='overdue'?'danger':'secondary') }}">{{ ucfirst($emi->status) }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@if($loan->emiSchedules->count() > 12)<p class="text-muted small text-center">Showing first 12 of {{ $loan->emiSchedules->count() }} installments</p>@endif
@endif
<div class="row mt-4">
    <div class="col-6"><div class="border-top pt-3 text-center"><div class="small text-muted">Borrower Signature</div></div></div>
    <div class="col-6"><div class="border-top pt-3 text-center"><div class="small text-muted">Authorized Signatory</div></div></div>
</div>
@endsection
