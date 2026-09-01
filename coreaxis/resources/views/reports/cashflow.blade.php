@extends('layouts.banking')
@section('title','Cash Flow Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Cash Flow & Liquidity Dashboard</h5>
</div>
<div class="card mb-3"><div class="card-body py-2"><form class="row g-2 align-items-end" method="GET">
    <div class="col-md-3"><label class="form-label form-label-sm mb-1">From</label><input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}"></div>
    <div class="col-md-3"><label class="form-label form-label-sm mb-1">To</label><input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}"></div>
    <div class="col-auto"><button class="btn btn-sm btn-primary">Apply</button></div>
</form></div></div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="stat-card bg-primary"><div class="d-flex justify-content-between"><div><div class="small opacity-75">Total Balance (All Accounts)</div><div class="fs-5 fw-bold">₹{{ number_format($totalBalance,0) }}</div></div><div class="stat-icon"><i class="bi bi-bank2"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card bg-success"><div class="d-flex justify-content-between"><div><div class="small opacity-75">Period Deposits</div><div class="fs-5 fw-bold">₹{{ number_format($totalDeposits,0) }}</div></div><div class="stat-icon"><i class="bi bi-arrow-down-circle"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card bg-danger"><div class="d-flex justify-content-between"><div><div class="small opacity-75">Period Withdrawals</div><div class="fs-5 fw-bold">₹{{ number_format($totalWithdrawals,0) }}</div></div><div class="stat-icon"><i class="bi bi-arrow-up-circle"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card bg-warning text-dark"><div class="d-flex justify-content-between"><div><div class="small">Loans Outstanding</div><div class="fs-5 fw-bold">₹{{ number_format($totalLoans,0) }}</div></div><div class="stat-icon"><i class="bi bi-credit-card"></i></div></div></div></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card text-center p-3"><div class="text-muted small">FD Portfolio</div><div class="fs-4 fw-bold text-primary">₹{{ number_format($totalFD,0) }}</div></div></div>
    <div class="col-md-4"><div class="card text-center p-3"><div class="text-muted small">RD Portfolio</div><div class="fs-4 fw-bold text-info">₹{{ number_format($totalRD,0) }}</div></div></div>
    <div class="col-md-4"><div class="card text-center p-3"><div class="text-muted small">Net Cash Flow (Period)</div>
    @php $net = $totalDeposits - $totalWithdrawals; @endphp
    <div class="fs-4 fw-bold {{ $net >= 0 ? 'text-success' : 'text-danger' }}">{{ $net >= 0 ? '+' : '' }}₹{{ number_format($net,0) }}</div></div></div>
</div>
<div class="card"><div class="card-header fw-semibold">Daily Flow ({{ $from }} to {{ $to }})</div>
<div class="card-body p-0"><div class="table-responsive">
<table class="table table-sm mb-0">
    <thead class="table-light"><tr><th>Date</th><th>Deposits</th><th>Withdrawals</th><th>Transfers</th></tr></thead>
    <tbody>
    @php $byDate = $dailyFlow->groupBy('date'); @endphp
    @forelse($byDate as $date => $rows)
    <tr>
        <td class="fw-semibold">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
        <td class="text-success">₹{{ number_format($rows->where('transaction_type','deposit')->sum('total'),2) }}</td>
        <td class="text-danger">₹{{ number_format($rows->where('transaction_type','withdrawal')->sum('total'),2) }}</td>
        <td class="text-muted">₹{{ number_format($rows->whereIn('transaction_type',['transfer_in','transfer_out'])->sum('total'),2) }}</td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center text-muted py-3">No transactions in this period</td></tr>
    @endforelse
    </tbody>
</table>
</div></div></div>
@endsection
