@extends('website.layout')
@section('title','Banking Services')
@section('content')
<section style="background:linear-gradient(135deg,#0D47A1,#1565C0);padding:8rem 0 5rem">
    <div class="container text-center">
        <div class="section-tag mb-3" style="color:#fff;border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.1)"><i class="bi bi-grid"></i> Services</div>
        <h1 class="text-white fw-800" style="font-size:clamp(2rem,5vw,3rem)">Full-Service Banking<br>for Every Need</h1>
        <p class="text-white opacity-70 mt-3 mx-auto" style="max-width:500px">Explore our comprehensive suite of financial products designed for individuals and businesses.</p>
    </div>
</section>

@php
$services = [
    ['icon'=>'bi-piggy-bank','color'=>'#1565C0','bg'=>'rgba(21,101,192,.08)','title'=>'Personal Banking','desc'=>'Everything you need for daily banking — savings, checking, and current accounts with competitive rates and zero hidden fees.','features'=>['Multiple account types','Free debit card','Mobile banking app','24/7 account access','Real-time notifications']],
    ['icon'=>'bi-arrow-left-right','color'=>'#00897B','bg'=>'rgba(0,137,123,.08)','title'=>'Fund Transfers','desc'=>'Move money instantly between accounts or to any bank worldwide with low transfer fees and real-time confirmation.','features'=>['Instant domestic transfers','International SWIFT transfers','Bulk payment support','Transfer scheduling','Real-time tracking']],
    ['icon'=>'bi-credit-card','color'=>'#6A1B9A','bg'=>'rgba(106,27,154,.08)','title'=>'Loan Services','desc'=>'Get the financing you need — personal, home, auto, or business loans — with transparent terms and quick approvals.','features'=>['Personal loans up to ₹5 Lakh','Home loans up to ₹1 Crore','Auto financing','Business loans','EMI calculator & planner']],
    ['icon'=>'bi-safe','color'=>'#E65100','bg'=>'rgba(230,81,0,.08)','title'=>'Fixed Deposits','desc'=>'Grow your savings with guaranteed high-yield fixed deposits. Lock in rates from 6 months up to 5 years.','features'=>['Up to 8.5% interest p.a.','Flexible tenures','Auto-renewal option','Premature withdrawal','No hidden charges']],
    ['icon'=>'bi-bar-chart-line','color'=>'#1565C0','bg'=>'rgba(21,101,192,.08)','title'=>'Financial Analytics','desc'=>'Smart dashboards and detailed reports give you a clear picture of your financial health at all times.','features'=>['Spending analysis','Income vs expense charts','Custom date reports','Export to PDF/CSV','Budget tracking']],
    ['icon'=>'bi-shield-lock','color'=>'#00897B','bg'=>'rgba(0,137,123,.08)','title'=>'Security & Fraud Protection','desc'=>'Your money is protected by bank-grade security, 2FA authentication, and 24/7 real-time fraud monitoring.','features'=>['256-bit SSL encryption','Two-factor authentication','Fraud monitoring','Account freeze option','Instant alerts']],
];
@endphp

<section style="padding:5rem 0">
    <div class="container">
        @foreach($services as $i => $s)
        <div class="row g-4 align-items-center mb-5 {{ $i%2==1 ? 'flex-row-reverse' : '' }}">
            <div class="col-lg-6">
                <div class="feature-icon mb-3" style="background:{{ $s['bg'] }};width:72px;height:72px;border-radius:20px;font-size:2rem">
                    <i class="bi {{ $s['icon'] }}" style="color:{{ $s['color'] }}"></i>
                </div>
                <h3 class="fw-800 mb-3">{{ $s['title'] }}</h3>
                <p class="section-sub mb-4">{{ $s['desc'] }}</p>
                <div class="row g-2">
                    @foreach($s['features'] as $f)
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check2 text-primary fw-bold"></i>
                            <span class="small">{{ $f }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('register') }}" class="btn btn-primary mt-4 px-4">Get Started <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg p-4" style="border-radius:24px;background:linear-gradient(135deg,{{ $s['bg'] }},rgba(255,255,255,.5))">
                    <div style="height:250px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi {{ $s['icon'] }}" style="font-size:8rem;color:{{ $s['color'] }};opacity:.2"></i>
                    </div>
                </div>
            </div>
        </div>
        @if($i < count($services)-1)<hr class="my-5 opacity-10">@endif
        @endforeach
    </div>
</section>

<section class="cta-section py-5">
    <div class="container text-center position-relative" style="z-index:1">
        <h2 class="text-white fw-800 mb-3">Ready to Experience Better Banking?</h2>
        <a href="{{ route('register') }}" class="btn btn-hero-primary px-5 py-3 mt-2">Open Free Account</a>
    </div>
</section>
@endsection
