@extends('layouts.banking')
@section('title','Account Details')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('accounts.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Account — {{ $account->account_number }}</h5>
    <div class="ms-auto d-flex gap-2">
        <a href="{{ route('print.passbook',$account) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-book me-1"></i>Passbook</a>
        <a href="{{ route('print.statement',$account) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-text me-1"></i>Statement</a>
        <a href="{{ route('accounts.nominees.index',$account) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-people me-1"></i>Nominees</a>
        <a href="{{ route('accounts.edit',$account) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">Account Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6"><div class="text-muted small">Customer</div><div class="fw-semibold">{{ $account->user->name }}</div><div class="text-muted small">{{ $account->user->email }}</div></div>
                    <div class="col-sm-6"><div class="text-muted small">Account Number</div><code class="fs-6">{{ $account->account_number }}</code></div>
                    <div class="col-sm-6"><div class="text-muted small">Account Type</div><div>{{ $account->getTypeLabel() }}</div></div>
                    <div class="col-sm-6"><div class="text-muted small">Currency</div><div>{{ $account->currency }}</div></div>
                    <div class="col-sm-6"><div class="text-muted small">Status</div><span class="badge bg-{{ $account->status==='active'?'success':($account->status==='frozen'?'warning text-dark':'secondary') }} fs-6">{{ ucfirst($account->status) }}</span></div>
                    <div class="col-sm-6"><div class="text-muted small">Opened</div><div>{{ $account->created_at->format('M d, Y') }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100" style="background:linear-gradient(135deg,#1565C0,#1976D2)">
            <div class="card-body text-white d-flex flex-column justify-content-center align-items-center py-4">
                <div class="opacity-75 small mb-2">Current Balance</div>
                <div class="display-5 fw-bold">₹{{ number_format($account->balance,2) }}</div>
                <div class="opacity-75 mt-1">{{ $account->currency }}</div>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('transactions.deposit') }}?account_id={{ $account->id }}" class="btn btn-sm btn-light">Deposit</a>
                    <a href="{{ route('transactions.withdraw') }}?account_id={{ $account->id }}" class="btn btn-sm btn-outline-light">Withdraw</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-clock-history me-2"></i>Transaction History</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Reference</th><th>Type</th><th>Amount</th><th>Balance After</th><th>Description</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr>
                        <td><code class="small">{{ $txn->reference_number }}</code></td>
                        <td><span class="badge bg-{{ $txn->getTypeBadge() }}">{{ $txn->getTypeLabel() }}</span></td>
                        <td class="fw-semibold {{ in_array($txn->transaction_type,['deposit','transfer_in'])?'text-success':'text-danger' }}">
                            {{ in_array($txn->transaction_type,['deposit','transfer_in'])?'+':'-' }}₹{{ number_format($txn->amount,2) }}
                        </td>
                        <td>₹{{ number_format($txn->balance_after,2) }}</td>
                        <td class="text-muted small">{{ $txn->description }}</td>
                        <td class="text-muted small">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                        <td><a href="{{ route('print.receipt',$txn) }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="font-size:.72rem;padding:.2rem .5rem"><i class="bi bi-printer"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">No transactions yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transactions->hasPages())
    <div class="card-footer">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection
