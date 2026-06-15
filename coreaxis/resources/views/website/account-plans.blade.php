@extends('website.layout')
@section('title','Account Plans')
@section('content')

<section style="background:linear-gradient(135deg,#0D47A1,#1565C0);padding:8rem 0 5rem">
    <div class="container text-center">
        <div class="section-tag mb-3" style="color:#fff;border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.1)"><i class="bi bi-grid-3x3-gap"></i> Account Plans</div>
        <h1 class="text-white fw-800" style="font-size:clamp(2rem,5vw,3rem)">Find Your Perfect<br>Banking Account</h1>
        <p class="text-white opacity-70 mt-3 mx-auto" style="max-width:500px">Compare all our account types side by side. No hidden fees, no surprises — just transparent banking.</p>
    </div>
</section>

{{-- Plans Grid --}}
<section class="py-5 bg-grid" style="padding:5rem 0!important">
    <div class="container">
        <div class="row g-4 mb-5">
            {{-- Savings --}}
            <div class="col-lg-6">
                <div class="plan-card h-100" style="border-color:#1565C0">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div style="width:56px;height:56px;border-radius:16px;background:rgba(21,101,192,.1);display:flex;align-items:center;justify-content:center;margin-bottom:.75rem">
                                <i class="bi bi-piggy-bank fs-3 text-primary"></i>
                            </div>
                            <h3 class="fw-800 mb-1">Savings Account</h3>
                            <p class="text-muted">Perfect for individuals looking to grow their money securely.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-4 p-3 rounded-3" style="background:rgba(21,101,192,.06)">
                        <span style="font-size:3rem;font-weight:900;color:var(--primary)">4.5%</span>
                        <div><div class="fw-600 text-primary">APY Interest</div><div class="text-muted small">Compounded monthly</div></div>
                    </div>
                    <div class="row g-2 mb-4">
                        @php $features = [
                            ['Minimum Opening Deposit','$100'],['Monthly Maintenance Fee','FREE'],['Interest Rate','4.5% APY'],['Withdrawals per Month','3 Free'],
                            ['Online Banking','Yes'],['Mobile App Access','Yes'],['ATM Card','Free'],['FDIC Insurance','Up to $250,000'],
                            ['Auto-Save Features','Yes'],['Account Statements','Monthly & On-Demand'],
                        ]; @endphp
                        @foreach($features as $f)
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background:#f8faff">
                                <span class="small text-muted">{{ $f[0] }}</span>
                                <span class="small fw-semibold">{{ $f[1] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100 py-3 fw-semibold">Open Savings Account <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>

            {{-- Checking --}}
            <div class="col-lg-6">
                <div class="plan-card featured h-100">
                    <div class="plan-badge">Most Popular</div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div style="width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;margin-bottom:.75rem">
                                <i class="bi bi-credit-card-2-front fs-3 text-white"></i>
                            </div>
                            <h3 class="fw-800 mb-1 text-white">Checking Account</h3>
                            <p style="color:rgba(255,255,255,.7)">Ideal for everyday spending with no transaction limits.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-4 p-3 rounded-3" style="background:rgba(255,255,255,.12)">
                        <span style="font-size:3rem;font-weight:900;color:#fff">0.5%</span>
                        <div><div class="fw-600 text-white">APY Interest</div><div style="color:rgba(255,255,255,.6)" class="small">On daily balance</div></div>
                    </div>
                    <div class="row g-2 mb-4">
                        @php $features = [
                            ['Minimum Opening Deposit','$0'],['Monthly Maintenance Fee','FREE'],['Interest Rate','0.5% APY'],['Transactions per Month','Unlimited'],
                            ['Online Banking','Yes'],['Mobile App Access','Yes'],['Debit Card','Free Visa Card'],['FDIC Insurance','Up to $250,000'],
                            ['Overdraft Protection','Yes'],['Bill Pay','Included Free'],
                        ]; @endphp
                        @foreach($features as $f)
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background:rgba(255,255,255,.1)">
                                <span class="small" style="color:rgba(255,255,255,.7)">{{ $f[0] }}</span>
                                <span class="small fw-semibold text-white">{{ $f[1] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-light w-100 py-3 fw-semibold text-primary">Open Checking Account <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>

            {{-- Current --}}
            <div class="col-lg-6">
                <div class="plan-card h-100" style="border-color:#6A1B9A">
                    <div style="width:56px;height:56px;border-radius:16px;background:rgba(106,27,154,.1);display:flex;align-items:center;justify-content:center;margin-bottom:.75rem">
                        <i class="bi bi-building fs-3" style="color:#6A1B9A"></i>
                    </div>
                    <h3 class="fw-800 mb-1">Current Account</h3>
                    <p class="text-muted">Designed for businesses and high-frequency transaction users.</p>
                    <div class="d-flex align-items-baseline gap-2 mb-4 p-3 rounded-3" style="background:rgba(106,27,154,.06)">
                        <span style="font-size:3rem;font-weight:900;color:#6A1B9A">1.0%</span>
                        <div><div class="fw-600" style="color:#6A1B9A">APY Interest</div><div class="text-muted small">On average balance</div></div>
                    </div>
                    <div class="row g-2 mb-4">
                        @php $features = [
                            ['Minimum Opening Deposit','$500'],['Monthly Maintenance Fee','$15/mo'],['Interest Rate','1.0% APY'],['Transactions per Month','Unlimited'],
                            ['Online Banking','Yes'],['Mobile App Access','Yes'],['Multiple Signatories','Yes'],['Bulk Payments','Supported'],
                            ['Dedicated Manager','Yes'],['Priority Support','24/7'],
                        ]; @endphp
                        @foreach($features as $f)
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background:#f8faff">
                                <span class="small text-muted">{{ $f[0] }}</span>
                                <span class="small fw-semibold">{{ $f[1] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('register') }}" class="btn w-100 py-3 fw-semibold" style="background:#6A1B9A;color:#fff">Open Current Account <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>

            {{-- Fixed Deposit --}}
            <div class="col-lg-6">
                <div class="plan-card h-100" style="border-color:#E65100;position:relative">
                    <div class="plan-badge" style="background:#E65100;color:#fff">Best Returns</div>
                    <div style="width:56px;height:56px;border-radius:16px;background:rgba(230,81,0,.1);display:flex;align-items:center;justify-content:center;margin-bottom:.75rem">
                        <i class="bi bi-safe fs-3" style="color:#E65100"></i>
                    </div>
                    <h3 class="fw-800 mb-1">Fixed Deposit</h3>
                    <p class="text-muted">Lock your funds and earn guaranteed higher returns over time.</p>
                    <div class="d-flex align-items-baseline gap-2 mb-4 p-3 rounded-3" style="background:rgba(230,81,0,.06)">
                        <span style="font-size:3rem;font-weight:900;color:#E65100">8.5%</span>
                        <div><div class="fw-600" style="color:#E65100">Max APY</div><div class="text-muted small">For 5-year tenure</div></div>
                    </div>
                    {{-- Tenure Rate Table --}}
                    <div class="mb-4">
                        <div class="fw-semibold small mb-2">Interest Rates by Tenure</div>
                        @foreach([['6 Months','5.5%'],['1 Year','6.5%'],['2 Years','7.0%'],['3 Years','7.5%'],['5 Years','8.5%']] as $r)
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-2 mb-1" style="background:#f8faff">
                            <span class="small text-muted">{{ $r[0] }}</span>
                            <span class="small fw-bold" style="color:#E65100">{{ $r[1] }} p.a.</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="row g-2 mb-4">
                        @foreach([['Minimum Deposit','$1,000'],['Maximum Deposit','No Limit'],['Premature Withdrawal','Allowed (penalty applies)'],['Auto Renewal','Yes']] as $f)
                        <div class="col-12">
                            <div class="d-flex justify-content-between p-2 rounded-2" style="background:#f8faff">
                                <span class="small text-muted">{{ $f[0] }}</span><span class="small fw-semibold">{{ $f[1] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('register') }}" class="btn w-100 py-3 fw-semibold" style="background:#E65100;color:#fff">Open Fixed Deposit <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Comparison Table --}}
<section class="py-5 bg-soft" style="padding:5rem 0!important">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag mb-3"><i class="bi bi-table"></i> Comparison</div>
            <h2 class="section-title">Side-by-Side Comparison</h2>
        </div>
        <div class="card border-0 shadow-sm" style="border-radius:20px;overflow:hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background:var(--primary);color:#fff">
                            <th class="py-3 ps-4">Feature</th>
                            <th class="py-3 text-center">Savings</th>
                            <th class="py-3 text-center" style="background:#0D47A1">Checking</th>
                            <th class="py-3 text-center">Current</th>
                            <th class="py-3 text-center">Fixed Deposit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            ['Interest Rate','4.5% APY','0.5% APY','1.0% APY','Up to 8.5%'],
                            ['Min. Opening','$100','$0','$500','$1,000'],
                            ['Monthly Fee','Free','Free','$15','Free'],
                            ['Transactions','3/month','Unlimited','Unlimited','On maturity'],
                            ['Debit Card','Yes','Yes (Visa)','Yes','No'],
                            ['Online Banking','✓','✓','✓','✓'],
                            ['ATM Access','Yes','Yes (rebates)','Yes','No'],
                            ['Overdraft Protection','No','Yes','Yes','No'],
                            ['Business Use','No','Limited','Yes','Yes'],
                            ['Best For','Personal savings','Daily spending','Business use','Long-term growth'],
                        ] as $row)
                        <tr>
                            <td class="ps-4 fw-semibold small">{{ $row[0] }}</td>
                            @for($i=1;$i<=4;$i++)<td class="text-center small">{{ $row[$i] }}</td>@endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section style="padding:5rem 0">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag mb-3"><i class="bi bi-question-circle"></i> FAQs</div>
            <h2 class="section-title">Frequently Asked Questions</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    @foreach([
                        ['Can I have multiple accounts?','Yes! You can open multiple account types. For example, a Savings account for your emergency fund and a Checking account for daily expenses.'],
                        ['Is there a minimum balance requirement?','Savings accounts require $100 minimum. Checking accounts have no minimum. Current accounts require $500. Fixed Deposits start at $1,000.'],
                        ['How long does it take to open an account?','Most accounts are opened instantly after registration. You can start banking within minutes of completing the online form.'],
                        ['Are my deposits insured?','Yes. All deposits are FDIC insured up to $250,000 per depositor per account category.'],
                        ['Can I switch account types later?','Yes. You can open additional account types or upgrade anytime. Contact our support team for assistance.'],
                    ] as $i => $faq)
                    <div class="accordion-item border-0 mb-2 rounded-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $i>0?'collapsed':'' }} fw-semibold rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">{{ $faq[0] }}</button>
                        </h2>
                        <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i==0?'show':'' }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">{{ $faq[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section py-5">
    <div class="container text-center position-relative" style="z-index:1">
        <h2 class="text-white fw-800 mb-3">Ready to Open Your Account?</h2>
        <p class="text-white opacity-60 mb-4">Takes less than 5 minutes. No paperwork required.</p>
        <a href="{{ route('register') }}" class="btn btn-hero-primary px-5 py-3">Get Started for Free <i class="bi bi-arrow-right ms-2"></i></a>
    </div>
</section>
@endsection
