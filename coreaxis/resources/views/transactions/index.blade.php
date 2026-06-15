@extends('layouts.banking')
@section('title','Transactions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold">All Transactions</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.deposit') }}" class="btn btn-sm btn-success">Deposit</a>
        <a href="{{ route('transactions.withdraw') }}" class="btn btn-sm btn-warning text-dark">Withdraw</a>
        <a href="{{ route('transactions.transfer') }}" class="btn btn-sm btn-primary">Transfer</a>
    </div>
</div>
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2" method="GET">
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="deposit" {{ request('type')=='deposit'?'selected':'' }}>Deposit</option>
                    <option value="withdrawal" {{ request('type')=='withdrawal'?'selected':'' }}>Withdrawal</option>
                    <option value="transfer_in" {{ request('type')=='transfer_in'?'selected':'' }}>Transfer In</option>
                    <option value="transfer_out" {{ request('type')=='transfer_out'?'selected':'' }}>Transfer Out</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="account_id" class="form-select form-select-sm">
                    <option value="">All Accounts</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('account_id')==$acc->id?'selected':'' }}>{{ $acc->account_number }} — {{ $acc->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From"></div>
            <div class="col-md-2"><input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To"></div>
            <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button> <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Reference</th><th>Account</th><th>Customer</th><th>Type</th><th>Amount</th><th>Balance After</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr>
                        <td><code class="small">{{ $txn->reference_number }}</code></td>
                        <td>{{ $txn->account->account_number }}</td>
                        <td>{{ $txn->account->user->name }}</td>
                        <td><span class="badge bg-{{ $txn->getTypeBadge() }}">{{ $txn->getTypeLabel() }}</span></td>
                        <td class="fw-semibold {{ in_array($txn->transaction_type,['deposit','transfer_in'])?'text-success':'text-danger' }}">
                            {{ in_array($txn->transaction_type,['deposit','transfer_in'])?'+':'-' }}₹{{ number_format($txn->amount,2) }}
                        </td>
                        <td>₹{{ number_format($txn->balance_after,2) }}</td>
                        <td class="text-muted small">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('transactions.show',$txn) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('print.receipt',$txn) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">No transactions found</td></tr>
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
