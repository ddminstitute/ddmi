@extends('layouts.banking')
@section('title','Grievances')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-chat-square-text me-2 text-primary"></i>Grievance Management</h5>
    <a href="{{ route('grievances.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Raise Grievance</a>
</div>
<div class="row g-3 mb-3">
    @foreach([['open','danger','exclamation-circle'],['in_progress','warning','arrow-repeat'],['resolved','success','check-circle'],['escalated','dark','arrow-up-circle']] as [$s,$c,$i])
    <div class="col-6 col-md-3"><div class="stat-card bg-{{ $c }}"><div class="d-flex justify-content-between align-items-center"><div><div class="small opacity-75">{{ ucwords(str_replace('_',' ',$s)) }}</div><div class="fs-3 fw-bold">{{ $stats[$s] }}</div></div><div class="stat-icon"><i class="bi bi-{{ $i }}"></i></div></div></div></div>
    @endforeach
</div>
<div class="card mb-3"><div class="card-body py-2"><form class="row g-2" method="GET">
    <div class="col-md-3"><select name="status" class="form-select form-select-sm"><option value="">All Status</option>@foreach(['open','in_progress','resolved','closed','escalated'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
    <div class="col-md-3"><select name="priority" class="form-select form-select-sm"><option value="">All Priority</option>@foreach(['low','medium','high','urgent'] as $p)<option value="{{ $p }}" {{ request('priority')===$p?'selected':'' }}>{{ ucfirst($p) }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button></div>
</form></div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Ticket</th><th>Customer</th><th>Subject</th><th>Category</th><th>Priority</th><th>SLA Due</th><th>Status</th><th>Assigned</th><th></th></tr></thead>
    <tbody>
    @forelse($grievances as $g)
    <tr>
        <td><code>{{ $g->ticket_number }}</code></td>
        <td>{{ $g->customer?->name ?? '—' }}</td>
        <td class="fw-semibold" style="max-width:200px">{{ Str::limit($g->subject,40) }}</td>
        <td><span class="badge bg-secondary">{{ ucfirst($g->category) }}</span></td>
        <td>@php $pc=['low'=>'success','medium'=>'warning','high'=>'warning text-dark','urgent'=>'danger'][$g->priority]??'secondary' @endphp
            <span class="badge bg-{{ $pc }}">{{ ucfirst($g->priority) }}</span></td>
        <td class="small {{ $g->sla_due_date && $g->sla_due_date->isPast() && $g->status!=='resolved' ? 'text-danger fw-bold' : 'text-muted' }}">
            {{ $g->sla_due_date?->format('d M Y') ?? '—' }}
        </td>
        <td><span class="badge bg-{{ $g->getStatusBadge() }}">{{ ucwords(str_replace('_',' ',$g->status)) }}</span></td>
        <td class="small">{{ $g->assignedTo?->name ?? '—' }}</td>
        <td><a href="{{ route('grievances.show',$g) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
    </tr>
    @empty
    <tr><td colspan="9" class="text-center text-muted py-4">No grievances found</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($grievances->hasPages())<div class="card-footer">{{ $grievances->links() }}</div>@endif</div>
@endsection
