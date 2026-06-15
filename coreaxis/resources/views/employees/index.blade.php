@extends('layouts.banking')
@section('title','Employees')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2 text-primary"></i>Employees</h5>
    <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i>Add Employee</a>
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="row g-2">
        <div class="col-md-4"><input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name or employee ID..."></div>
        <div class="col-md-3">
            <select name="department" class="form-select form-select-sm">
                <option value="">All Departments</option>
                @foreach($departments as $d)<option value="{{ $d }}" {{ request('department')==$d?'selected':'' }}>{{ $d }}</option>@endforeach
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search me-1"></i>Search</button></div>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Photo</th><th>Employee ID</th><th>Name</th><th>Designation</th><th>Department</th><th>Phone</th><th>Salary</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse($employees as $emp)
        <tr>
            <td>
                @if($emp->photo)
                    <img src="{{ Storage::url($emp->photo) }}" class="rounded-circle" width="36" height="36" style="object-fit:cover">
                @else
                    <div class="rounded-circle bg-info d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px"><span class="text-white fw-bold" style="font-size:.8rem">{{ strtoupper(substr($emp->name,0,1)) }}</span></div>
                @endif
            </td>
            <td><span class="badge bg-light text-dark border fw-semibold" style="font-size:.72rem">{{ $emp->employee_id }}</span></td>
            <td class="fw-semibold">{{ $emp->name }}</td>
            <td class="small">{{ $emp->designation }}</td>
            <td class="small text-muted">{{ $emp->department }}</td>
            <td class="small">{{ $emp->phone }}</td>
            <td class="fw-semibold text-success small">₹{{ number_format($emp->grossSalary(),2) }}</td>
            <td><span class="badge bg-{{ $emp->status==='active'?'success':'secondary' }}">{{ ucfirst($emp->status) }}</span></td>
            <td>
                <a href="{{ route('employees.show',$emp) }}" class="btn btn-xs btn-outline-primary me-1" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-eye"></i></a>
                <a href="{{ route('employees.attendance',$emp) }}" class="btn btn-xs btn-outline-info me-1" style="font-size:.75rem;padding:.2rem .55rem" title="Attendance"><i class="bi bi-calendar3"></i></a>
                <a href="{{ route('employees.edit',$emp) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-pencil"></i></a>
            </td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center text-muted py-4">No employees found</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>
@if($employees->hasPages())<div class="card-footer bg-white border-0 py-2">{{ $employees->links() }}</div>@endif
</div>
@endsection
