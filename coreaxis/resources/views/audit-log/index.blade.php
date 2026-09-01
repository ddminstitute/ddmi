@extends('layouts.banking')
@section('title','Audit Log')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>Audit Trail / Activity Log</h5>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover table-sm mb-0">
    <thead class="table-light"><tr><th>Time</th><th>User</th><th>Action</th><th>Description</th><th>Model</th><th>IP</th></tr></thead>
    <tbody>
    @forelse($logs as $log)
    <tr>
        <td class="small text-muted" style="white-space:nowrap">{{ $log->created_at->format('d M H:i') }}</td>
        <td class="small fw-semibold">{{ $log->user?->name ?? 'System' }}</td>
        <td>
            @php $ac=['created'=>'success','updated'=>'warning','deleted'=>'danger','login'=>'info','logout'=>'secondary'][$log->action]??'secondary' @endphp
            <span class="badge bg-{{ $ac }}">{{ ucfirst($log->action) }}</span>
        </td>
        <td style="max-width:350px;font-size:.8rem">{{ $log->description }}</td>
        <td class="small text-muted">{{ $log->model_type }}{{ $log->model_id ? ' #'.$log->model_id : '' }}</td>
        <td class="small text-muted font-monospace">{{ $log->ip_address }}</td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center text-muted py-4">No activity recorded yet</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($logs->hasPages())<div class="card-footer">{{ $logs->links() }}</div>@endif</div>
@endsection
