@extends('layouts.banking')
@section('title', 'Role Permissions Matrix')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-toggles me-2 text-primary"></i>Role Permissions Matrix</h2>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('super-admin.permissions.update') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3" style="min-width:200px">Feature</th>
                            <th class="text-center"><span class="badge bg-dark px-3 py-2 fs-6">Super Admin</span></th>
                            <th class="text-center"><span class="badge bg-danger px-3 py-2 fs-6">Admin</span></th>
                            <th class="text-center"><span class="badge bg-primary px-3 py-2 fs-6">Manager</span></th>
                            <th class="text-center"><span class="badge bg-success px-3 py-2 fs-6">Cashier</span></th>
                            <th class="text-center"><span class="badge bg-info px-3 py-2 fs-6">Agent</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($features as $key => $label)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $label }}</td>
                            {{-- Super Admin: always enabled --}}
                            <td class="text-center">
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Always</span>
                            </td>
                            @foreach($roles as $role)
                            @php
                                $perm = $permissions->get($role)?->firstWhere('feature_key', $key);
                                $checked = $perm ? $perm->is_enabled : true;
                            @endphp
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        name="perm[{{ $role }}][{{ $key }}]"
                                        style="width:2.5em;height:1.3em;cursor:pointer"
                                        {{ $checked ? 'checked' : '' }}>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Save Permissions</button>
            </div>
        </form>
    </div>
</div>
@endsection
