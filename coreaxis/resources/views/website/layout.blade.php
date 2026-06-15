<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CoreAxis Financial') — Empowering Your Financial Future</title>
    <meta name="description" content="@yield('meta_desc', 'CoreAxis Financial — Secure, smart banking with savings, checking, loans and more.')">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1565C0;
            --primary-dark: #0D47A1;
            --primary-light: #1976D2;
            --accent: #00BCD4;
            --accent2: #FFB300;
            --dark: #0A1628;
            --dark2: #0F2044;
            --text: #1a1a2e;
        }
        * { font-family: 'Inter', sans-serif; }
        body { color: var(--text); overflow-x: hidden; }

        /* Navbar */
        #mainNav {
            background: rgba(10, 22, 40, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s;
            padding: .8rem 0;
        }
        #mainNav.scrolled { background: rgba(10,22,40,0.97); padding: .5rem 0; }
        .brand-icon { width:36px;height:36px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0 }
        .brand-icon i { color:#fff;font-size:1.1rem; }
        .navbar-nav .nav-link { font-size:.875rem;font-weight:500;border-radius:6px;transition:all .2s; }
        .navbar-nav .nav-link:hover,.navbar-nav .nav-link.active { background:rgba(255,255,255,.1); }
        .fw-800 { font-weight:800 }

        /* Hero */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark2) 50%, #1a2d5a 100%);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        .hero-section::before {
            content:'';
            position:absolute;inset:0;
            background: radial-gradient(ellipse at 30% 60%, rgba(0,188,212,0.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 75% 20%, rgba(21,101,192,0.2) 0%, transparent 50%);
        }
        .hero-badge { background:rgba(0,188,212,.15);border:1px solid rgba(0,188,212,.3);color:var(--accent);display:inline-flex;align-items:center;gap:6px;padding:.35rem .85rem;border-radius:50px;font-size:.8rem;font-weight:600 }
        .hero-title { font-size:clamp(2.2rem,5vw,3.8rem);font-weight:900;color:#fff;line-height:1.1 }
        .hero-title .highlight { background:linear-gradient(135deg,var(--accent),#80DEEA);-webkit-background-clip:text;-webkit-text-fill-color:transparent }
        .hero-subtitle { color:rgba(255,255,255,.65);font-size:1.05rem;line-height:1.7;max-width:520px }
        .btn-hero-primary { background:linear-gradient(135deg,var(--accent),#0097A7);border:none;color:#fff;padding:.8rem 2rem;border-radius:50px;font-weight:700;font-size:.95rem;transition:all .3s;box-shadow:0 8px 30px rgba(0,188,212,.3) }
        .btn-hero-primary:hover { transform:translateY(-2px);box-shadow:0 12px 40px rgba(0,188,212,.4);color:#fff }
        .btn-hero-outline { background:transparent;border:2px solid rgba(255,255,255,.25);color:#fff;padding:.78rem 2rem;border-radius:50px;font-weight:600;font-size:.95rem;transition:all .3s }
        .btn-hero-outline:hover { border-color:rgba(255,255,255,.6);color:#fff;background:rgba(255,255,255,.08) }
        .hero-stats { display:flex;gap:2rem;flex-wrap:wrap }
        .hero-stat { }
        .hero-stat-num { font-size:1.8rem;font-weight:900;color:#fff }
        .hero-stat-label { font-size:.75rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:1px }

        /* Floating card */
        .hero-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.5rem;
            color: #fff;
        }
        .balance-display { font-size:2.2rem;font-weight:800 }
        .mini-txn { display:flex;align-items:center;gap:.75rem;padding:.5rem;border-radius:10px;background:rgba(255,255,255,.06);margin-bottom:.4rem }
        .mini-icon { width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0 }

        /* Section styles */
        .section-tag { display:inline-flex;align-items:center;gap:6px;background:rgba(21,101,192,.08);color:var(--primary);border:1px solid rgba(21,101,192,.2);padding:.3rem .8rem;border-radius:50px;font-size:.78rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase }
        .section-title { font-size:clamp(1.6rem,3vw,2.4rem);font-weight:800;color:var(--text);line-height:1.2 }
        .section-sub { color:#64748b;font-size:1rem;line-height:1.7 }

        /* Feature cards */
        .feature-card { background:#fff;border-radius:20px;padding:2rem;border:1px solid #f1f5f9;transition:all .3s;height:100% }
        .feature-card:hover { transform:translateY(-6px);box-shadow:0 20px 60px rgba(0,0,0,.1);border-color:rgba(21,101,192,.15) }
        .feature-icon { width:60px;height:60px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1.25rem }

        /* Account plan cards */
        .plan-card { background:#fff;border-radius:24px;padding:2rem;border:2px solid #f1f5f9;transition:all .3s;position:relative;overflow:hidden;height:100% }
        .plan-card:hover { border-color:var(--primary);box-shadow:0 20px 60px rgba(21,101,192,.12) }
        .plan-card.featured { border-color:var(--primary);background:linear-gradient(135deg,#0D47A1 0%,#1565C0 100%);color:#fff }
        .plan-card.featured .text-muted { color:rgba(255,255,255,.65)!important }
        .plan-card.featured .plan-feature { color:#fff!important }
        .plan-card.featured .plan-feature i { color:var(--accent)!important }
        .plan-badge { position:absolute;top:1rem;right:1rem;background:var(--accent2);color:#000;font-size:.7rem;font-weight:700;padding:.25rem .7rem;border-radius:50px;text-transform:uppercase;letter-spacing:.5px }
        .plan-price { font-size:2.5rem;font-weight:900 }
        .plan-price sup { font-size:1.2rem;font-weight:700;vertical-align:super }
        .plan-price sub { font-size:.85rem;font-weight:400;opacity:.7 }
        .plan-feature { display:flex;align-items:flex-start;gap:.6rem;margin-bottom:.6rem;font-size:.9rem }
        .plan-feature i { color:var(--primary);margin-top:3px;flex-shrink:0 }

        /* How it works */
        .step-num { width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;font-size:1.2rem;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem }
        .step-connector { position:absolute;top:28px;left:calc(50% + 28px);width:calc(100% - 56px);height:2px;background:linear-gradient(90deg,var(--primary),var(--primary-light));opacity:.25 }

        /* Testimonials */
        .testimonial-card { background:#fff;border-radius:20px;padding:2rem;border:1px solid #f1f5f9;transition:all .3s }
        .testimonial-card:hover { box-shadow:0 10px 40px rgba(0,0,0,.08) }
        .stars { color:#FFB300;font-size:.9rem }
        .avatar { width:50px;height:50px;border-radius:50%;object-fit:cover;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem }

        /* CTA section */
        .cta-section { background:linear-gradient(135deg,var(--dark) 0%,var(--dark2) 100%);position:relative;overflow:hidden }
        .cta-section::before { content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(0,188,212,.1),transparent 60%),radial-gradient(ellipse at 80% 50%,rgba(21,101,192,.15),transparent 60%) }

        /* Footer */
        .footer-section { background:var(--dark);color:#fff }
        .footer-link { color:rgba(255,255,255,.5);text-decoration:none;transition:.2s;font-size:.875rem }
        .footer-link:hover { color:var(--accent) }
        .social-btn { width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);display:flex;align-items:center;justify-content:center;text-decoration:none;transition:.2s;font-size:.9rem }
        .social-btn:hover { background:var(--accent);border-color:var(--accent);color:#fff }
        .text-accent { color:var(--accent) }

        /* Background sections */
        .bg-soft { background:#f8faff }
        .bg-grid { background-color:#fff;background-image:radial-gradient(#e2e8f0 1.5px,transparent 1.5px);background-size:28px 28px }

        /* Animations */
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-15px)} }
        @keyframes pulse-ring { 0%{transform:scale(.9);opacity:.7} 100%{transform:scale(1.3);opacity:0} }
        .float-anim { animation:float 5s ease-in-out infinite }
        .float-anim-2 { animation:float 7s ease-in-out infinite 2s }

        /* Mobile responsive */
        @media(max-width:991px){
            #navMenu { background:rgba(10,22,40,.97);border-radius:12px;margin-top:.5rem;padding:1rem;border:1px solid rgba(255,255,255,.08) }
            .navbar-nav .nav-link { padding:.6rem 1rem!important }
            .d-flex.gap-2.mt-3 { justify-content:center }
        }
        @media(max-width:767px){
            .hero-section { min-height:auto;padding-bottom:3rem }
            .hero-title { font-size:clamp(1.8rem,7vw,2.8rem) }
            .hero-subtitle { font-size:.95rem }
            .hero-stats { gap:1.2rem }
            .hero-stat-num { font-size:1.4rem }
            .float-anim { animation:none }
            .balance-display { font-size:1.6rem }
            .section-title { font-size:clamp(1.4rem,5vw,2rem) }
            .plan-price { font-size:2rem }
            .step-connector { display:none!important }
            .cta-section h2 { font-size:clamp(1.5rem,6vw,2.2rem) }
        }
        @media(max-width:575px){
            .feature-card { padding:1.25rem }
            .plan-card { padding:1.25rem }
            .testimonial-card { padding:1.25rem }
            .hero-card { padding:1rem }
            section[style*="padding:5rem"] { padding:3rem 0!important }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('website.partials.navbar')

    @yield('content')

    @include('website.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', () => {
            document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>
    @stack('scripts')
</body>
</html>
