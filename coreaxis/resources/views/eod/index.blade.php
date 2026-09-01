@extends('layouts.banking')
@section('title','End of Day')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar2-check me-2 text-primary"></i>End of Day (EOD) Processing</h5>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<div class="row mb-4">
<div class="col-lg-5">
<div class="card border-primary"><div class="card-header bg-primary text-white fw-semibold">Process Today's EOD</div>
<div class="card-body">
@if($alreadyDone)
<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i>EOD for today ({{ $today }}) has already been processed.</div>
@else
<p class="text-muted mb-3">Running EOD will:</p>
<ul class="small mb-3">
    <li>Mark overdue EMIs and calculate penalties</li>
    <li>Post monthly interest on Fixed Deposits</li>
    <li>Generate day-book summary</li>
    <li>Update NPA classification (90+ day loans)</li>
</ul>
<form method="POST" action="{{ route('eod.process') }}">@csrf
    <input type="hidden" name="business_date" value="{{ $today }}">
    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Process EOD for {{ $today }}? This cannot be undone.')">
        <i class="bi bi-play-circle me-2"></i>Run EOD for {{ $today }}
    </button>
</form>
@endif
</div></div>
</div>
<div class="col-lg-7">
<div class="card"><div class="card-header fw-semibold">EOD History</div>
<div class="card-body p-0"><div class="table-responsive">
<table class="table table-sm mb-0">
    <thead class="table-light"><tr><th>Date</th><th>Deposits</th><th>Withdrawals</th><th>Txns</th><th>Overdue EMIs</th><th>Interest Posted</th><th>By</th></tr></thead>
    <tbody>
    @forelse($records as $r)
    <tr>
        <td class="fw-semibold">{{ $r->business_date->format('d M Y') }}</td>
        <td class="text-success">₹{{ number_format($r->total_deposits,0) }}</td>
        <td class="text-danger">₹{{ number_format($r->total_withdrawals,0) }}</td>
        <td>{{ $r->transactions_count }}</td>
        <td>{{ $r->emis_marked_overdue }}</td>
        <td>₹{{ number_format($r->interest_posted,2) }}</td>
        <td class="small text-muted">{{ $r->processedBy?->name ?? '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted py-3">No EOD records yet</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($records->hasPages())<div class="card-footer">{{ $records->links() }}</div>@endif</div>
</div>
</div>
@endsection
