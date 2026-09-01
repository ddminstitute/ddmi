@extends('layouts.banking')
@section('title','Branches')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i>Branch Management</h5>
    <a href="{{ route('branches.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Add Branch</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Code</th><th>Branch Name</th><th>City</th><th>Phone</th><th>Manager</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse($branches as $b)
    <tr>
        <td><code>{{ $b->branch_code }}</code></td>
        <td class="fw-semibold">{{ $b->branch_name }}</td>
        <td>{{ $b->city }}, {{ $b->state }}</td>
        <td>{{ $b->phone ?? '—' }}</td>
        <td>{{ $b->manager_name ?? '—' }}</td>
        <td><span class="badge bg-{{ $b->is_active?'success':'secondary' }}">{{ $b->is_active?'Active':'Inactive' }}</span></td>
        <td><a href="{{ route('branches.edit',$b) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a></td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted py-4">No branches yet. Add your first branch.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div></div>
@endsection
