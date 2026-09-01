@extends('layouts.banking')
@section('title','Demand Drafts')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Demand Drafts / Pay Orders</h5>
    <a href="{{ route('demand-drafts.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Issue DD/PO</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>DD Number</th><th>Type</th><th>Account</th><th>Payee</th><th>Amount</th><th>Charges</th><th>Issue Date</th><th>Valid Until</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse($dds as $dd)
    <tr>
        <td><code>{{ $dd->dd_number }}</code></td>
        <td><span class="badge bg-dark">{{ ucwords(str_replace('_',' ',$dd->instrument_type)) }}</span></td>
        <td>{{ $dd->account?->account_number }}</td>
        <td>{{ $dd->payee_name }}<br><small class="text-muted">{{ $dd->payable_at_city }}</small></td>
        <td class="fw-semibold">₹{{ number_format($dd->amount,2) }}</td>
        <td class="text-muted small">₹{{ number_format($dd->charges,2) }}</td>
        <td>{{ $dd->issue_date->format('d M Y') }}</td>
        <td class="{{ $dd->valid_until && $dd->valid_until->isPast() ? 'text-danger' : '' }}">{{ $dd->valid_until?->format('d M Y') ?? '—' }}</td>
        <td><span class="badge bg-{{ $dd->status==='active'?'success':($dd->status==='cancelled'?'danger':'secondary') }}">{{ ucfirst($dd->status) }}</span></td>
        <td>
        @if($dd->status === 'active')
        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#ddModal{{ $dd->id }}">Cancel</button>
        <div class="modal fade" id="ddModal{{ $dd->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Cancel DD {{ $dd->dd_number }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="{{ route('demand-drafts.cancel',$dd) }}">@csrf
            <div class="modal-body">
                <div class="alert alert-info">Principal ₹{{ number_format($dd->amount,2) }} will be refunded. Charges of ₹{{ number_format($dd->charges,2) }} are non-refundable.</div>
                <div class="mb-3"><label class="form-label">Cancellation Reason *</label><input type="text" name="cancellation_reason" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">No</button><button type="submit" class="btn btn-danger">Cancel DD</button></div>
            </form>
        </div></div></div>
        @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="10" class="text-center text-muted py-4">No demand drafts found</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($dds->hasPages())<div class="card-footer">{{ $dds->links() }}</div>@endif</div>
@endsection
