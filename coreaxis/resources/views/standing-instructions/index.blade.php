@extends('layouts.banking')
@section('title','Standing Instructions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-repeat me-2 text-primary"></i>Standing Instructions (Auto-Debit)</h5>
    <a href="{{ route('standing-instructions.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>New Instruction</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Account</th><th>Type</th><th>Amount</th><th>Frequency</th><th>Next Run</th><th>Executed</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse($sis as $si)
    <tr>
        <td>{{ $si->account?->account_number }}</td>
        <td><span class="badge bg-secondary">{{ ucwords(str_replace('_',' ',$si->instruction_type)) }}</span></td>
        <td class="fw-semibold">₹{{ number_format($si->amount,2) }}</td>
        <td>{{ ucfirst($si->frequency) }} (day {{ $si->execution_day }})</td>
        <td>{{ $si->next_execution_date?->format('d M Y') ?? '—' }}</td>
        <td>{{ $si->executed_count }}x</td>
        <td><span class="badge bg-{{ $si->status==='active'?'success':($si->status==='paused'?'warning':'secondary') }}">{{ ucfirst($si->status) }}</span></td>
        <td class="d-flex gap-1">
            @if(in_array($si->status,['active','paused']))
            <form method="POST" action="{{ route('standing-instructions.pause',$si) }}">@csrf
            <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ $si->status==='active'?'Pause':'Resume' }}"><i class="bi bi-{{ $si->status==='active'?'pause':'play' }}-fill"></i></button>
            </form>
            <form method="POST" action="{{ route('standing-instructions.cancel',$si) }}">@csrf
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this instruction?')"><i class="bi bi-x"></i></button>
            </form>
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted py-4">No standing instructions set up</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($sis->hasPages())<div class="card-footer">{{ $sis->links() }}</div>@endif</div>
@endsection
