@extends('layouts.banking')
@section('title','Grievance Details')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('grievances.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Grievance — {{ $grievance->ticket_number }}</h5>
    <span class="badge bg-{{ $grievance->getStatusBadge() }} ms-2">{{ ucwords(str_replace('_',' ',$grievance->status)) }}</span>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="row">
<div class="col-lg-7">
<div class="card mb-3"><div class="card-header fw-semibold">Complaint Details</div><div class="card-body">
    <h6 class="fw-bold">{{ $grievance->subject }}</h6>
    <p>{{ $grievance->description }}</p>
    <div class="row g-2 mt-2">
        <div class="col-6 col-md-4"><div class="text-muted small">Customer</div><div>{{ $grievance->customer?->name ?? '—' }}</div></div>
        <div class="col-6 col-md-4"><div class="text-muted small">Category</div><div>{{ ucfirst($grievance->category) }}</div></div>
        <div class="col-6 col-md-4"><div class="text-muted small">Priority</div><div>{{ ucfirst($grievance->priority) }}</div></div>
        <div class="col-6 col-md-4"><div class="text-muted small">Raised By</div><div>{{ $grievance->reportedBy?->name ?? '—' }}</div></div>
        <div class="col-6 col-md-4"><div class="text-muted small">SLA Due</div><div class="{{ $grievance->sla_due_date?->isPast() ? 'text-danger fw-bold' : '' }}">{{ $grievance->sla_due_date?->format('d M Y') ?? '—' }}</div></div>
        <div class="col-6 col-md-4"><div class="text-muted small">Raised On</div><div>{{ $grievance->created_at->format('d M Y H:i') }}</div></div>
    </div>
    @if($grievance->resolution_notes)<div class="mt-3 p-3 bg-success bg-opacity-10 rounded border border-success"><strong>Resolution:</strong> {{ $grievance->resolution_notes }}</div>@endif
</div></div>
</div>
<div class="col-lg-5">
<div class="card"><div class="card-header fw-semibold">Update Status</div><div class="card-body">
<form method="POST" action="{{ route('grievances.update',$grievance) }}">@csrf @method('PATCH')
<div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['open','in_progress','resolved','closed','escalated'] as $s)<option value="{{ $s }}" {{ $grievance->status===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Assign To</label><select name="assigned_to" class="form-select"><option value="">— Unassigned —</option>@foreach($staff as $u)<option value="{{ $u->id }}" {{ $grievance->assigned_to===$u->id?'selected':'' }}>{{ $u->name }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Resolution Notes</label><textarea name="resolution_notes" class="form-control" rows="3">{{ $grievance->resolution_notes }}</textarea></div>
<button type="submit" class="btn btn-primary w-100">Update Grievance</button>
</form>
</div></div>
</div>
</div>
@endsection
