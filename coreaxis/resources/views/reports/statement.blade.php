@extends('layouts.banking')
@section('title','Account Statement')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Account Statement</h5>
</div>
<div class="card mb-3">
    <div class="card-body">
        <form class="row g-2" method="GET">
            <div class="col-md-4">
                <select name="account_id" class="form-select" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('account_id')==$acc->id?'selected':'' }}>{{ $acc->account_number }} — {{ $acc->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}"></div>
            <div class="col-md-3"><input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}"></div>
            <div class="col-auto"><button class="btn btn-primary">Generate Statement</button></div>
        </form>
    </div>
</div>
@if($account)
<div class="card mb-3">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <span><strong>{{ $account->account_number }}</strong> — {{ $account->user->name }}</span>
        <span>Balance: ${{ number_format($account->balance,2) }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>Date</th><th>Reference</th><th>Description</th><th>Debit</th><th>Credit</th><th>Balance</th></tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr>
                        <td class="small">{{ $txn->created_at->format('M d, Y') }}</td>
                        <td><code class="small">{{ $txn->reference_number }}</code></td>
                        <td class="small">{{ $txn->description }}</td>
                        <td class="text-danger">{{ in_array($txn->transaction_type,['withdrawal','transfer_out']) ? '$'.number_format($txn->amount,2) : '' }}</td>
                        <td class="text-success">{{ in_array($txn->transaction_type,['deposit','transfer_in']) ? '$'.number_format($txn->amount,2) : '' }}</td>
                        <td class="fw-semibold">${{ number_format($txn->balance_after,2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No transactions for selected period</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transactions->hasPages())<div class="card-footer">{{ $transactions->links() }}</div>@endif
</div>
@endif
@endsection
