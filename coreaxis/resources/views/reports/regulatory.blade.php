@extends('layouts.banking')
@section('title','Regulatory Returns')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-ruled me-2 text-primary"></i>Regulatory / Statutory Returns</h5>
</div>
<div class="card mb-3"><div class="card-body py-2"><form class="row g-2 align-items-end" method="GET">
    <div class="col-md-3"><label class="form-label form-label-sm mb-1">Month</label><input type="month" name="month" class="form-control form-control-sm" value="{{ $month }}"></div>
    <div class="col-auto"><button class="btn btn-sm btn-primary">Generate</button></div>
    <div class="col-auto"><a href="{{ route('reports.regulatory') }}?month={{ $month }}&format=csv" class="btn btn-sm btn-outline-success"><i class="bi bi-download me-1"></i>Export CSV</a></div>
</form></div></div>
<div class="card"><div class="card-header fw-semibold">Monthly Return — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</div>
<div class="card-body">
<div class="alert alert-info small"><i class="bi bi-info-circle me-2"></i>This report provides data for filing with the Registrar of Cooperative Societies / RBI. Verify all figures before submission.</div>
<table class="table table-bordered">
    <thead class="table-light"><tr><th>Parameter</th><th class="text-end">Value</th></tr></thead>
    <tbody>
    @foreach($data as $k => $v)
    <tr>
        <td>{{ ucwords(str_replace('_',' ',$k)) }}</td>
        <td class="text-end fw-semibold font-monospace">
            @if(is_numeric($v) && (str_contains($k,'amount') || str_contains($k,'deposits') || str_contains($k,'portfolio') || str_contains($k,'loans')))
                ₹{{ number_format($v,2) }}
            @else
                {{ $v }}
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<p class="text-muted small mt-2">Generated on: {{ now()->format('d M Y H:i') }} by {{ auth()->user()->name }}</p>
</div></div>
@endsection
