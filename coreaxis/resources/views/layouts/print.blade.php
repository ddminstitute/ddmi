<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('print-title','Document') — CoreAxis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Segoe UI', sans-serif; }
        body { font-size: 13px; background: #f5f5f5; }
        .print-page { max-width: 800px; margin: 20px auto; background: #fff; padding: 2rem; box-shadow: 0 2px 20px rgba(0,0,0,.1); }
        .company-header { border-bottom: 3px solid #1565C0; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .company-name { font-size: 1.4rem; font-weight: 800; color: #0D47A1; }
        .company-sub { font-size: .8rem; color: #666; }
        .doc-title { background: linear-gradient(135deg,#1565C0,#0D47A1); color: #fff; padding: .5rem 1.25rem; border-radius: 8px; font-size: .95rem; font-weight: 700; display: inline-block; margin-bottom: 1.25rem; }
        .info-row { display: flex; gap: 1rem; margin-bottom: .3rem; font-size: .85rem; }
        .info-label { color: #666; min-width: 140px; font-weight: 600; }
        .print-btn-bar { position: fixed; bottom: 20px; right: 20px; display: flex; gap: .5rem; }
        .table th { background: #f8f9fa; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
        .table td { font-size: .83rem; }
        @media print {
            .print-btn-bar { display: none !important; }
            body { background: #fff; }
            .print-page { box-shadow: none; margin: 0; }
        }
    </style>
</head>
<body>
<div class="print-page">
    <div class="company-header d-flex justify-content-between align-items-start">
        <div>
            <div class="company-name"><i class="bi bi-bank2 me-2"></i>CoreAxis Financial Services</div>
            <div class="company-sub">
                <i class="bi bi-geo-alt me-1"></i>Samastipur, Bihar — 848101 &nbsp;|&nbsp;
                <i class="bi bi-telephone me-1"></i>+91 9113107586 &nbsp;|&nbsp;
                <i class="bi bi-envelope me-1"></i>support@coreaxis.cloud
            </div>
        </div>
        <div class="text-end">
            <div style="font-size:.8rem;color:#888">Printed: {{ now()->format('d M Y h:i A') }}</div>
            <div style="font-size:.75rem;color:#bbb">coreaxis.cloud</div>
        </div>
    </div>
    @yield('print-content')
    <div class="text-center mt-4 pt-3 border-top" style="font-size:.75rem;color:#aaa">
        This is a computer-generated document. CoreAxis Financial Services, Samastipur, Bihar.
    </div>
</div>
<div class="print-btn-bar">
    <button onclick="window.print()" class="btn btn-primary btn-sm shadow"><i class="bi bi-printer me-1"></i>Print</button>
    <button onclick="window.close()" class="btn btn-secondary btn-sm">Close</button>
</div>
</body>
</html>
