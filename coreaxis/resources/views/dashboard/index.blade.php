@extends('layouts.banking')
@section('title', 'Dashboard')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1565C0,#1976D2)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Total Accounts</div>
                    <div class="fs-3 fw-bold">{{ $totalAccounts }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#00897B,#26A69A)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Total Balance</div>
                    <div class="fs-3 fw-bold">₹{{ number_format($totalBalance,2) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-currency-rupee"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#E65100,#F57C00)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Active Loans</div>
                    <div class="fs-3 fw-bold">{{ $activeLoans }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-credit-card"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6A1B9A,#8E24AA)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Monthly Deposits</div>
                    <div class="fs-3 fw-bold">₹{{ number_format($monthlyDeposits,2) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <a href="{{ route('accounts.create') }}" class="btn btn-primary w-100 py-3">
            <i class="bi bi-plus-circle d-block fs-4 mb-1"></i><small>New Account</small>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('transactions.deposit') }}" class="btn btn-success w-100 py-3">
            <i class="bi bi-arrow-down-circle d-block fs-4 mb-1"></i><small>Deposit</small>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('transactions.withdraw') }}" class="btn btn-warning w-100 py-3 text-dark">
            <i class="bi bi-arrow-up-circle d-block fs-4 mb-1"></i><small>Withdraw</small>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('loans.create') }}" class="btn btn-info w-100 py-3 text-white">
            <i class="bi bi-file-earmark-plus d-block fs-4 mb-1"></i><small>Apply Loan</small>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-arrow-left-right me-2 text-primary"></i>Recent Transactions</span>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Reference</th><th>Account</th><th>Type</th><th>Amount</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $txn)
                            <tr>
                                <td><code class="small">{{ $txn->reference_number }}</code></td>
                                <td>{{ $txn->account->account_number }}</td>
                                <td><span class="badge bg-{{ $txn->getTypeBadge() }}">{{ $txn->getTypeLabel() }}</span></td>
                                <td class="fw-semibold {{ in_array($txn->transaction_type,['deposit','transfer_in']) ? 'text-success' : 'text-danger' }}">
                                    {{ in_array($txn->transaction_type,['deposit','transfer_in']) ? '+' : '-' }}₹{{ number_format($txn->amount,2) }}
                                </td>
                                <td class="text-muted small">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No transactions yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-wallet2 me-2 text-primary"></i>Recent Accounts</span>
                <a href="{{ route('accounts.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="list-group list-group-flush rounded-bottom">
                @forelse($recentAccounts as $acc)
                <a href="{{ route('accounts.show',$acc) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-semibold small">{{ $acc->account_number }}</div>
                            <div class="text-muted" style="font-size:.78rem">{{ $acc->user->name }} · {{ $acc->getTypeLabel() }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold small text-success">₹{{ number_format($acc->balance,2) }}</div>
                            <span class="badge bg-{{ $acc->status==='active' ? 'success' : 'secondary' }} mt-1" style="font-size:.65rem">{{ ucfirst($acc->status) }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="list-group-item text-center text-muted py-4">No accounts yet</div>
                @endforelse
            </div>
        </div>
        @if($pendingLoans > 0)
        <div class="alert alert-warning mt-3 d-flex align-items-center">
            <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
            <div><strong>{{ $pendingLoans }}</strong> loan(s) pending approval. <a href="{{ route('loans.index') }}?status=pending">Review now</a></div>
        </div>
        @endif
    </div>
</div>
@endsection
