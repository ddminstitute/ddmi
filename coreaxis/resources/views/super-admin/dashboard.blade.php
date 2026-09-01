@extends('layouts.banking')
@section('title', 'Super Admin Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-shield-lock-fill me-2 text-dark"></i>Super Admin Dashboard</h2>
    <a href="{{ route('super-admin.permissions') }}" class="btn btn-outline-primary"><i class="bi bi-toggles me-1"></i>Manage Permissions</a>
</div>

<h5 class="fw-semibold text-muted mb-3">Users by Role</h5>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-dark">{{ $stats['super_admin'] }}</div>
                <span class="badge bg-dark mt-1">Super Admin</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-danger">{{ $stats['admin'] }}</div>
                <span class="badge bg-danger mt-1">Admin</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-primary">{{ $stats['manager'] }}</div>
                <span class="badge bg-primary mt-1">Manager</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-success">{{ $stats['cashier'] }}</div>
                <span class="badge bg-success mt-1">Cashier</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-info">{{ $stats['agent'] }}</div>
                <span class="badge bg-info mt-1">Agent</span>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-semibold text-muted mb-3">System Health</h5>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100 bg-light">
            <div class="card-body py-3">
                <i class="bi bi-people-fill fs-3 text-primary"></i>
                <div class="fs-3 fw-bold">{{ $systemCounts['customers'] }}</div>
                <small class="text-muted">Customers</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100 bg-light">
            <div class="card-body py-3">
                <i class="bi bi-bank fs-3 text-success"></i>
                <div class="fs-3 fw-bold">{{ $systemCounts['accounts'] }}</div>
                <small class="text-muted">Accounts</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100 bg-light">
            <div class="card-body py-3">
                <i class="bi bi-cash-coin fs-3 text-warning"></i>
                <div class="fs-3 fw-bold">{{ $systemCounts['loans'] }}</div>
                <small class="text-muted">Loans</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100 bg-light">
            <div class="card-body py-3">
                <i class="bi bi-person-badge fs-3 text-info"></i>
                <div class="fs-3 fw-bold">{{ $systemCounts['employees'] }}</div>
                <small class="text-muted">Employees</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100 bg-light">
            <div class="card-body py-3">
                <i class="bi bi-receipt fs-3 text-danger"></i>
                <div class="fs-3 fw-bold">{{ $systemCounts['expenses'] }}</div>
                <small class="text-muted">Expenses</small>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-semibold text-muted mb-3">Quick Links</h5>
<div class="row g-3">
    <div class="col-auto"><a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-people me-1"></i>Users</a></div>
    <div class="col-auto"><a href="{{ route('super-admin.permissions') }}" class="btn btn-outline-primary"><i class="bi bi-toggles me-1"></i>Permissions</a></div>
    <div class="col-auto"><a href="{{ route('super-admin.seed') }}" class="btn btn-outline-dark" onclick="return confirm('Promote first user to Super Admin?')"><i class="bi bi-shield-check me-1"></i>Seed Super Admin</a></div>
</div>
@endsection
