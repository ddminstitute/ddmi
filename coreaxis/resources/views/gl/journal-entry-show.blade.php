@extends('layouts.banking')
@section('title','Journal Entry')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('gl.entries') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Journal Entry — {{ $journalEntry->entry_number }}</h5>
    @if($journalEntry->is_balanced)<span class="badge bg-success ms-auto">Balanced</span>@else<span class="badge bg-danger ms-auto">Unbalanced!</span>@endif
</div>
<div class="card mb-3"><div class="card-header">Entry Details</div><div class="card-body">
    <div class="row g-2 small">
        <div class="col-6"><span class="text-muted">Date:</span> <strong>{{ $journalEntry->entry_date->format('d M Y') }}</strong></div>
        <div class="col-6"><span class="text-muted">Source:</span> <span class="badge bg-secondary">{{ $journalEntry->source_type }}</span></div>
        <div class="col-12"><span class="text-muted">Narration:</span> {{ $journalEntry->narration }}</div>
        @if($journalEntry->reference)<div class="col-12"><span class="text-muted">Reference:</span> <code>{{ $journalEntry->reference }}</code></div>@endif
    </div>
</div></div>
<div class="card"><div class="card-header">Accounting Lines</div><div class="card-body p-0">
    <table class="table mb-0">
        <thead class="table-light"><tr><th>Code</th><th>Account</th><th class="text-end">Debit (Dr)</th><th class="text-end">Credit (Cr)</th></tr></thead>
        <tbody>
            @foreach($journalEntry->lines as $line)
            <tr>
                <td><code>{{ $line->account->code }}</code></td>
                <td>{{ $line->account->name }}</td>
                <td class="text-end text-primary">{{ $line->type==='debit' ? '₹'.number_format($line->amount,2) : '' }}</td>
                <td class="text-end text-success">{{ $line->type==='credit' ? '₹'.number_format($line->amount,2) : '' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-light fw-bold"><tr><td colspan="2" class="text-end">Total</td><td class="text-end text-primary">₹{{ number_format($journalEntry->total_debit,2) }}</td><td class="text-end text-success">₹{{ number_format($journalEntry->total_credit,2) }}</td></tr></tfoot>
    </table>
</div></div>
</div></div>
@endsection
