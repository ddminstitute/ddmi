@extends('layouts.portal')
@section('title','Dashboard')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card text-white" style="background:linear-gradient(135deg,#1565C0,#1976D2)"><div class="card-body"><div class="opacity-75 small mb-1">Total Balance</div><div class="display-6 fw-bold">₹{{ number_format($totalBalance,2) }}</div><div class="opacity-75 small mt-1">{{ $accounts->count() }} account(s)</div></div></div></div>
    <div class="col-md-4"><div class="card text-white" style="background:linear-gradient(135deg,#E65100,#F57C00)"><div class="card-body"><div class="opacity-75 small mb-1">Active Loans</div><div class="display-6 fw-bold">{{ $loans->count() }}</div><div class="opacity-75 small mt-1">Outstanding: ₹{{ number_format($loans->sum('outstanding_amount'),2) }}</div></div></div></div>
    <div class="col-md-4"><div class="card text-white" style="background:linear-gradient(135deg,#2E7D32,#388E3C)"><div class="card-body"><div class="opacity-75 small mb-1">Active FDs</div><div class="display-6 fw-bold">{{ $fds->count() }}</div><div class="opacity-75 small mt-1">Invested: ₹{{ number_format($fds->sum('principal_amount'),2) }}</div></div></div></div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-wallet2 me-2 text-primary"></i>My Accounts</div>
            <div class="card-body p-0"><table class="table table-hover mb-0"><tbody>
                @forelse($accounts as $acc)
                <tr><td><div class="fw-semibold small">{{ $acc->account_number }}</div><div class="text-muted" style="font-size:.78rem">{{ $acc->getTypeLabel() }}</div></td><td class="text-end"><div class="fw-bold">₹{{ number_format($acc->balance,2) }}</div><span class="badge bg-{{ $acc->status==='active'?'success':'secondary' }}">{{ ucfirst($acc->status) }}</span></td></tr>
                @empty
                <tr><td class="text-muted text-center py-4">No accounts found.</td></tr>
                @endforelse
            </tbody></table></div>
            <div class="card-footer text-end"><a href="{{ route('portal.accounts') }}" class="btn btn-sm btn-outline-primary">View All</a></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-cash-coin me-2 text-warning"></i>Active Loans</div>
            <div class="card-body p-0"><table class="table table-hover mb-0"><tbody>
                @forelse($loans as $loan)
                <tr><td><div class="fw-semibold small">{{ $loan->loan_number }}</div><div class="text-muted" style="font-size:.78rem">{{ $loan->getTypeLabel() }}</div></td><td class="text-end"><div class="fw-bold text-danger">₹{{ number_format($loan->outstanding_amount,2) }}</div><div class="text-muted" style="font-size:.75rem">EMI: ₹{{ number_format($loan->monthly_emi,2) }}</div></td></tr>
                @empty
                <tr><td class="text-muted text-center py-4">No active loans.</td></tr>
                @endforelse
            </tbody></table></div>
            <div class="card-footer text-end"><a href="{{ route('portal.loans') }}" class="btn btn-sm btn-outline-warning">View All</a></div>
        </div>
    </div>
</div>
@endsection
