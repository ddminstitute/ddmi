<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CoreAxis') — CoreAxis Financial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary:#1565C0; --primary-dark:#0D47A1; --accent:#00BCD4; --sidebar-width:260px; }
        body { background:#f0f2f5; font-family:'Segoe UI',sans-serif; }
        .sidebar { width:var(--sidebar-width); min-height:100vh; background:linear-gradient(180deg,var(--primary-dark) 0%,var(--primary) 100%); position:fixed; top:0; left:0; z-index:100; box-shadow:4px 0 15px rgba(0,0,0,.15); overflow-y:auto; }
        .sidebar-brand { padding:1.25rem; border-bottom:1px solid rgba(255,255,255,.15); }
        .sidebar-brand h5 { color:#fff; margin:0; font-weight:700; font-size:1rem; }
        .sidebar-brand small { color:rgba(255,255,255,.6); font-size:.75rem; }
        .sidebar .nav-link { color:rgba(255,255,255,.75); padding:.6rem 1.1rem; border-radius:8px; margin:2px 8px; font-size:.85rem; transition:all .2s; }
        .sidebar .nav-link:hover,.sidebar .nav-link.active { color:#fff; background:rgba(255,255,255,.15); }
        .sidebar .nav-link i { width:20px; margin-right:8px; }
        .nav-section { color:rgba(255,255,255,.4); font-size:.68rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; padding:.9rem 1.1rem .2rem; }
        .main-content { margin-left:var(--sidebar-width); min-height:100vh; }
        .topbar { background:#fff; padding:.7rem 1.5rem; box-shadow:0 2px 8px rgba(0,0,0,.08); position:sticky; top:0; z-index:99; }
        .page-content { padding:1.5rem; }
        .card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.08); }
        .card-header { background:#fff; border-bottom:1px solid #f0f0f0; border-radius:12px 12px 0 0!important; padding:1rem 1.25rem; font-weight:600; }
        .stat-card { border-radius:12px; padding:1.25rem; color:#fff; border:none; }
        .stat-icon { width:48px; height:48px; border-radius:10px; background:rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
        .btn-primary { background:var(--primary); border-color:var(--primary); }
        .btn-primary:hover { background:var(--primary-dark); border-color:var(--primary-dark); }
        .table th { font-weight:600; font-size:.78rem; text-transform:uppercase; letter-spacing:.5px; color:#666; }
        .table td { vertical-align:middle; }
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-bank2 text-white fs-5"></i>
            </div>
            <div>
                <h5>CoreAxis</h5>
                <small>Financial Management</small>
            </div>
        </div>
    </div>
    <nav class="mt-2 pb-3">
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <div class="nav-section">Banking</div>
        <a href="{{ route('accounts.index') }}" class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Accounts
        </a>
        <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
            <i class="bi bi-list-ul"></i> All Transactions
        </a>
        <a href="{{ route('transactions.deposit') }}" class="nav-link {{ request()->routeIs('transactions.deposit') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Deposit
        </a>
        <a href="{{ route('transactions.withdraw') }}" class="nav-link {{ request()->routeIs('transactions.withdraw') ? 'active' : '' }}">
            <i class="bi bi-dash-circle"></i> Withdraw
        </a>
        <a href="{{ route('transactions.transfer') }}" class="nav-link {{ request()->routeIs('transactions.transfer') ? 'active' : '' }}">
            <i class="bi bi-send"></i> Transfer
        </a>
        <div class="nav-section">Loans</div>
        <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Loan Management
        </a>
        <div class="nav-section">Analytics</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>
    </nav>
</div>
<div class="main-content">
    <div class="topbar d-flex align-items-center justify-content-between">
        <div>
            <span class="fw-semibold">@yield('title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>
    </div>
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
