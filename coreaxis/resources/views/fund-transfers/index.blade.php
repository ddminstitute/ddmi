@extends('layouts.banking')
@section('title','Fund Transfers')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-send me-2 text-primary"></i>NEFT / RTGS / IMPS Transfers</h5>
    <a href="{{ route('fund-transfers.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Initiate Transfer</a>
</div>
<div class="card mb-3"><div class="card-body py-2"><form class="row g-2" method="GET">
    <div class="col-md-3"><select name="status" class="form-select form-select-sm"><option value="">All Status</option>@foreach(['pending','processing','completed','failed','reversed'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
    <div class="col-md-3"><select name="mode" class="form-select form-select-sm"><option value="">All Modes</option>@foreach(['neft','rtgs','imps','upi'] as $m)<option value="{{ $m }}" {{ request('mode')===$m?'selected':'' }}>{{ strtoupper($m) }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button></div>
</form></div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Reference</th><th>Account</th><th>Mode</th><th>Beneficiary</th><th>Amount</th><th>Charges</th><th>Status</th><th>Date</th><th></th></tr></thead>
    <tbody>
    @forelse($transfers as $t)
    <tr>
        <td><code class="small">{{ $t->reference_number }}</code><br><small class="text-muted">{{ $t->bank_reference ?? '—' }}</small></td>
        <td>{{ $t->account?->account_number }}</td>
        <td><span class="badge bg-dark">{{ strtoupper($t->transfer_mode) }}</span></td>
        <td>{{ $t->beneficiary_name }}<br><code class="small">{{ $t->beneficiary_ifsc }}</code></td>
        <td class="fw-semibold text-danger">₹{{ number_format($t->amount,2) }}</td>
        <td class="small text-muted">₹{{ number_format($t->charges,2) }}</td>
        <td><span class="badge bg-{{ $t->getStatusBadge() }}">{{ ucfirst($t->status) }}</span></td>
        <td class="small text-muted">{{ $t->created_at->format('d M Y H:i') }}</td>
        <td>
            @if(in_array($t->status,['pending','processing']))
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ftModal{{ $t->id }}">Update</button>
            <div class="modal fade" id="ftModal{{ $t->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Update Transfer Status</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <form method="POST" action="{{ route('fund-transfers.update-status',$t) }}">@csrf
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select" required><option value="completed">Completed</option><option value="failed">Failed</option><option value="reversed">Reversed</option></select></div>
                    <div class="mb-3"><label class="form-label">Bank Reference / UTR</label><input type="text" name="bank_reference" class="form-control" placeholder="UTR number from bank"></div>
                    <div class="mb-3"><label class="form-label">Failure Reason (if failed)</label><input type="text" name="failure_reason" class="form-control"></div>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div>
                </form>
            </div></div></div>
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="9" class="text-center text-muted py-4">No transfers found</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($transfers->hasPages())<div class="card-footer">{{ $transfers->links() }}</div>@endif</div>
@endsection
