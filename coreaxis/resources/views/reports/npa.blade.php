@extends('layouts.banking')
@section('title','NPA Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>NPA / Overdue Loan Report</h5>
    <a href="{{ route('reports.regulatory') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i>Regulatory Returns</a>
</div>
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3"><div class="stat-card bg-danger"><div class="d-flex justify-content-between align-items-center"><div><div class="small opacity-75">NPA Accounts</div><div class="fs-3 fw-bold">{{ $summary['total_npa_accounts'] }}</div></div><div class="stat-icon"><i class="bi bi-x-octagon"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card bg-warning text-dark"><div class="d-flex justify-content-between align-items-center"><div><div class="small">NPA Portfolio</div><div class="fs-5 fw-bold">₹{{ number_format($summary['total_npa_amount'],0) }}</div></div><div class="stat-icon"><i class="bi bi-currency-rupee"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card bg-secondary"><div class="d-flex justify-content-between align-items-center"><div><div class="small opacity-75">Total Penalties</div><div class="fs-5 fw-bold">₹{{ number_format($summary['total_penalties'],0) }}</div></div><div class="stat-icon"><i class="bi bi-exclamation-circle"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card" style="background:#6f42c1"><div class="d-flex justify-content-between align-items-center"><div><div class="small opacity-75">90+ Day DPD</div><div class="fs-3 fw-bold">{{ $summary['overdue_90_plus'] }}</div></div><div class="stat-icon"><i class="bi bi-calendar-x"></i></div></div></div></div>
</div>
<div class="card mb-3"><div class="card-header fw-semibold">DPD Bucket Summary</div><div class="card-body">
<div class="row g-3 text-center">
    @foreach([['1–30 DPD','overdue_30','warning'],['31–60 DPD','overdue_60','orange'],['61–90 DPD','overdue_90','danger'],['90+ DPD (NPA)','overdue_90_plus','dark']] as [$l,$k,$c])
    <div class="col-6 col-md-3"><div class="p-3 border rounded"><div class="text-muted small">{{ $l }}</div><div class="fs-2 fw-bold text-{{ $c }}">{{ $summary[$k] }}</div></div></div>
    @endforeach
</div>
</div></div>
<div class="card mb-3"><div class="card-body py-2"><form class="row g-2" method="GET">
    <div class="col-md-3"><select name="bucket" class="form-select form-select-sm"><option value="">All DPD</option><option value="30" {{ request('bucket')==='30'?'selected':'' }}>1–30 DPD</option><option value="60" {{ request('bucket')==='60'?'selected':'' }}>31–60 DPD</option><option value="90" {{ request('bucket')==='90'?'selected':'' }}>61–90 DPD</option><option value="180+" {{ request('bucket')==='180+'?'selected':'' }}>90+ DPD (NPA)</option></select></div>
    <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button> <a href="{{ route('reports.npa') }}" class="btn btn-sm btn-outline-secondary">Reset</a></div>
</form></div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Loan #</th><th>Customer</th><th>Account</th><th>Outstanding</th><th>Overdue Days</th><th>Penalty</th><th>NPA?</th><th>Status</th></tr></thead>
    <tbody>
    @forelse($loans as $loan)
    <tr>
        <td><a href="{{ route('loans.show',$loan) }}" class="text-decoration-none fw-semibold">{{ $loan->loan_number }}</a></td>
        <td>{{ $loan->customer?->name ?? $loan->account?->user?->name }}</td>
        <td>{{ $loan->account?->account_number }}</td>
        <td class="fw-semibold text-danger">₹{{ number_format($loan->outstanding_amount,2) }}</td>
        <td>
            @php $dpd=$loan->overdue_days; $oc=$dpd<=30?'warning':($dpd<=90?'danger':'dark'); @endphp
            <span class="badge bg-{{ $oc }}">{{ $dpd }} days</span>
        </td>
        <td class="text-danger">₹{{ number_format($loan->penalty_amount,2) }}</td>
        <td>{!! $loan->is_npa ? '<span class="badge bg-danger">NPA</span>' : '<span class="badge bg-success">Standard</span>' !!}</td>
        <td><span class="badge bg-{{ $loan->getStatusBadge() }}">{{ ucfirst($loan->status) }}</span></td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted py-4">No overdue loans found</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($loans->hasPages())<div class="card-footer">{{ $loans->links() }}</div>@endif</div>
@endsection
