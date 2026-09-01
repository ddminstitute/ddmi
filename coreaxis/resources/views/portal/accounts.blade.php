@extends('layouts.portal')
@section('title','My Accounts')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-wallet2 me-2 text-primary"></i>My Accounts</h5>
<div class="row g-3">
    @forelse($accounts as $acc)
    <div class="col-md-6"><div class="card h-100"><div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div><div class="fw-bold">{{ $acc->account_number }}</div><div class="text-muted small">{{ $acc->getTypeLabel() }} · {{ $acc->currency }}</div><div class="mt-1"><span class="badge bg-{{ $acc->status==='active'?'success':($acc->status==='frozen'?'warning text-dark':'secondary') }}">{{ ucfirst($acc->status) }}</span></div></div>
            <div class="text-end"><div class="text-muted small mb-1">Balance</div><div class="fs-4 fw-bold text-primary">₹{{ number_format($acc->balance,2) }}</div></div>
        </div>
        <hr>
        <div class="row g-2 small text-muted">
            <div class="col-6"><i class="bi bi-calendar me-1"></i>Opened {{ $acc->created_at->format('M d, Y') }}</div>
            <div class="col-6 text-end"><a href="{{ route('portal.transactions', ['account_id'=>$acc->id]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-clock-history me-1"></i>Transactions</a></div>
        </div>
    </div></div></div>
    @empty
    <div class="col-12"><div class="alert alert-info">No accounts linked to your profile. Please contact the branch.</div></div>
    @endforelse
</div>
@endsection
