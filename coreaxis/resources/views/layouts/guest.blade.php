<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CoreAxis') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Segoe UI', system-ui, sans-serif; }
        body { min-height: 100vh; margin: 0; display: flex; }
        .auth-left {
            width: 42%; min-height: 100vh;
            background: linear-gradient(145deg, #050d1a 0%, #0D47A1 55%, #1565C0 100%);
            display: flex; flex-direction: column; justify-content: center;
            padding: 3rem 2.5rem; position: relative; overflow: hidden;
        }
        .auth-left::before { content:''; position:absolute; inset:0;
            background: radial-gradient(ellipse at 15% 85%, rgba(0,188,212,.18) 0%, transparent 55%),
                        radial-gradient(ellipse at 85% 15%, rgba(21,101,192,.25) 0%, transparent 50%); }
        .auth-left-content { position: relative; z-index: 1; }
        .brand-mark { width:56px; height:56px; background:linear-gradient(135deg,#00BCD4,#0097A7); border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem; box-shadow:0 8px 25px rgba(0,188,212,.4); }
        .brand-mark i { color:#fff; font-size:1.8rem; }
        .auth-left h1 { color:#fff; font-size:2rem; font-weight:800; line-height:1.2; margin-bottom:.6rem; }
        .auth-left h1 span { background:linear-gradient(135deg,#00BCD4,#80DEEA); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .auth-left p { color:rgba(255,255,255,.6); font-size:.9rem; line-height:1.7; margin-bottom:1.75rem; }
        .auth-stat { background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1); border-radius:14px; padding:1rem 1.25rem; margin-bottom:.65rem; }
        .auth-stat-num { color:#fff; font-size:1.35rem; font-weight:800; }
        .auth-stat-label { color:rgba(255,255,255,.5); font-size:.72rem; text-transform:uppercase; letter-spacing:1px; }
        .auth-contact { margin-top:1.5rem; padding-top:1.25rem; border-top:1px solid rgba(255,255,255,.1); }
        .auth-contact div { color:rgba(255,255,255,.45); font-size:.75rem; margin-bottom:.3rem; }
        .auth-right { flex:1; display:flex; align-items:center; justify-content:center; padding:2rem; background:#f0f4ff; }
        .auth-card { background:#fff; border-radius:24px; padding:2.5rem; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,.1); }
        .auth-logo { display:flex; align-items:center; gap:.75rem; margin-bottom:1.75rem; }
        .auth-logo-icon { width:44px; height:44px; background:linear-gradient(135deg,#00BCD4,#0097A7); border-radius:11px; display:flex; align-items:center; justify-content:center; }
        .auth-logo-icon i { color:#fff; font-size:1.25rem; }
        .form-control { border-radius:10px; padding:.65rem 1rem; border:1.5px solid #e2e8f0; font-size:.875rem; }
        .form-control:focus { border-color:#1565C0; box-shadow:0 0 0 3px rgba(21,101,192,.1); }
        .input-group-text { background:#f8faff; border:1.5px solid #e2e8f0; border-right:none; border-radius:10px 0 0 10px; }
        .input-group .form-control { border-radius:0 10px 10px 0; }
        .btn-auth { background:linear-gradient(135deg,#1565C0,#0D47A1); color:#fff; border:none; border-radius:10px; padding:.78rem; font-weight:600; font-size:.93rem; width:100%; transition:all .3s; }
        .btn-auth:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(21,101,192,.38); color:#fff; }
        label { font-size:.82rem; font-weight:600; color:#374151; margin-bottom:.3rem; display:block; }
        .form-check-label { font-size:.82rem; font-weight:400; color:#6b7280; }
        a { color:#1565C0; text-decoration:none; }
        a:hover { text-decoration:underline; }
        @media(max-width:768px) { .auth-left { display:none; } .auth-right { background:linear-gradient(135deg,#0A1628,#1565C0); } .auth-card { box-shadow:0 25px 80px rgba(0,0,0,.4); } }
    </style>
</head>
<body>
<div class="auth-left">
    <div class="auth-left-content">
        <div class="brand-mark"><i class="bi bi-bank2"></i></div>
        <h1>CoreAxis <br><span>Financial Services</span></h1>
        <p>Empowering financial growth with smart, secure banking solutions trusted across Bihar and beyond.</p>
        <div class="auth-stat">
            <div class="d-flex align-items-center gap-3">
                <div style="width:38px;height:38px;background:rgba(0,188,212,.2);border-radius:9px;display:flex;align-items:center;justify-content:center"><i class="bi bi-people-fill text-info fs-5"></i></div>
                <div><div class="auth-stat-num">50,000+</div><div class="auth-stat-label">Trusted Customers</div></div>
            </div>
        </div>
        <div class="auth-stat">
            <div class="d-flex align-items-center gap-3">
                <div style="width:38px;height:38px;background:rgba(76,175,80,.2);border-radius:9px;display:flex;align-items:center;justify-content:center"><i class="bi bi-currency-rupee text-success fs-5"></i></div>
                <div><div class="auth-stat-num">₹50 Crore+</div><div class="auth-stat-label">Total Deposits Managed</div></div>
            </div>
        </div>
        <div class="auth-stat">
            <div class="d-flex align-items-center gap-3">
                <div style="width:38px;height:38px;background:rgba(255,152,0,.2);border-radius:9px;display:flex;align-items:center;justify-content:center"><i class="bi bi-shield-fill-check text-warning fs-5"></i></div>
                <div><div class="auth-stat-num">10+ Years</div><div class="auth-stat-label">Serving Bihar & Beyond</div></div>
            </div>
        </div>
        <div class="auth-contact">
            <div><i class="bi bi-geo-alt me-2"></i>Samastipur, Bihar — 848101</div>
            <div><i class="bi bi-telephone me-2"></i>+91 9113107586</div>
            <div><i class="bi bi-envelope me-2"></i>support@coreaxis.cloud</div>
        </div>
    </div>
</div>
<div class="auth-right">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon"><i class="bi bi-bank2"></i></div>
            <div>
                <div style="font-weight:800;font-size:1.1rem;color:#0A1628">CoreAxis</div>
                <div style="font-size:.72rem;color:#94a3b8">Financial Management System</div>
            </div>
        </div>
        {{ $slot }}
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
