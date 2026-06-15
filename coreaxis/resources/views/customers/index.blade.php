@extends('layouts.banking')
@section('title','Customers')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>Customer Management</h5>
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i>Add Customer</a>
</div>
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search by name, phone, customer ID, PAN...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                    <option value="blacklisted" {{ request('status')=='blacklisted'?'selected':'' }}>Blacklisted</option>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search me-1"></i>Search</button></div>
            @if(request()->anyFilled(['search','status']))
                <div class="col-auto"><a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Photo</th><th>Customer ID</th><th>Name</th><th>Phone</th><th>City</th><th>PAN</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr>
                        <td>
                            @if($c->photo)
                                <img src="{{ Storage::url($c->photo) }}" class="rounded-circle" width="36" height="36" style="object-fit:cover">
                            @else
                                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px"><span class="text-white fw-bold" style="font-size:.8rem">{{ strtoupper(substr($c->name,0,1)) }}</span></div>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border fw-semibold" style="font-size:.72rem">{{ $c->customer_id }}</span></td>
                        <td class="fw-semibold">{{ $c->name }}<div class="text-muted" style="font-size:.75rem">{{ $c->father_name ? 'S/O '.$c->father_name : '' }}</div></td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ $c->city }}, {{ $c->state }}</td>
                        <td class="text-muted small">{{ $c->pan_number ?: '—' }}</td>
                        <td><span class="badge bg-{{ $c->getStatusBadge() }}">{{ ucfirst($c->status) }}</span></td>
                        <td>
                            <a href="{{ route('customers.show',$c) }}" class="btn btn-xs btn-outline-primary me-1" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('customers.edit',$c) }}" class="btn btn-xs btn-outline-secondary me-1" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('customers.destroy',$c) }}" class="d-inline" onsubmit="return confirm('Delete this customer?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-people fs-2 d-block mb-2"></i>No customers found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
    <div class="card-footer bg-white border-0 py-2">{{ $customers->links() }}</div>
    @endif
</div>
@endsection
