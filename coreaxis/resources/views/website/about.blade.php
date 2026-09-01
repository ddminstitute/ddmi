@extends('website.layout')
@section('title','About CoreAxis')
@section('content')

{{-- Page Hero --}}
<section style="background:linear-gradient(135deg,#0D47A1,#1565C0);padding:8rem 0 5rem">
    <div class="container text-center">
        <div class="section-tag mb-3" style="color:#fff;border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.1)"><i class="bi bi-building"></i> About Us</div>
        <h1 class="text-white fw-800" style="font-size:clamp(2rem,5vw,3rem)">Built on Trust.<br>Driven by Innovation.</h1>
        <p class="text-white opacity-70 mt-3 mx-auto" style="max-width:500px">CoreAxis Financial has been transforming banking since 2010 — putting customers first in everything we do.</p>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="py-5 bg-soft" style="padding:4rem 0!important">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="section-tag mb-3"><i class="bi bi-compass"></i> Our Mission</div>
                <h2 class="section-title mb-3">Making Banking Simple for Everyone</h2>
                <p class="section-sub mb-4">We believe everyone deserves access to world-class financial services. CoreAxis was founded to break down barriers — whether you're a student opening your first account or a business scaling globally.</p>
                <div class="row g-3">
                    @foreach(['Customer First','Zero Hidden Fees','Bank-Grade Security','24/7 Support'] as $v)
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 bg-white p-3 rounded-3 border">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                            <span class="fw-semibold small">{{ $v }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    @foreach([['₹50 Cr+','Assets Under Management'],['50K+','Happy Customers'],['10+','Years of Service'],['99.9%','System Uptime']] as $stat)
                    <div class="col-6">
                        <div class="card border-0 text-center p-4 shadow-sm">
                            <div class="fw-800 text-primary" style="font-size:2.2rem">{{ $stat[0] }}</div>
                            <div class="text-muted small">{{ $stat[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Team --}}
<section style="padding:4rem 0">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag mb-3"><i class="bi bi-people"></i> Leadership</div>
            <h2 class="section-title">Meet Our Team</h2>
        </div>
        <div class="row g-4">
            @foreach([['CEO & Founder','Michael Chen','Leading CoreAxis since 2010 with 20+ years in fintech.','MC','#1565C0'],['CTO','Sarah Williams','Architect of our core banking platform & security systems.','SW','#00897B'],['Chief Risk Officer','David Kumar','Ensuring regulatory compliance and risk management excellence.','DK','#6A1B9A'],['Head of Customer Success','Lisa Park','Dedicated to making every customer experience exceptional.','LP','#E65100']] as $m)
            <div class="col-md-6 col-lg-3 text-center">
                <div class="card border-0 shadow-sm p-4">
                    <div class="avatar mx-auto mb-3" style="width:72px;height:72px;background:{{ $m[4] }};font-size:1.5rem">{{ $m[3] }}</div>
                    <h6 class="fw-700 mb-0">{{ $m[1] }}</h6>
                    <div class="text-primary small mb-2">{{ $m[0] }}</div>
                    <p class="text-muted" style="font-size:.82rem">{{ $m[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Values --}}
<section class="py-5 bg-soft" style="padding:4rem 0!important">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag mb-3"><i class="bi bi-heart"></i> Our Values</div>
            <h2 class="section-title">What Drives Us</h2>
        </div>
        <div class="row g-4">
            @foreach([['bi-shield-check','Trust & Transparency','We operate with complete honesty and transparency in all our dealings.','rgba(21,101,192,.08)','#1565C0'],['bi-lightning-charge','Innovation','We continuously innovate to bring you the best financial technology.','rgba(0,137,123,.08)','#00897B'],['bi-people','Community','We support the communities where our customers live and work.','rgba(106,27,154,.08)','#6A1B9A'],['bi-award','Excellence','We hold ourselves to the highest standards in everything we do.','rgba(230,81,0,.08)','#E65100']] as $v)
            <div class="col-md-6 col-lg-3 text-center">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto" style="background:{{ $v[3] }}"><i class="bi {{ $v[0] }}" style="color:{{ $v[4] }}"></i></div>
                    <h6 class="fw-700 mb-2">{{ $v[1] }}</h6>
                    <p class="text-muted small mb-0">{{ $v[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="cta-section py-5" style="padding:4rem 0!important">
    <div class="container text-center position-relative" style="z-index:1">
        <h2 class="text-white fw-800 mb-3">Join the CoreAxis Family</h2>
        <a href="{{ route('register') }}" class="btn btn-hero-primary px-5 py-3 mt-2"><i class="bi bi-person-plus me-2"></i>Open Your Account</a>
    </div>
</section>
@endsection
