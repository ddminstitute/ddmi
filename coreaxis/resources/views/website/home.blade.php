@extends('website.layout')
@section('title','CoreAxis Financial')
@section('meta_desc','CoreAxis — Your trusted partner for personal & business banking, loans, savings accounts and more.')
@section('content')

{{-- HERO --}}
<section class="hero-section pt-5">
    <div class="container position-relative" style="z-index:2;padding-top:6rem;padding-bottom:6rem">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge mb-4"><i class="bi bi-shield-check"></i> RBI Compliant · Bank-Grade Security</div>
                <h1 class="hero-title mb-4">
                    Banking Made<br><span class="highlight">Simple, Smart</span><br>&amp; Secure
                </h1>
                <p class="hero-subtitle mb-5">CoreAxis gives you the power of a full-service bank in the palm of your hand. Open accounts, transfer funds, apply for loans — all in one place.</p>
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="{{ route('register') }}" class="btn btn-hero-primary"><i class="bi bi-rocket-takeoff me-2"></i>Open Free Account</a>
                    <a href="{{ route('account.plans') }}" class="btn btn-hero-outline"><i class="bi bi-grid me-2"></i>View Plans</a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat"><div class="hero-stat-num">50K+</div><div class="hero-stat-label">Happy Customers</div></div>
                    <div class="hero-stat"><div class="hero-stat-num">₹50 Cr+</div><div class="hero-stat-label">Assets Managed</div></div>
                    <div class="hero-stat"><div class="hero-stat-num">99.9%</div><div class="hero-stat-label">Uptime SLA</div></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3 float-anim">
                    <div class="col-12">
                        <div class="hero-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="small opacity-60">Total Balance</div>
                                    <div class="balance-display">₹24,580<span style="font-size:1.2rem">.00</span></div>
                                </div>
                                <div style="width:48px;height:48px;background:var(--accent);border-radius:12px;display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-wallet2 text-white fs-5"></i>
                                </div>
                            </div>
                            <div class="progress mb-2" style="height:6px;background:rgba(255,255,255,.15);border-radius:3px">
                                <div class="progress-bar" style="width:68%;background:var(--accent);border-radius:3px"></div>
                            </div>
                            <div class="d-flex justify-content-between small opacity-50"><span>Savings Goal</span><span>68%</span></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-card">
                            <div class="mini-icon mb-2" style="background:rgba(0,188,212,.2)"><i class="bi bi-arrow-down-circle text-accent"></i></div>
                            <div class="small opacity-60">Income</div>
                            <div class="fw-700 text-success">+₹5,200</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-card">
                            <div class="mini-icon mb-2" style="background:rgba(239,68,68,.15)"><i class="bi bi-arrow-up-circle" style="color:#f87171"></i></div>
                            <div class="small opacity-60">Expenses</div>
                            <div class="fw-700" style="color:#f87171">-₹1,840</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="hero-card">
                            <div class="small opacity-60 mb-2">Recent Activity</div>
                            <div class="mini-txn">
                                <div class="mini-icon" style="background:rgba(0,188,212,.2)"><i class="bi bi-shop text-accent"></i></div>
                                <div class="flex-grow-1"><div class="fw-600 small">Salary Credit</div><div class="opacity-50" style="font-size:.72rem">Today, 9:00 AM</div></div>
                                <div class="text-success fw-700">+₹3,500</div>
                            </div>
                            <div class="mini-txn">
                                <div class="mini-icon" style="background:rgba(255,179,0,.15)"><i class="bi bi-send" style="color:#FFB300"></i></div>
                                <div class="flex-grow-1"><div class="fw-600 small">Transfer Out</div><div class="opacity-50" style="font-size:.72rem">Yesterday</div></div>
                                <div style="color:#f87171" class="fw-700">-₹450</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Gradient bottom --}}
    <div style="position:absolute;bottom:0;left:0;right:0;height:80px;background:linear-gradient(transparent,#f8faff)"></div>
</section>

{{-- TRUST LOGOS --}}
<section class="bg-soft py-4 border-bottom">
    <div class="container">
        <div class="text-center text-muted small mb-3">Trusted & Certified By</div>
        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
            @foreach(['RBI Regulated','PCI DSS Certified','ISO 27001','256-bit SSL','UPI Enabled'] as $badge)
            <div class="d-flex align-items-center gap-2 px-3 py-2 bg-white rounded-pill border shadow-sm">
                <i class="bi bi-patch-check-fill text-primary"></i>
                <span class="small fw-semibold">{{ $badge }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SERVICES --}}
