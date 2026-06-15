<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CoreAxis') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0A1628 0%, #0D47A1 50%, #1565C0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.3);
        }
        .brand-icon {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #00BCD4, #0097A7);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-icon i { color: #fff; font-size: 1.4rem; }
        .form-control, .form-select {
            border-radius: 10px;
            padding: .65rem 1rem;
            border: 1.5px solid #e2e8f0;
            font-size: .9rem;
            transition: all .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1565C0;
            box-shadow: 0 0 0 3px rgba(21,101,192,.12);
        }
        .btn-auth {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .75rem;
            font-weight: 600;
            font-size: .95rem;
            transition: all .3s;
        }
        .btn-auth:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(21,101,192,.35); color: #fff; }
        label { font-size: .85rem; font-weight: 500; color: #374151; margin-bottom: .35rem; }
        .form-check-label { font-size: .85rem; color: #6b7280; }
        a { color: #1565C0; }
        .divider { color: #9ca3af; font-size: .8rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="d-flex align-items-center gap-2 mb-4">
            <div class="brand-icon">
                <i class="bi bi-bank2"></i>
            </div>
            <div>
                <div class="fw-700 fs-5" style="font-weight:700;color:#0A1628">CoreAxis</div>
                <div style="font-size:.75rem;color:#6b7280">Financial Management</div>
            </div>
        </div>
        {{ $slot }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
