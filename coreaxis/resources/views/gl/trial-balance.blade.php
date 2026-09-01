@extends('layouts.banking')
@section('title','Trial Balance')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart-steps me-2 text-primary"></i>Trial Balance</h5>
    <form class="d-flex gap-2 align-items-center"><label class="mb-0 small">As of:</label><input type="date" name="as_of" value="{{ $asOfDate }}" class="form-control form-control-sm" style="width:160px"><button class="btn btn-primary btn-sm">Refresh</button></form>
</div>
@php $grouped=$accounts->groupBy('type'); $typeOrder=['asset','liability','equity','income','expense']; @endphp
@foreach($typeOrder as $type)
@if(isset($grouped[$type])&&$grouped[$type]->count())
<div class="card mb-3">
    <div class="card-header py-2"><strong>{{ ucfirst($type==='asset'?'Assets':($type==='liability'?'Liabilities':ucfirst($type))) }}</strong></div>
    <div class="card-body p-0"><table class="table table-sm mb-0">
        <thead class="table-light"><tr><th>Code</th><th>Account Name</th><th class="text-end">Debit (Dr)</th><th class="text-end">Credit (Cr)</th><th class="text-end">Net Balance</th></tr></thead>
        <tbody>
            @foreach($grouped[$type]->sortBy('code') as $acc)
            <tr>
                <td><code class="small">{{ $acc->code }}</code></td>
                <td>{{ $acc->name }}</td>
                <td class="text-end text-primary">{{ $acc->total_debits>0 ? '₹'.number_format($acc->total_debits,2) : '—' }}</td>
                <td class="text-end text-success">{{ $acc->total_credits>0 ? '₹'.number_format($acc->total_credits,2) : '—' }}</td>
                <td class="text-end fw-semibold {{ $acc->net_balance<0?'text-danger':'' }}">₹{{ number_format(abs($acc->net_balance),2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table></div>
</div>
@endif
@endforeach
<div class="card border-{{ abs($totals['debit']-$totals['credit'])<0.01?'success':'danger' }}">
    <div class="card-body"><div class="row text-center">
        <div class="col-md-4"><div class="text-muted small">Total Debits</div><div class="fs-4 fw-bold text-primary">₹{{ number_format($totals['debit'],2) }}</div></div>
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            @if(abs($totals['debit']-$totals['credit'])<0.01)<span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-check-circle me-1"></i>Balanced</span>@else<span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-exclamation-triangle me-1"></i>Difference: ₹{{ number_format(abs($totals['debit']-$totals['credit']),2) }}</span>@endif
        </div>
        <div class="col-md-4"><div class="text-muted small">Total Credits</div><div class="fs-4 fw-bold text-success">₹{{ number_format($totals['credit'],2) }}</div></div>
    </div></div>
</div>
@endsection
