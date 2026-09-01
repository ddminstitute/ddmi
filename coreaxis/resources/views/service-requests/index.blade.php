@extends('layouts.banking')
@section('title','Service Requests')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard-check me-2 text-primary"></i>Service Requests</h5>
    <a href="{{ route('service-requests.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>New Request</a>
</div>
<div class="card mb-3"><div class="card-body py-2"><form class="row g-2" method="GET"><div class="col-md-3"><select name="status" class="form-select form-select-sm"><option value="">All Status</option>@foreach(['pending','approved','completed','rejected'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div><div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button></div></form></div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Request #</th><th>Customer</th><th>Type</th><th>Account</th><th>Status</th><th>Raised</th><th>Action</th></tr></thead>
    <tbody>
    @forelse($requests as $sr)
    <tr>
        <td><code>{{ $sr->request_number }}</code></td>
        <td>{{ $sr->customer?->name ?? '—' }}</td>
        <td><span class="badge bg-secondary">{{ ucwords(str_replace('_',' ',$sr->request_type)) }}</span></td>
        <td>{{ $sr->account?->account_number ?? '—' }}</td>
        <td><span class="badge bg-{{ $sr->getStatusBadge() }}">{{ ucfirst($sr->status) }}</span></td>
        <td class="small text-muted">{{ $sr->created_at->format('d M Y') }}</td>
        <td>
        @if($sr->status === 'pending')
        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#srModal{{ $sr->id }}">Process</button>
        <div class="modal fade" id="srModal{{ $sr->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Process Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><p><strong>Type:</strong> {{ ucwords(str_replace('_',' ',$sr->request_type)) }}</p><p>{{ $sr->details }}</p>
            <form method="POST" action="{{ route('service-requests.process',$sr) }}">@csrf
            <div class="mb-3"><label class="form-label">Decision</label><select name="status" class="form-select" required><option value="approved">Approve</option><option value="completed">Complete</option><option value="rejected">Reject</option></select></div>
            <div class="mb-3"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
            <button type="submit" class="btn btn-primary w-100">Submit Decision</button>
            </form></div>
        </div></div></div>
        @else
        <small class="text-muted">{{ $sr->remarks ?? '—' }}</small>
        @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted py-4">No service requests found</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($requests->hasPages())<div class="card-footer">{{ $requests->links() }}</div>@endif</div>
@endsection
