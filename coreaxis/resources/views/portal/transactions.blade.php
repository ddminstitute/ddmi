@extends('layouts.portal')
@section('title','Transactions')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Transaction History</h5>
<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2 align-items-end">
        <div class="col-md-4"><label class="form-label small mb-1">Account</label><select name="account_id" class="form-select form-select-sm" required><option value="">Select account...</option>@foreach($accounts as $acc)<option value="{{ $acc->id }}" {{ request('account_id')==$acc->id?'selected':'' }}>{{ $acc->account_number }} ({{ $acc->getTypeLabel() }})</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label small mb-1">From</label><input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm"></div>
        <div class="col-md-3"><label class="form-label small mb-1">To</label><input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm"></div>
        <div class="col-md-2"><button class="btn btn-primary btn-sm w-100">View</button></div>
    </form>
</div></div>
@if($selectedAccount)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center"><span>{{ $selectedAccount->account_number }}</span><span class="fw-bold">Balance: ₹{{ number_format($selectedAccount->balance,2) }}</span></div>
    <div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Date</th><th>Reference</th><th>Type</th><th>Description</th><th class="text-end">Amount</th><th class="text-end">Balance</th></tr></thead>
        <tbody>
            @forelse($transactions as $txn)
            <tr>
                <td class="small text-muted">{{ $txn->created_at->format('d M Y') }}</td>
                <td><code class="small">{{ $txn->reference_number }}</code></td>
                <td><span class="badge bg-{{ $txn->getTypeBadge() }}">{{ $txn->getTypeLabel() }}</span></td>
                <td class="small">{{ $txn->description }}</td>
                <td class="text-end fw-semibold {{ in_array($txn->transaction_type,['deposit','transfer_in'])?'text-success':'text-danger' }}">{{ in_array($txn->transaction_type,['deposit','transfer_in'])?'+':'-' }}₹{{ number_format($txn->amount,2) }}</td>
                <td class="text-end small">₹{{ number_format($txn->balance_after,2) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No transactions found.</td></tr>
            @endforelse
        </tbody>
    </table></div></div>
    @if($transactions->hasPages())<div class="card-footer">{{ $transactions->links() }}</div>@endif
</div>
@endif
@endsection
