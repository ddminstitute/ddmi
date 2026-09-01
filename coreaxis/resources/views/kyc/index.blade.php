@extends('layouts.banking')
@section('title','KYC Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2 text-primary"></i>KYC Verification</h5>
</div>
<div class="row g-3 mb-3">
    @foreach([['pending','warning','hourglass-split'],['verified','success','shield-check'],['rejected','danger','x-circle'],['expired','secondary','clock-history']] as [$s,$c,$i])
    <div class="col-6 col-md-3"><div class="stat-card bg-{{ $c }}"><div class="d-flex justify-content-between align-items-center"><div><div class="small opacity-75">{{ ucfirst($s) }}</div><div class="fs-3 fw-bold">{{ $stats[$s] }}</div></div><div class="stat-icon"><i class="bi bi-{{ $i }}"></i></div></div></div></div>
    @endforeach
</div>
<div class="card mb-3"><div class="card-body py-2">
<form class="row g-2" method="GET">
    <div class="col-md-3"><select name="kyc_status" class="form-select form-select-sm">
        <option value="">All Status</option>
        @foreach(['pending','verified','rejected','expired'] as $s)<option value="{{ $s }}" {{ request('kyc_status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach
    </select></div>
    <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button> <a href="{{ route('kyc.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a></div>
</form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Customer</th><th>PAN</th><th>KYC Status</th><th>Verified At</th><th>Expiry</th><th>Remarks</th><th>Action</th></tr></thead>
    <tbody>
    @forelse($customers as $c)
    <tr>
        <td><a href="{{ route('customers.show',$c) }}" class="text-decoration-none fw-semibold">{{ $c->name }}</a><br><small class="text-muted">{{ $c->phone }}</small></td>
        <td><code>{{ $c->pan_number ?? '—' }}</code></td>
        <td>
            @php $badge=['pending'=>'warning','verified'=>'success','rejected'=>'danger','expired'=>'secondary'][$c->kyc_status]??'secondary' @endphp
            <span class="badge bg-{{ $badge }}">{{ ucfirst($c->kyc_status) }}</span>
        </td>
        <td>{{ $c->kyc_verified_at?->format('d M Y') ?? '—' }}</td>
        <td>{{ $c->kyc_expiry_date?->format('d M Y') ?? '—' }}</td>
        <td><small>{{ $c->kyc_remarks ?? '—' }}</small></td>
        <td>
            @if(in_array($c->kyc_status,['pending','rejected','expired']))
            <button class="btn btn-xs btn-success" style="font-size:.75rem;padding:2px 8px"
                data-bs-toggle="modal" data-bs-target="#kycModal{{ $c->id }}">Verify / Reject</button>
            @endif
            <div class="modal fade" id="kycModal{{ $c->id }}" tabindex="-1">
                <div class="modal-dialog"><div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">KYC Action — {{ $c->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <form method="POST" action="{{ route('kyc.verify',$c) }}">@csrf
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Action</label>
                        <select name="action" class="form-select" required>
                            <option value="verified">✅ Verify KYC</option>
                            <option value="rejected">❌ Reject KYC</option>
                        </select></div>
                        <div class="mb-3"><label class="form-label">Remarks</label><textarea name="kyc_remarks" class="form-control" rows="2">{{ $c->kyc_remarks }}</textarea></div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Submit</button></div>
                    </form>
                </div></div>
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted py-4">No customers found</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($customers->hasPages())<div class="card-footer">{{ $customers->links() }}</div>@endif</div>
@endsection
