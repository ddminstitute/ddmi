<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','My Account') — CoreAxis Customer Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f0f4f8; }
        .portal-sidebar { width:240px;min-height:100vh;background:#0d47a1;padding:0;position:fixed;top:0;left:0;z-index:100; }
        .portal-sidebar .brand { padding:1.5rem 1rem 1rem;border-bottom:1px solid rgba(255,255,255,.15); }
        .portal-sidebar .brand-name { font-size:1.1rem;font-weight:700;color:#fff; }
        .portal-sidebar .brand-sub { font-size:.72rem;color:rgba(255,255,255,.6); }
        .portal-sidebar .nav-link { color:rgba(255,255,255,.8);padding:.55rem 1.25rem;border-radius:0;font-size:.875rem; }
        .portal-sidebar .nav-link:hover,.portal-sidebar .nav-link.active { background:rgba(255,255,255,.12);color:#fff; }
        .portal-main { margin-left:240px;padding:1.5rem; }
        .portal-topbar { background:#fff;border-radius:.5rem;padding:.75rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 4px rgba(0,0,0,.07); }
        @media(max-width:768px){.portal-sidebar{width:100%;min-height:auto;position:relative;}.portal-main{margin-left:0;}}
    </style>
</head>
<body>
<div class="portal-sidebar">
    <div class="brand">
        <div class="brand-name"><i class="bi bi-bank2 me-2"></i>CoreAxis</div>
        <div class="brand-sub">Customer Portal</div>
    </div>
    <nav class="mt-2">
        <a href="{{ route('portal.dashboard') }}" class="nav-link {{ request()->routeIs('portal.dashboard')?'active':'' }}"><i class="bi bi-grid me-2"></i>Dashboard</a>
        <a href="{{ route('portal.accounts') }}" class="nav-link {{ request()->routeIs('portal.accounts')?'active':'' }}"><i class="bi bi-wallet2 me-2"></i>My Accounts</a>
        <a href="{{ route('portal.transactions') }}" class="nav-link {{ request()->routeIs('portal.transactions')?'active':'' }}"><i class="bi bi-clock-history me-2"></i>Transactions</a>
        <a href="{{ route('portal.loans') }}" class="nav-link {{ request()->routeIs('portal.loans')?'active':'' }}"><i class="bi bi-cash-coin me-2"></i>Loans</a>
        <a href="{{ route('portal.deposits') }}" class="nav-link {{ request()->routeIs('portal.deposits')?'active':'' }}"><i class="bi bi-piggy-bank me-2"></i>FD / RD</a>
        <a href="{{ route('portal.grievances') }}" class="nav-link {{ request()->routeIs('portal.grievances')?'active':'' }}"><i class="bi bi-chat-square-text me-2"></i>Grievances</a>
        <hr class="border-white opacity-25 mx-3">
        <a href="{{ route('portal.profile') }}" class="nav-link {{ request()->routeIs('portal.profile')?'active':'' }}"><i class="bi bi-person me-2"></i>My Profile</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="nav-link btn btn-link text-start w-100" style="color:rgba(255,255,255,.8)"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form>
    </nav>
</div>
<div class="portal-main">
    <div class="portal-topbar">
        <span class="fw-semibold text-muted small">@yield('title','Dashboard')</span>
        <span class="small text-muted"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</span>
    </div>
    @if(session('success'))<div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if(session('error'))<div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
