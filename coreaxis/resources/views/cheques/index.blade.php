@extends('layouts.banking')
@section('title','Cheque Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-check2-square me-2 text-primary"></i>Cheque Management</h5>
    <a href="{{ route('cheques.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Record Cheque</a>
</div>
<div class="card mb-3"><div class="card-body py-2"><form class="row g-2" method="GET">
    <div class="col-md-3"><select name="status" class="form-select form-select-sm"><option value="">All Status</option>
    @foreach(['pending','cleared','bounced','cancelled'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach
    </select></div>
    <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button></div>
</form></div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Cheque #</th><th>Account</th><th>Type</th><th>Drawer</th><th>Amount</th><th>Cheque Date</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse($cheques as $ch)
    <tr>
        <td><code>{{ $ch->cheque_number }}</code></td>
        <td>{{ $ch->account?->account_number }}</td>
        <td><span class="badge bg-{{ $ch->cheque_type==='received'?'info':'secondary' }}">{{ ucfirst($ch->cheque_type) }}</span></td>
        <td>{{ $ch->drawer_name ?? $ch->drawee_bank }}</td>
        <td class="fw-semibold">₹{{ number_format($ch->amount,2) }}</td>
        <td>{{ $ch->cheque_date->format('d M Y') }}</td>
        <td><span class="badge bg-{{ $ch->getStatusBadge() }}">{{ ucfirst($ch->status) }}</span></td>
        <td>
        @if($ch->status === 'pending')
        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#chqModal{{ $ch->id }}">Update</button>
        <div class="modal fade" id="chqModal{{ $ch->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Update Cheque Status</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="{{ route('cheques.update-status',$ch) }}">@csrf
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select" required><option value="cleared">Cleared</option><option value="bounced">Bounced</option><option value="cancelled">Cancelled</option></select></div>
                <div class="mb-3"><label class="form-label">Clearing Date</label><input type="date" name="clearing_date" class="form-control" value="{{ date('Y-m-d') }}"></div>
                <div class="mb-3"><label class="form-label">Bounce Reason (if bounced)</label><input type="text" name="bounce_reason" class="form-control"></div>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div>
            </form>
        </div></div></div>
        @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted py-4">No cheques found</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($cheques->hasPages())<div class="card-footer">{{ $cheques->links() }}</div>@endif</div>
@endsection
