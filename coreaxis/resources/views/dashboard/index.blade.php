@extends('layouts.banking')
@section('title', 'Dashboard')
@section('content')

{{-- Welcome Banner --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Welcome back, {{ auth()->user()->name }} 👋</h5>
        <div class="text-muted small">
            <span class="badge bg-{{ auth()->user()->isSuperAdmin() ? 'warning text-dark' : (auth()->user()->isAdmin() ? 'primary' : 'secondary') }} me-1">
                {{ ucfirst(str_replace('_',' ', auth()->user()->role ?? 'user')) }}
            </span>
            {{ now()->format('l, d M Y') }}
        </div>
    </div>
    @if(auth()->user()->isSuperAdmin())
    <a href="{{ route('super-admin.dashboard') }}" class="btn btn-warning btn-sm fw-semibold">
        <i class="bi bi-shield-fill-check me-1"></i>Super Admin Panel
    </a>
    @endif
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    @if(auth()->user()->hasFeature('accounts'))
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1565C0,#1976D2)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Total Accounts</div>
                    <div class="fs-3 fw-bold">{{ $totalAccounts ?? 0 }}</div>
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
                    <div class="fs-3 fw-bold">₹{{ number_format($totalBalance ?? 0,0) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-currency-rupee"></i></div>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasFeature('loans'))
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#E65100,#F57C00)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Active Loans</div>
                    <div class="fs-3 fw-bold">{{ $activeLoans ?? 0 }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-credit-card"></i></div>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasFeature('transactions'))
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6A1B9A,#8E24AA)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Monthly Deposits</div>
                    <div class="fs-3 fw-bold">₹{{ number_format($monthlyDeposits ?? 0,0) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasFeature('collections') && isset($todayCollections))
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#00838F,#006064)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Today's Collections</div>
                    <div class="fs-3 fw-bold">₹{{ number_format($todayCollections,0) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-collection"></i></div>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasFeature('employees') && isset($totalEmployees))
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#283593,#3949AB)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="small opacity-75 mb-1">Active Employees</div>
                    <div class="fs-3 fw-bold">{{ $totalEmployees }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Quick Actions --}}
<div class="row g-2 mb-4">
    @if(auth()->user()->hasFeature('accounts'))
    <div class="col-6 col-md-3 col-lg-2">
        <a href="{{ route('accounts.create') }}" class="btn btn-primary w-100 py-3">
            <i class="bi bi-plus-circle d-block fs-4 mb-1"></i><small>New Account</small>
        </a>
    </div>
    @endif
    @if(auth()->user()->hasFeature('transactions'))
    <div class="col-6 col-md-3 col-lg-2">
        <a href="{{ route('transactions.deposit') }}" class="btn btn-success w-100 py-3">
            <i class="bi bi-arrow-down-circle d-block fs-4 mb-1"></i><small>Deposit</small>
        </a>
    </div>
    <div class="col-6 col-md-3 col-lg-2">
        <a href="{{ route('transactions.withdraw') }}" class="btn btn-warning w-100 py-3 text-dark">
            <i class="bi bi-arrow-up-circle d-block fs-4 mb-1"></i><small>Withdraw</small>
        </a>
    </div>
    <div class="col-6 col-md-3 col-lg-2">
        <a href="{{ route('transactions.transfer') }}" class="btn btn-outline-primary w-100 py-3">
            <i class="bi bi-arrow-left-right d-block fs-4 mb-1"></i><small>Transfer</small>
        </a>
    </div>
    @endif
    @if(auth()->user()->hasFeature('loans'))
    <div class="col-6 col-md-3 col-lg-2">
        <a href="{{ route('loans.create') }}" class="btn btn-info w-100 py-3 text-white">
            <i class="bi bi-file-earmark-plus d-block fs-4 mb-1"></i><small>Apply Loan</small>
        </a>
    </div>
    @endif
    @if(auth()->user()->hasFeature('customers'))
    <div class="col-6 col-md-3 col-lg-2">
        <a href="{{ route('customers.create') }}" class="btn btn-outline-success w-100 py-3">
            <i class="bi bi-person-plus d-block fs-4 mb-1"></i><small>New Customer</small>
        </a>
    </div>
    @endif
</div>

<div class="row g-3">
    {{-- Recent Transactions --}}
    @if(auth()->user()->hasFeature('transactions') && isset($recentTransactions))
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
                            <tr><th>Reference</th><th>Account</th><th>Type</th><th>Amount</th><th>Date</th><th></th></tr>
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
                                <td><a href="{{ route('print.receipt',$txn) }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="font-size:.72rem;padding:.2rem .5rem"><i class="bi bi-printer"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No transactions yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Accounts + Alerts --}}
    <div class="{{ auth()->user()->hasFeature('transactions') && isset($recentTransactions) ? 'col-lg-4' : 'col-12' }}">
        @if(auth()->user()->hasFeature('accounts') && isset($recentAccounts))
        <div class="card mb-3">
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
        @endif

        @if(auth()->user()->hasFeature('loans') && !empty($pendingLoans) && $pendingLoans > 0)
        <div class="alert alert-warning d-flex align-items-center">
            <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
            <div><strong>{{ $pendingLoans }}</strong> loan(s) pending approval. <a href="{{ route('loans.index') }}?status=pending">Review now</a></div>
        </div>
        @endif

        {{-- Role info card for non-admin --}}
        @if(!auth()->user()->isAdmin())
        <div class="card border-0" style="background:linear-gradient(135deg,#f8faff,#e8f0fe)">
            <div class="card-body">
                <div class="fw-semibold small mb-2"><i class="bi bi-person-badge me-1 text-primary"></i>Your Role & Access</div>
                <div class="text-muted small">
                    You are logged in as <strong>{{ ucfirst(str_replace('_',' ', auth()->user()->role ?? 'user')) }}</strong>.
                    Contact your administrator if you need additional access.
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
