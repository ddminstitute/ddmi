@extends('layouts.banking')
@section('title','Fixed Deposits')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-safe2 me-2 text-primary"></i>Fixed Deposits</h5>
    <a href="{{ route('fixed-deposits.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Open FD</a>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="stat-card bg-primary"><div class="d-flex justify-content-between"><div><div class="small opacity-75">Active FDs</div><div class="fs-4 fw-bold">{{ $stats['active'] }}</div></div><div class="stat-icon"><i class="bi bi-safe2"></i></div></div></div></div>
    <div class="col-md-4"><div class="stat-card bg-info"><div class="d-flex justify-content-between"><div><div class="small opacity-75">Matured</div><div class="fs-4 fw-bold">{{ $stats['matured'] }}</div></div><div class="stat-icon"><i class="bi bi-check-circle"></i></div></div></div></div>
    <div class="col-md-4"><div class="stat-card bg-success"><div class="d-flex justify-content-between"><div><div class="small opacity-75">Total Portfolio</div><div class="fs-4 fw-bold">₹{{ number_format($stats['total_amount'],0) }}</div></div><div class="stat-icon"><i class="bi bi-currency-rupee"></i></div></div></div></div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>FD Number</th><th>Customer/Account</th><th>Principal</th><th>Rate</th><th>Tenure</th><th>Maturity Date</th><th>Maturity Amt</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($fds as $fd)
                <tr>
                    <td><code>{{ $fd->fd_number }}</code></td>
                    <td>{{ $fd->customer?->name ?? $fd->account?->user?->name }}<br><small class="text-muted">{{ $fd->account?->account_number }}</small></td>
                    <td class="fw-semibold">₹{{ number_format($fd->principal_amount,2) }}</td>
                    <td>{{ $fd->interest_rate }}% p.a.</td>
                    <td>{{ $fd->tenure_months }}M / {{ ucfirst($fd->compounding) }}</td>
                    <td>{{ $fd->maturity_date->format('d M Y') }}<br><small class="text-muted">{{ $fd->maturity_date->diffForHumans() }}</small></td>
                    <td class="fw-semibold text-success">₹{{ number_format($fd->maturity_amount,2) }}</td>
                    <td><span class="badge bg-{{ $fd->getStatusBadge() }}">{{ ucfirst(str_replace('_',' ',$fd->status)) }}</span></td>
                    <td>
                        <a href="{{ route('fixed-deposits.show',$fd) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        @if($fd->status === 'active')
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#closeModal{{ $fd->id }}"><i class="bi bi-x-circle"></i></button>
                        @endif
                    </td>
                </tr>
                @if($fd->status === 'active')
                <div class="modal fade" id="closeModal{{ $fd->id }}" tabindex="-1">
                    <div class="modal-dialog"><div class="modal-content">
                        <div class="modal-header"><h5 class="modal-title">Close FD {{ $fd->fd_number }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <form method="POST" action="{{ route('fixed-deposits.close',$fd) }}">@csrf
                        <div class="modal-body">
                            @if(now()->lt($fd->maturity_date))<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Premature closure — penalty of {{ $fd->premature_penalty_percent }}% on interest will apply.</div>@endif
                            <div class="mb-3"><label class="form-label">Closure Reason <span class="text-danger">*</span></label><input type="text" name="closure_reason" class="form-control" required></div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Close FD</button></div>
                        </form>
                    </div></div>
                </div>
                @endif
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No fixed deposits found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($fds->hasPages())<div class="card-footer">{{ $fds->links() }}</div>@endif
</div>
@endsection
