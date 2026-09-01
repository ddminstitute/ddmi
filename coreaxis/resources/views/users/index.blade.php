@extends('layouts.banking')
@section('title','User Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-person me-2 text-primary"></i>User Management</h5>
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i>Add User</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse($users as $u)
        <tr>
            <td class="fw-semibold">{{ $u->name }}</td>
            <td class="text-muted small">{{ $u->email }}</td>
            <td class="small">{{ $u->phone ?? '—' }}</td>
            <td>
                @php $rc = ['super_admin'=>'dark','admin'=>'danger','manager'=>'primary','cashier'=>'success','agent'=>'info']; @endphp
                <span class="badge bg-{{ $rc[$u->role ?? 'cashier'] ?? 'secondary' }}">{{ $u->role === 'super_admin' ? 'Super Admin' : ucfirst($u->role ?? 'cashier') }}</span>
            </td>
            <td><span class="badge bg-{{ ($u->is_active ?? true)?'success':'secondary' }}">{{ ($u->is_active ?? true)?'Active':'Inactive' }}</span></td>
            <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
            <td>
                <a href="{{ route('users.edit',$u) }}" class="btn btn-xs btn-outline-secondary me-1" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-pencil"></i></a>
                @if($u->id !== auth()->id())
                <form method="POST" action="{{ route('users.destroy',$u) }}" class="d-inline" onsubmit="return confirm('Delete this user?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-outline-danger" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-trash"></i></button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4">No users found</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>
@if($users->hasPages())<div class="card-footer bg-white border-0 py-2">{{ $users->links() }}</div>@endif
</div>
@endsection
