@extends('layouts.print')
@section('print-title','Passbook — '.$account->account_number)
@section('print-content')
<div class="doc-title"><i class="bi bi-book me-2"></i>Account Passbook</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="info-row"><span class="info-label">Account Number:</span><span class="fw-bold">{{ $account->account_number }}</span></div>
        <div class="info-row"><span class="info-label">Account Type:</span><span>{{ $account->getTypeLabel() }}</span></div>
        <div class="info-row"><span class="info-label">Account Holder:</span><span class="fw-bold">{{ $account->customer?->name ?? $account->user?->name }}</span></div>
        <div class="info-row"><span class="info-label">Currency:</span><span>{{ $account->currency }}</span></div>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="info-row justify-content-md-end"><span class="info-label">Current Balance:</span><span class="fw-bold text-success fs-5">₹{{ number_format($account->balance,2) }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Status:</span><span class="badge bg-{{ $account->status==='active'?'success':'secondary' }}">{{ ucfirst($account->status) }}</span></div>
    </div>
</div>
<hr>
<table class="table table-bordered table-sm">
    <thead><tr><th>Date</th><th>Description</th><th>Ref. No</th><th class="text-end">Debit (₹)</th><th class="text-end">Credit (₹)</th><th class="text-end">Balance (₹)</th></tr></thead>
    <tbody>
        @php $runBalance = 0; @endphp
        @foreach($transactions->sortBy('created_at') as $txn)
        @php
            $isCredit = in_array($txn->transaction_type,['deposit','transfer_in']);
            $runBalance = $isCredit ? $txn->balance_after : $txn->balance_after;
        @endphp
        <tr>
            <td>{{ $txn->created_at->format('d M Y') }}</td>
            <td>{{ $txn->description }}</td>
            <td style="font-size:.75rem">{{ $txn->reference_number }}</td>
            <td class="text-end text-danger">{{ !$isCredit ? number_format($txn->amount,2) : '' }}</td>
            <td class="text-end text-success">{{ $isCredit ? number_format($txn->amount,2) : '' }}</td>
            <td class="text-end fw-semibold">{{ number_format($txn->balance_after,2) }}</td>
        </tr>
        @endforeach
        @if($transactions->isEmpty())
        <tr><td colspan="6" class="text-center text-muted py-3">No transactions found</td></tr>
        @endif
    </tbody>
    <tfoot>
        <tr class="fw-bold"><td colspan="5" class="text-end">Closing Balance:</td><td class="text-end text-primary">₹{{ number_format($account->balance,2) }}</td></tr>
    </tfoot>
</table>
@endsection
