@extends('layouts.banking')
@section('title','Transaction Receipt')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Transaction Receipt</h5>
    <a href="{{ route('print.receipt',$transaction) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-auto"><i class="bi bi-printer me-1"></i>Print Receipt</a>
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

@if(!$transaction->is_reversed && in_array($transaction->transaction_type, ['deposit','withdrawal']))
<div class="card mt-3 border-danger">
    <div class="card-header text-danger"><i class="bi bi-arrow-counterclockwise me-2"></i>Reverse Transaction</div>
    <div class="card-body">
        <p class="text-muted small">This will create an offsetting entry to reverse the transaction. Action cannot be undone.</p>
        <form method="POST" action="{{ route('transactions.reverse',$transaction) }}" onsubmit="return confirm('Confirm reversal of ₹{{ number_format($transaction->amount,2) }}?')">
            @csrf
            <div class="mb-2">
                <input type="text" name="reason" class="form-control" placeholder="Reason for reversal *" required>
            </div>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-arrow-counterclockwise me-1"></i>Reverse Transaction</button>
        </form>
    </div>
</div>
@elseif($transaction->is_reversed)
<div class="alert alert-warning mt-3"><i class="bi bi-exclamation-triangle me-2"></i>This transaction has been reversed.</div>
@endif
</div>
</div>
@endsection
