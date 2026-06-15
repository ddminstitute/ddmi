@extends('layouts.banking')
@section('title','Accounts')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold">Bank Accounts</h5>
    <a href="{{ route('accounts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Open Account</a>
</div>
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2" method="GET">
            <div class="col-md-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search account or customer..." value="{{ request('search') }}"></div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="savings" {{ request('type')=='savings'?'selected':'' }}>Savings</option>
                    <option value="checking" {{ request('type')=='checking'?'selected':'' }}>Checking</option>
                    <option value="current" {{ request('type')=='current'?'selected':'' }}>Current</option>
                    <option value="fixed_deposit" {{ request('type')=='fixed_deposit'?'selected':'' }}>Fixed Deposit</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                    <option value="frozen" {{ request('status')=='frozen'?'selected':'' }}>Frozen</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('accounts.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Account No.</th><th>Customer</th><th>Type</th><th>Balance</th><th>Currency</th><th>Status</th><th>Opened</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($accounts as $acc)
                    <tr>
                        <td><code>{{ $acc->account_number }}</code></td>
                        <td>{{ $acc->user->name }}<div class="text-muted" style="font-size:.75rem">{{ $acc->user->email }}</div></td>
                        <td>{{ $acc->getTypeLabel() }}</td>
                        <td class="fw-semibold text-success">${{ number_format($acc->balance,2) }}</td>
                        <td>{{ $acc->currency }}</td>
                        <td><span class="badge bg-{{ $acc->status==='active'?'success':($acc->status==='frozen'?'warning text-dark':'secondary') }}">{{ ucfirst($acc->status) }}</span></td>
                        <td class="text-muted small">{{ $acc->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('accounts.show',$acc) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('accounts.edit',$acc) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">No accounts found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($accounts->hasPages())
    <div class="card-footer">{{ $accounts->links() }}</div>
    @endif
</div>
@endsection
