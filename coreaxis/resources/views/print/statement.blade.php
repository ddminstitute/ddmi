@extends('layouts.print')
@section('print-title','Account Statement — '.$account->account_number)
@section('print-content')
<div class="doc-title"><i class="bi bi-file-earmark-text me-2"></i>Account Statement</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="info-row"><span class="info-label">Account Number:</span><span class="fw-bold">{{ $account->account_number }}</span></div>
        <div class="info-row"><span class="info-label">Account Type:</span><span>{{ $account->getTypeLabel() }}</span></div>
        <div class="info-row"><span class="info-label">Account Holder:</span><span class="fw-bold">{{ $account->customer?->name ?? $account->user?->name }}</span></div>
        <div class="info-row"><span class="info-label">Currency:</span><span>{{ $account->currency }}</span></div>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="info-row justify-content-md-end"><span class="info-label">Statement Period:</span><span>{{ $from }} to {{ $to }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Current Balance:</span><span class="fw-bold text-success fs-5">₹{{ number_format($account->balance,2) }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Status:</span><span class="badge bg-{{ $account->status==='active'?'success':'secondary' }}">{{ ucfirst($account->status) }}</span></div>
    </div>
</div>
<hr>
@php
    $totalCredit = $transactions->filter(fn($t)=>in_array($t->transaction_type,['deposit','transfer_in']))->sum('amount');
    $totalDebit  = $transactions->filter(fn($t)=>!in_array($t->transaction_type,['deposit','transfer_in']))->sum('amount');
@endphp
<div class="row g-2 mb-3">
    <div class="col-4"><div class="border rounded p-2 text-center"><div class="small text-muted">Total Credits</div><div class="fw-bold text-success">₹{{ number_format($totalCredit,2) }}</div></div></div>
    <div class="col-4"><div class="border rounded p-2 text-center"><div class="small text-muted">Total Debits</div><div class="fw-bold text-danger">₹{{ number_format($totalDebit,2) }}</div></div></div>
    <div class="col-4"><div class="border rounded p-2 text-center"><div class="small text-muted">Transactions</div><div class="fw-bold">{{ $transactions->count() }}</div></div></div>
</div>
<table class="table table-bordered table-sm">
    <thead><tr><th>Date</th><th>Description</th><th>Ref. No</th><th class="text-end">Debit (₹)</th><th class="text-end">Credit (₹)</th><th class="text-end">Balance (₹)</th></tr></thead>
    <tbody>
        @forelse($transactions->sortBy('created_at') as $txn)
        @php $isCredit = in_array($txn->transaction_type,['deposit','transfer_in']); @endphp
        <tr>
            <td>{{ $txn->created_at->format('d M Y') }}</td>
            <td>{{ $txn->description }}</td>
            <td style="font-size:.75rem">{{ $txn->reference_number }}</td>
            <td class="text-end text-danger">{{ !$isCredit ? number_format($txn->amount,2) : '' }}</td>
            <td class="text-end text-success">{{ $isCredit ? number_format($txn->amount,2) : '' }}</td>
            <td class="text-end fw-semibold">{{ number_format($txn->balance_after,2) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted py-3">No transactions in this period</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="fw-bold"><td colspan="5" class="text-end">Closing Balance:</td><td class="text-end text-primary">₹{{ number_format($account->balance,2) }}</td></tr>
    </tfoot>
</table>
@endsection
