@extends('layouts.banking')
@section('title','Journal Entries')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Journal Entries</h5>
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label small mb-1">From Date</label><input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm"></div>
        <div class="col-md-3"><label class="form-label small mb-1">To Date</label><input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm"></div>
        <div class="col-md-3"><label class="form-label small mb-1">Source</label><select name="source_type" class="form-select form-select-sm"><option value="">All</option><option value="transaction" {{ request('source_type')=='transaction'?'selected':'' }}>Transaction</option><option value="loan" {{ request('source_type')=='loan'?'selected':'' }}>Loan</option><option value="loan_payment" {{ request('source_type')=='loan_payment'?'selected':'' }}>Loan Payment</option><option value="fixed_deposit" {{ request('source_type')=='fixed_deposit'?'selected':'' }}>Fixed Deposit</option></select></div>
        <div class="col-md-3"><button class="btn btn-primary btn-sm w-100">Filter</button></div>
    </form>
</div></div>
<div class="card"><div class="card-body p-0">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Entry #</th><th>Date</th><th>Narration</th><th>Source</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th>OK</th><th></th></tr></thead>
        <tbody>
            @forelse($entries as $entry)
            <tr>
                <td><code class="small">{{ $entry->entry_number }}</code></td>
                <td class="text-muted small">{{ $entry->entry_date->format('d M Y') }}</td>
                <td>{{ Str::limit($entry->narration, 50) }}</td>
                <td><span class="badge bg-secondary">{{ $entry->source_type }}</span></td>
                <td class="text-end fw-semibold text-primary">₹{{ number_format($entry->total_debit,2) }}</td>
                <td class="text-end fw-semibold text-success">₹{{ number_format($entry->total_credit,2) }}</td>
                <td>@if($entry->is_balanced)<span class="badge bg-success">✓</span>@else<span class="badge bg-danger">!</span>@endif</td>
                <td><a href="{{ route('gl.entries.show', $entry) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.72rem;padding:.2rem .5rem">View</a></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-5">No journal entries yet. Entries are created automatically on deposits, withdrawals, and loan payments.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($entries->hasPages())<div class="card-footer">{{ $entries->links() }}</div>@endif
</div>
@endsection
