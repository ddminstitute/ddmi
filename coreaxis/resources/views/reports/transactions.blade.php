@extends('layouts.banking')
@section('title','Transaction Report')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Transaction Report</h5>
</div>
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2" method="GET">
            <div class="col-md-3"><input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From Date"></div>
            <div class="col-md-3"><input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To Date"></div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
                    <option value="transfer_in">Transfer In</option>
                    <option value="transfer_out">Transfer Out</option>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-sm btn-primary">Generate</button></div>
        </form>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-body text-center">
                <div class="text-muted small">Total Credits</div>
                <div class="fs-3 fw-bold text-success">${{ number_format($totals['deposits'],2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-body text-center">
                <div class="text-muted small">Total Debits</div>
                <div class="fs-3 fw-bold text-danger">${{ number_format($totals['withdrawals'],2) }}</div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">{{ $transactions->total() }} transactions found</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Reference</th><th>Account</th><th>Type</th><th>Amount</th><th>Date</th></tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr>
                        <td><code class="small">{{ $txn->reference_number }}</code></td>
                        <td>{{ $txn->account->account_number }}</td>
                        <td><span class="badge bg-{{ $txn->getTypeBadge() }}">{{ $txn->getTypeLabel() }}</span></td>
                        <td class="{{ in_array($txn->transaction_type,['deposit','transfer_in'])?'text-success':'text-danger' }} fw-semibold">
                            {{ in_array($txn->transaction_type,['deposit','transfer_in'])?'+':'-' }}${{ number_format($txn->amount,2) }}
                        </td>
                        <td class="text-muted small">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No transactions found for selected criteria</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transactions->hasPages())<div class="card-footer">{{ $transactions->links() }}</div>@endif
</div>
@endsection
