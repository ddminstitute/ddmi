@extends('layouts.portal')
@section('title','Fixed & Recurring Deposits')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-piggy-bank me-2 text-success"></i>Fixed & Recurring Deposits</h5>
@if($fds->count())
<h6 class="text-muted mb-2">Fixed Deposits</h6>
<div class="row g-3 mb-4">
    @foreach($fds as $fd)
    <div class="col-md-6"><div class="card"><div class="card-body">
        <div class="d-flex justify-content-between"><div><div class="fw-bold">{{ $fd->fd_number }}</div><div class="text-muted small">{{ $fd->interest_rate }}% · {{ $fd->tenure_months }} months · {{ ucfirst($fd->compounding) }}</div></div><span class="badge bg-{{ $fd->status==='active'?'success':'secondary' }}">{{ ucfirst($fd->status) }}</span></div>
        <hr>
        <div class="row g-2 small"><div class="col-6"><span class="text-muted">Principal:</span> <strong>₹{{ number_format($fd->principal_amount,2) }}</strong></div><div class="col-6"><span class="text-muted">Maturity:</span> <strong>₹{{ number_format($fd->maturity_amount,2) }}</strong></div><div class="col-6"><span class="text-muted">Start:</span> {{ $fd->start_date->format('d M Y') }}</div><div class="col-6"><span class="text-muted">Matures:</span> {{ $fd->maturity_date->format('d M Y') }}</div></div>
    </div></div></div>
    @endforeach
</div>
@endif
@if($rds->count())
<h6 class="text-muted mb-2">Recurring Deposits</h6>
<div class="row g-3">
    @foreach($rds as $rd)
    <div class="col-md-6"><div class="card"><div class="card-body">
        <div class="d-flex justify-content-between"><div><div class="fw-bold">{{ $rd->rd_number }}</div><div class="text-muted small">Monthly ₹{{ number_format($rd->monthly_installment,2) }} · {{ $rd->tenure_months }} months</div></div><span class="badge bg-{{ $rd->status==='active'?'success':'secondary' }}">{{ ucfirst($rd->status) }}</span></div>
        <hr>
        <div class="row g-2 small"><div class="col-6"><span class="text-muted">Interest:</span> {{ $rd->interest_rate }}%</div><div class="col-6"><span class="text-muted">Maturity:</span> ₹{{ number_format($rd->maturity_amount,2) }}</div></div>
    </div></div></div>
    @endforeach
</div>
@endif
@if($fds->isEmpty()&&$rds->isEmpty())<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>No fixed or recurring deposits on record.</div>@endif
@endsection
