@extends('layouts.portal')
@section('title','Grievances')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-chat-square-text me-2 text-primary"></i>Grievances</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#raiseModal"><i class="bi bi-plus me-1"></i>Raise Grievance</button>
</div>
<div class="card"><div class="card-body p-0">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Date</th><th>Category</th><th>Subject</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($grievances as $g)
            <tr><td class="small text-muted">{{ $g->created_at->format('d M Y') }}</td><td><span class="badge bg-secondary">{{ ucfirst($g->category) }}</span></td><td>{{ $g->subject }}</td><td><span class="badge bg-{{ $g->status==='open'?'warning text-dark':($g->status==='resolved'?'success':'secondary') }}">{{ ucfirst($g->status) }}</span></td></tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No grievances submitted yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($grievances->hasPages())<div class="card-footer">{{ $grievances->links() }}</div>@endif</div>
<div class="modal fade" id="raiseModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Raise a Grievance</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="{{ route('portal.grievances.store') }}">@csrf
        <div class="modal-body row g-3">
            <div class="col-12"><label class="form-label">Category <span class="text-danger">*</span></label><select name="category" class="form-select" required><option value="account">Account</option><option value="loan">Loan</option><option value="service">Service</option><option value="other">Other</option></select></div>
            <div class="col-12"><label class="form-label">Subject <span class="text-danger">*</span></label><input type="text" name="subject" class="form-control" required maxlength="150"></div>
            <div class="col-12"><label class="form-label">Description <span class="text-danger">*</span></label><textarea name="description" class="form-control" rows="4" required maxlength="1000"></textarea></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Submit</button></div>
    </form>
</div></div></div>
@endsection
