@extends('layouts.banking')
@section('title','General Ledger')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-book me-2 text-primary"></i>General Ledger</h5>
<div class="card mb-3"><div class="card-body py-2">
    <form class="row g-2 align-items-end">
        <div class="col-md-4"><label class="form-label small mb-1">Account</label><select name="account_id" class="form-select form-select-sm" required><option value="">Select account...</option>@foreach($accounts as $acc)<option value="{{ $acc->id }}" {{ request('account_id')==$acc->id?'selected':'' }}>{{ $acc->code }} — {{ $acc->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label small mb-1">From</label><input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm"></div>
        <div class="col-md-3"><label class="form-label small mb-1">To</label><input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm"></div>
        <div class="col-md-2"><button class="btn btn-primary btn-sm w-100">View</button></div>
    </form>
</div></div>
@if($selected)
<div class="card">
    <div class="card-header d-flex justify-content-between"><span><code>{{ $selected->code }}</code> — {{ $selected->name }}</span><span class="badge bg-{{ $selected->getTypeBadge() }}">{{ ucfirst($selected->type) }}</span></div>
    <div class="card-body p-0"><table class="table mb-0">
        <thead class="table-light"><tr><th>Date</th><th>Entry #</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Balance</th></tr></thead>
        <tbody>
            @php $running=0; @endphp
            @forelse($lines as $line)
            @php $running += $line->type==='debit' ? ($selected->normal_balance==='debit'?$line->amount:-$line->amount) : ($selected->normal_balance==='credit'?$line->amount:-$line->amount); @endphp
            <tr>
                <td class="text-muted small">{{ $line->journalEntry->entry_date->format('d M Y') }}</td>
                <td><code class="small">{{ $line->journalEntry->entry_number }}</code></td>
                <td class="small">{{ Str::limit($line->journalEntry->narration,45) }}</td>
                <td class="text-end text-primary">{{ $line->type==='debit' ? '₹'.number_format($line->amount,2) : '' }}</td>
                <td class="text-end text-success">{{ $line->type==='credit' ? '₹'.number_format($line->amount,2) : '' }}</td>
                <td class="text-end fw-semibold {{ $running<0?'text-danger':'' }}">₹{{ number_format(abs($running),2) }} {{ $running<0?'Cr':'Dr' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No entries found.</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endif
@endsection
