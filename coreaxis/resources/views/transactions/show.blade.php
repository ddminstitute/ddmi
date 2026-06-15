@extends('layouts.banking')
@section('title','Transaction Receipt')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Transaction Receipt</h5>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Reference: <code>{{ $transaction->reference_number }}</code></span>
        <span class="badge bg-{{ $transaction->getTypeBadge() }} fs-6">{{ $transaction->getTypeLabel() }}</span>
    </div>
    <div class="card-body">
        <div class="text-center mb-4">
            <div class="display-4 fw-bold {{ in_array($transaction->transaction_type,['deposit','transfer_in'])?'text-success':'text-danger' }}">
                {{ in_array($transaction->transaction_type,['deposit','transfer_in'])?'+':'-' }}₹{{ number_format($transaction->amount,2) }}
            </div>
            <div class="text-muted">{{ $transaction->getTypeLabel() }}</div>
        </div>
        <table class="table table-borderless">
            <tr><td class="text-muted">Account</td><td class="fw-semibold">{{ $transaction->account->account_number }}</td></tr>
            <tr><td class="text-muted">Customer</td><td>{{ $transaction->account->user->name }}</td></tr>
            <tr><td class="text-muted">Balance Before</td><td>₹{{ number_format($transaction->balance_before,2) }}</td></tr>
            <tr><td class="text-muted">Balance After</td><td class="fw-semibold">₹{{ number_format($transaction->balance_after,2) }}</td></tr>
            @if($transaction->relatedAccount)
            <tr><td class="text-muted">{{ in_array($transaction->transaction_type,['transfer_in'])?'From':'To' }} Account</td><td>{{ $transaction->relatedAccount->account_number }}</td></tr>
            @endif
            <tr><td class="text-muted">Description</td><td>{{ $transaction->description }}</td></tr>
            <tr><td class="text-muted">Date & Time</td><td>{{ $transaction->created_at->format('M d, Y H:i:s') }}</td></tr>
            <tr><td class="text-muted">Status</td><td><span class="badge bg-success">{{ ucfirst($transaction->status) }}</span></td></tr>
        </table>
    </div>
</div>
</div>
</div>
@endsection
