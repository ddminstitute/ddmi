@extends('layouts.banking')
@section('title','Nominees')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('accounts.show',$account) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h5 class="mb-0 fw-bold">Nominees — {{ $account->account_number }}</h5>
    </div>
    @php $total = $nominees->sum('share_percent'); @endphp
    @if($total < 100)
    <a href="{{ route('accounts.nominees.create',$account) }}" class="btn btn-sm btn-primary"><i class="bi bi-person-plus me-1"></i>Add Nominee</a>
    @endif
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
@if($total < 100 && $nominees->count() > 0)<div class="alert alert-warning">Total share is {{ $total }}%. Please add nominees to reach 100%.</div>@endif
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Name</th><th>Relation</th><th>DOB</th><th>Phone</th><th>Share %</th><th>Minor?</th><th>Guardian</th><th></th></tr></thead>
    <tbody>
    @forelse($nominees as $n)
    <tr>
        <td class="fw-semibold">{{ $n->name }}</td>
        <td>{{ $n->relation }}</td>
        <td>{{ $n->date_of_birth?->format('d M Y') ?? '—' }}</td>
        <td>{{ $n->phone ?? '—' }}</td>
        <td><span class="badge bg-primary">{{ $n->share_percent }}%</span></td>
        <td>{!! $n->is_minor ? '<span class="badge bg-warning text-dark">Yes</span>' : 'No' !!}</td>
        <td>{{ $n->guardian_name ?? '—' }}</td>
        <td>
            <form method="POST" action="{{ route('accounts.nominees.destroy',[$account,$n]) }}">@csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove nominee?')"><i class="bi bi-trash"></i></button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-person-x fs-1 d-block mb-2 opacity-25"></i>No nominees added yet. Add at least one nominee.</td></tr>
    @endforelse
    </tbody>
    @if($nominees->count())<tfoot class="table-light"><tr><td colspan="4" class="fw-semibold">Total</td><td class="fw-bold">{{ $total }}%</td><td colspan="3"></td></tr></tfoot>@endif
</table>
</div></div></div>
@endsection