<section class="py-6 bg-soft" id="services" style="padding:5rem 0">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag mb-3"><i class="bi bi-stars"></i> What We Offer</div>
            <h2 class="section-title mb-3">Complete Banking Services<br>Under One Roof</h2>
            <p class="section-sub mx-auto" style="max-width:500px">From everyday banking to complex financial products — we've got everything you need.</p>
        </div>
        <div class="row g-4">
            @php
            $services = [
                ['icon'=>'bi-wallet2','color'=>'#1565C0','bg'=>'rgba(21,101,192,.08)','title'=>'Personal Accounts','desc'=>'Savings, Checking & Current accounts with zero fees and competitive interest rates.'],
                ['icon'=>'bi-arrow-left-right','color'=>'#00897B','bg'=>'rgba(0,137,123,.08)','title'=>'Fund Transfers','desc'=>'Instant domestic transfers with real-time tracking, NEFT/RTGS/UPI support.'],
                ['icon'=>'bi-credit-card','color'=>'#6A1B9A','bg'=>'rgba(106,27,154,.08)','title'=>'Loan Services','desc'=>'Personal, Home, Auto & Business loans with competitive rates and quick approval.'],
                ['icon'=>'bi-graph-up-arrow','color'=>'#E65100','bg'=>'rgba(230,81,0,.08)','title'=>'Fixed Deposits','desc'=>'Lock in your savings with high-yield fixed deposits for 6 to 60 months.'],
                ['icon'=>'bi-shield-lock','color'=>'#1565C0','bg'=>'rgba(21,101,192,.08)','title'=>'Secure Banking','desc'=>'Bank-grade 256-bit encryption, 2FA, and fraud monitoring protect every transaction.'],
                ['icon'=>'bi-bar-chart-line','color'=>'#00897B','bg'=>'rgba(0,137,123,.08)','title'=>'Analytics & Reports','desc'=>'Smart dashboards, spending insights, and detailed statements at your fingertips.'],
            ];
            @endphp
            @foreach($services as $s)
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:{{ $s['bg'] }}"><i class="bi {{ $s['icon'] }}" style="color:{{ $s['color'] }}"></i></div>
                    <h5 class="fw-700 mb-2">{{ $s['title'] }}</h5>
                    <p class="text-muted mb-0 small">{{ $s['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ACCOUNT PLANS PREVIEW --}}
<section class="py-5 bg-grid" style="padding:5rem 0!important">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag mb-3"><i class="bi bi-grid-3x3-gap"></i> Account Plans</div>
            <h2 class="section-title mb-3">Choose the Right Account for You</h2>
            <p class="section-sub mx-auto" style="max-width:500px">All accounts come with zero hidden fees, real-time notifications, and 24/7 support.</p>
        </div>
        <div class="row g-4 mb-4">
            @php
            $plans = [
                ['name'=>'Savings Account','icon'=>'bi-piggy-bank','color'=>'#1565C0','rate'=>'4.5% p.a.','fee'=>'₹0/month','min'=>'₹500','badge'=>null,'features'=>['High-yield interest','Unlimited deposits','3 free withdrawals/mo','Online & mobile banking','RBI insured deposits']],
                ['name'=>'Checking Account','icon'=>'bi-credit-card-2-front','color'=>'#fff','rate'=>'0.5% p.a.','fee'=>'₹0/month','min'=>'₹0','badge'=>'Most Popular','features'=>['Unlimited transactions','Free debit card','Overdraft protection','Bill pay included','UPI & NEFT support']],
                ['name'=>'Current Account','icon'=>'bi-building','color'=>'#1565C0','rate'=>'1.0% p.a.','fee'=>'₹500/month','min'=>'₹5,000','badge'=>null,'features'=>['Business-grade features','Multiple signatories','Bulk payment support','Dedicated manager','Priority support']],
                ['name'=>'Fixed Deposit','icon'=>'bi-safe','color'=>'#1565C0','rate'=>'Up to 8.5%','fee'=>'₹0 fees','min'=>'₹10,000','badge'=>'Best Returns','features'=>['Guaranteed returns','Flexible tenures 6–60 mo','Auto-renewal option','No market risk','Early withdrawal option']],
            ];
            @endphp
            @foreach($plans as $i => $plan)
            <div class="col-md-6 col-lg-3">
                <div class="plan-card {{ $i===1 ? 'featured' : '' }}">
                    @if($plan['badge'])<div class="plan-badge">{{ $plan['badge'] }}</div>@endif
                    <div class="mb-3">
                        <div style="width:52px;height:52px;border-radius:14px;background:{{ $i===1 ? 'rgba(255,255,255,.15)' : 'rgba(21,101,192,.08)' }};display:flex;align-items:center;justify-content:center;margin-bottom:1rem">
                            <i class="bi {{ $plan['icon'] }} fs-4" style="color:{{ $i===1 ? '#fff' : $plan['color'] }}"></i>
                        </div>
                        <h5 class="fw-700 mb-1">{{ $plan['name'] }}</h5>
                        <div class="d-flex align-items-baseline gap-1 mb-3">
                            <span style="font-size:1.6rem;font-weight:800;color:{{ $i===1 ? '#fff' : 'var(--primary)' }}">{{ $plan['rate'] }}</span>
                        </div>
                        <div class="d-flex gap-3 small mb-3 {{ $i===1 ? 'text-white opacity-75' : 'text-muted' }}">
                            <span><i class="bi bi-tag me-1"></i>{{ $plan['fee'] }}</span>
                            <span><i class="bi bi-coin me-1"></i>Min: {{ $plan['min'] }}</span>
                        </div>
                    </div>
                    @foreach($plan['features'] as $f)
                    <div class="plan-feature">
                        <i class="bi bi-check2-circle" style="color:{{ $i===1 ? 'var(--accent)' : 'var(--primary)' }}"></i>
                        <span>{{ $f }}</span>
                    </div>
                    @endforeach
                    <a href="{{ route('register') }}" class="btn w-100 mt-3 fw-semibold {{ $i===1 ? 'btn-light text-primary' : 'btn-outline-primary' }}">
                        Open Account
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center"><a href="{{ route('account.plans') }}" class="btn btn-primary px-5">View Full Plan Details <i class="bi bi-arrow-right ms-1"></i></a></div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-5 bg-soft" style="padding:5rem 0!important">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag mb-3"><i class="bi bi-list-ol"></i> How It Works</div>
            <h2 class="section-title">Get Started in 3 Simple Steps</h2>
        </div>
        <div class="row g-4 text-center">
            @foreach([['Register','Create your free account in minutes with just your email and basic info.','bi-person-plus'],['Choose a Plan','Pick the account type that fits your needs — savings, checking, or more.','bi-grid'],['Start Banking','Deposit funds, transfer money, apply for loans — all from your dashboard.','bi-lightning-charge']] as $i => $step)
            <div class="col-md-4 position-relative">
                @if($i < 2)<div class="step-connector d-none d-md-block"></div>@endif
                <div class="step-num">{{ $i+1 }}</div>
                <h5 class="fw-700 mb-2">{{ $step[0] }}</h5>
                <p class="text-muted small">{{ $step[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
<section class="py-5" style="padding:5rem 0!important">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag mb-3"><i class="bi bi-chat-quote"></i> Testimonials</div>
            <h2 class="section-title">Loved by Thousands of Customers</h2>
        </div>
        <div class="row g-4">
            @php
            $testimonials = [
                ['name'=>'Anjali Verma','role'=>'Freelance Designer','rating'=>5,'text'=>'CoreAxis completely changed how I manage my money. The savings account interest is incredible and the app is super intuitive.','initials'=>'AV'],
                ['name'=>'Rahul Gupta','role'=>'Small Business Owner','rating'=>5,'text'=>'Got my business loan approved within 48 hours. The process was seamless and the EMI calculator helped me plan perfectly.','initials'=>'RG'],
                ['name'=>'Priya Sharma','role'=>'Software Engineer','rating'=>5,'text'=>'The fixed deposit rates are unmatched. I\'ve been earning 8.5% on my savings. Highly recommend CoreAxis to everyone!','initials'=>'PS'],
            ];
            @endphp
            @foreach($testimonials as $t)
            <div class="col-md-4">
                <div class="testimonial-card h-100">
                    <div class="stars mb-3">@for($i=0;$i<$t['rating'];$i++)<i class="bi bi-star-fill"></i>@endfor</div>
                    <p class="mb-4 text-muted">"{{ $t['text'] }}"</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar" style="background:var(--primary)">{{ $t['initials'] }}</div>
                        <div><div class="fw-semibold small">{{ $t['name'] }}</div><div class="text-muted" style="font-size:.78rem">{{ $t['role'] }}</div></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section py-5" style="padding:5rem 0!important">
    <div class="container text-center position-relative" style="z-index:1">
        <h2 class="text-white fw-800 mb-3" style="font-size:clamp(1.8rem,4vw,3rem)">Ready to Take Control of<br>Your Financial Future?</h2>
        <p class="text-white opacity-60 mb-4 mx-auto" style="max-width:500px">Join over 50,000 customers who trust CoreAxis for their banking needs. Open your free account today.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-hero-primary px-5 py-3"><i class="bi bi-rocket-takeoff me-2"></i>Start for Free</a>
            <a href="{{ route('contact') }}" class="btn btn-hero-outline px-5 py-3"><i class="bi bi-telephone me-2"></i>Talk to Us</a>
        </div>
        <div class="mt-4 text-white opacity-50 small">
            <i class="bi bi-geo-alt me-1"></i>Samastipur, Bihar — 848101 &nbsp;|&nbsp;
            <i class="bi bi-telephone me-1"></i>+91 9113107586 &nbsp;|&nbsp;
            <i class="bi bi-envelope me-1"></i>support@coreaxis.cloud
        </div>
    </div>
</section>

@endsection
