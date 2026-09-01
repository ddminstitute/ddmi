<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','CoreAxis') — CoreAxis Financial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root{--pri:#1565C0;--pri-d:#0D47A1;--acc:#00BCD4;--sw:262px;--sw-c:62px;}
        *{font-family:'Segoe UI',system-ui,sans-serif;}
        body{background:#f0f2f5;}

        /* ── SIDEBAR ── */
        .sidebar{width:var(--sw);height:100vh;background:linear-gradient(180deg,#050d1a 0%,#0D47A1 60%,#1565C0 100%);position:fixed;top:0;left:0;z-index:1050;box-shadow:4px 0 20px rgba(0,0,0,.25);overflow-y:auto;overflow-x:hidden;transition:transform .28s ease,width .28s ease;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.2) transparent;}
        .sidebar::-webkit-scrollbar{width:4px;}
        .sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.25);border-radius:4px;}
        .sidebar::-webkit-scrollbar-track{background:transparent;}

        /* Desktop collapse */
        body.sb-col .sidebar{width:var(--sw-c);}
        body.sb-col .sb-label{display:none!important;}
        body.sb-col .nav-sec{display:none!important;}
        body.sb-col .sb-brand-text{display:none!important;}
        body.sb-col .sb-acc-btn{justify-content:center;padding:.6rem;}
        body.sb-col .sb-acc-btn .bi-chevron-down{display:none;}
        body.sb-col .acc-children{display:none!important;}
        body.sb-col .direct-lnk{justify-content:center;padding:.6rem;}
        body.sb-col .main-content{margin-left:var(--sw-c);}

        /* Mobile: sidebar hidden off-screen by default */
        @media(max-width:767px){
            .sidebar{transform:translateX(-100%);width:var(--sw)!important;}
            body.sb-open .sidebar{transform:translateX(0);}
            .main-content{margin-left:0!important;}
            .sb-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1040;}
            body.sb-open .sb-overlay{display:block;}
            body.sb-col .sidebar{width:var(--sw)!important;}
        }

        /* ── BRAND ── */
        .sb-brand{padding:.9rem 1rem;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:.65rem;overflow:hidden;white-space:nowrap;}
        .sb-icon{width:36px;height:36px;background:var(--acc);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .sb-icon i{color:#fff;font-size:1.1rem;}
        .sb-brand-text h6{color:#fff;margin:0;font-weight:700;font-size:.9rem;}
        .sb-brand-text small{color:rgba(255,255,255,.45);font-size:.65rem;}

        /* ── NAV SECTIONS ── */
        .nav-sec{color:rgba(255,255,255,.3);font-size:.62rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:.85rem 1rem .2rem;white-space:nowrap;}
        .sb-acc-btn{display:flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.72);padding:.55rem .9rem;border-radius:8px;margin:1px 7px;font-size:.82rem;background:transparent;border:none;width:calc(100% - 14px);text-align:left;cursor:pointer;transition:all .2s;white-space:nowrap;}
        .sb-acc-btn:hover{background:rgba(255,255,255,.1);color:#fff;}
        .sb-acc-btn.open{background:rgba(255,255,255,.12);color:#fff;}
        .sb-acc-btn>i:first-child{width:17px;flex-shrink:0;font-size:.88rem;}
        .sb-acc-btn .bi-chevron-down{margin-left:auto;font-size:.65rem;transition:transform .22s;}
        .sb-acc-btn.open .bi-chevron-down{transform:rotate(180deg);}
        .acc-children{padding-left:.4rem;}
        .child-lnk{display:flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.58);padding:.45rem .85rem;border-radius:7px;margin:1px 7px;font-size:.79rem;text-decoration:none;transition:all .2s;white-space:nowrap;}
        .child-lnk:hover{background:rgba(255,255,255,.09);color:#fff;}
        .child-lnk.active{background:rgba(255,255,255,.17);color:#fff;font-weight:600;}
        .child-lnk>i{width:15px;flex-shrink:0;}
        .direct-lnk{display:flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.72);padding:.55rem .9rem;border-radius:8px;margin:1px 7px;font-size:.82rem;text-decoration:none;transition:all .2s;white-space:nowrap;}
        .direct-lnk:hover,.direct-lnk.active{background:rgba(255,255,255,.14);color:#fff;}
        .direct-lnk>i{width:17px;flex-shrink:0;font-size:.88rem;}

        /* ── SECTION GROUP ── */
        .sec-group-btn{width:100%;background:none;border:none;border-top:1px solid rgba(255,255,255,.07);color:rgba(255,255,255,.45);display:flex;align-items:center;justify-content:space-between;padding:.42rem .9rem;margin-top:.3rem;cursor:pointer;transition:all .2s;font-size:.7rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;}
        .sec-group-btn:hover{color:rgba(255,255,255,.75);background:rgba(255,255,255,.04);}
        .sec-group-btn.collapsed{color:rgba(255,255,255,.3);}
        .sec-group-label{display:flex;align-items:center;gap:.3rem;}
        .sec-chevron{font-size:.7rem;transition:transform .25s;flex-shrink:0;}
        .sec-group-btn.collapsed .sec-chevron{transform:rotate(-90deg);}
        .sec-group-body{overflow:hidden;transition:max-height .3s ease;}
        .sec-group-body.sg-hidden{display:none;}

        /* ── MAIN ── */
        .main-content{margin-left:var(--sw);min-height:100vh;transition:margin-left .28s ease;}
        .topbar{background:#fff;padding:.6rem 1.2rem;box-shadow:0 2px 10px rgba(0,0,0,.06);position:sticky;top:0;z-index:99;display:flex;align-items:center;justify-content:space-between;}
        .sb-toggle{background:none;border:none;padding:.3rem .45rem;border-radius:8px;color:#555;cursor:pointer;transition:.2s;font-size:1.2rem;line-height:1;}
        .sb-toggle:hover{background:#f1f5f9;}
        .page-content{padding:1.2rem;}

        /* ── CARDS ── */
        .card{border:none;border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.07);}
        .card-header{background:#fff;border-bottom:1px solid #f1f1f1;border-radius:14px 14px 0 0!important;padding:.85rem 1.2rem;font-weight:600;font-size:.875rem;}
        .stat-card{border-radius:14px;padding:1.2rem;color:#fff;}
        .stat-icon{width:44px;height:44px;border-radius:10px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.25rem;}
        .btn-primary{background:var(--pri);border-color:var(--pri);}
        .btn-primary:hover{background:var(--pri-d);border-color:var(--pri-d);}
        .table th{font-weight:600;font-size:.74rem;text-transform:uppercase;letter-spacing:.5px;color:#888;}
        .table td{vertical-align:middle;font-size:.85rem;}
        .badge{font-size:.7rem;}
        .form-control,.form-select{border-radius:9px;border:1.5px solid #e2e8f0;font-size:.875rem;}
        .form-control:focus,.form-select:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(21,101,192,.1);}

        /* ── MOBILE RESPONSIVE ── */
        @media(max-width:575px){
            .page-content{padding:.75rem;}
            .stat-card{padding:.9rem;}
            .stat-icon{width:36px;height:36px;font-size:1rem;}
            .topbar{padding:.5rem .75rem;}
            .card-header{padding:.7rem 1rem;}
            h5.fw-bold{font-size:1rem!important;}
            .table th,.table td{font-size:.75rem;}
            .btn-sm{font-size:.75rem;padding:.3rem .6rem;}
            .d-flex.justify-content-between.align-items-center.mb-3{flex-wrap:wrap;gap:.5rem;}
        }
        @media(max-width:767px){
            .page-content{padding:.9rem;}
            .user-name-text{display:none;}
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Mobile Overlay -->
<div class="sb-overlay" id="sbOverlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sb-brand">
        <div class="sb-icon"><i class="bi bi-bank2"></i></div>
        <div class="sb-brand-text"><h6>CoreAxis</h6><small>Financial Management</small></div>
    </div>
    <nav class="py-2 pb-3">

        <a href="{{ route('dashboard') }}" class="direct-lnk {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span class="sb-label">Dashboard</span>
        </a>

        {{-- CRM GROUP --}}
        @if(auth()->user()?->hasFeature('customers'))
        @php $crmActive = request()->routeIs('customers.*'); @endphp
        <button class="sec-group-btn {{ $crmActive ? '' : '' }}" onclick="toggleGroup('grpCRM',this)" id="btnGrpCRM">
            <span class="sec-group-label"><i class="bi bi-people-fill me-1"></i><span class="sb-label">CRM</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpCRM">
            @php $custOpen = request()->routeIs('customers.*'); @endphp
            <button class="sb-acc-btn {{ $custOpen ? 'open' : '' }}" onclick="toggleAcc('accCust',this)">
                <i class="bi bi-people"></i><span class="sb-label">Customers</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $custOpen ? '' : 'd-none' }}" id="accCust">
                <a href="{{ route('customers.index') }}" class="child-lnk {{ request()->routeIs('customers.index') ? 'active' : '' }}"><i class="bi bi-list-ul"></i><span class="sb-label">All Customers</span></a>
                <a href="{{ route('customers.create') }}" class="child-lnk {{ request()->routeIs('customers.create') ? 'active' : '' }}"><i class="bi bi-person-plus"></i><span class="sb-label">Add Customer</span></a>
            </div>
        </div>
        @endif

        {{-- BANKING GROUP --}}
        @if(auth()->user()?->hasFeature('accounts') || auth()->user()?->hasFeature('transactions') || auth()->user()?->hasFeature('loans'))
        @php $bankingActive = request()->routeIs('accounts.*') || request()->routeIs('transactions.*') || request()->routeIs('loans.*'); @endphp
        <button class="sec-group-btn" onclick="toggleGroup('grpBanking',this)" id="btnGrpBanking">
            <span class="sec-group-label"><i class="bi bi-bank me-1"></i><span class="sb-label">Banking</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpBanking">
            @if(auth()->user()?->hasFeature('accounts'))
            @php $accOpen = request()->routeIs('accounts.*'); @endphp
            <button class="sb-acc-btn {{ $accOpen ? 'open' : '' }}" onclick="toggleAcc('accAcc',this)">
                <i class="bi bi-wallet2"></i><span class="sb-label">Accounts</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $accOpen ? '' : 'd-none' }}" id="accAcc">
                <a href="{{ route('accounts.index') }}" class="child-lnk {{ request()->routeIs('accounts.index') ? 'active' : '' }}"><i class="bi bi-list-ul"></i><span class="sb-label">All Accounts</span></a>
                <a href="{{ route('accounts.create') }}" class="child-lnk {{ request()->routeIs('accounts.create') ? 'active' : '' }}"><i class="bi bi-plus-circle"></i><span class="sb-label">Open Account</span></a>
            </div>
            @endif
            @if(auth()->user()?->hasFeature('transactions'))
            @php $txnOpen = request()->routeIs('transactions.*'); @endphp
            <button class="sb-acc-btn {{ $txnOpen ? 'open' : '' }}" onclick="toggleAcc('accTxn',this)">
                <i class="bi bi-arrow-left-right"></i><span class="sb-label">Transactions</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $txnOpen ? '' : 'd-none' }}" id="accTxn">
                <a href="{{ route('transactions.index') }}" class="child-lnk {{ request()->routeIs('transactions.index') ? 'active' : '' }}"><i class="bi bi-list-ul"></i><span class="sb-label">All Transactions</span></a>
                <a href="{{ route('transactions.deposit') }}" class="child-lnk {{ request()->routeIs('transactions.deposit') ? 'active' : '' }}"><i class="bi bi-plus-circle-fill"></i><span class="sb-label">Deposit</span></a>
                <a href="{{ route('transactions.withdraw') }}" class="child-lnk {{ request()->routeIs('transactions.withdraw') ? 'active' : '' }}"><i class="bi bi-dash-circle-fill"></i><span class="sb-label">Withdraw</span></a>
                <a href="{{ route('transactions.transfer') }}" class="child-lnk {{ request()->routeIs('transactions.transfer') ? 'active' : '' }}"><i class="bi bi-send-fill"></i><span class="sb-label">Transfer</span></a>
            </div>
            @endif
            @if(auth()->user()?->hasFeature('loans'))
            @php $loanOpen = request()->routeIs('loans.*'); @endphp
            <button class="sb-acc-btn {{ $loanOpen ? 'open' : '' }}" onclick="toggleAcc('accLoan',this)">
                <i class="bi bi-credit-card"></i><span class="sb-label">Loans & EMI</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $loanOpen ? '' : 'd-none' }}" id="accLoan">
                <a href="{{ route('loans.index') }}" class="child-lnk {{ request()->routeIs('loans.index') ? 'active' : '' }}"><i class="bi bi-list-ul"></i><span class="sb-label">All Loans</span></a>
                <a href="{{ route('loans.create') }}" class="child-lnk {{ request()->routeIs('loans.create') ? 'active' : '' }}"><i class="bi bi-file-earmark-plus"></i><span class="sb-label">Apply Loan</span></a>
            </div>
            @endif
        </div>
        @endif

        {{-- COLLECTIONS GROUP --}}
        @if(auth()->user()?->hasFeature('collections') || auth()->user()?->hasFeature('saving_plans'))
        @php $colActive = request()->routeIs('collection-plans.*') || request()->routeIs('saving-plans.*'); @endphp
        <button class="sec-group-btn" onclick="toggleGroup('grpCollections',this)" id="btnGrpCollections">
            <span class="sec-group-label"><i class="bi bi-collection-fill me-1"></i><span class="sb-label">Collections</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpCollections">
            @if(auth()->user()?->hasFeature('collections'))
            @php $colOpen = request()->routeIs('collection-plans.*'); @endphp
            <button class="sb-acc-btn {{ $colOpen ? 'open' : '' }}" onclick="toggleAcc('accCol',this)">
                <i class="bi bi-collection"></i><span class="sb-label">Collections</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $colOpen ? '' : 'd-none' }}" id="accCol">
                <a href="{{ route('collection-plans.index') }}" class="child-lnk"><i class="bi bi-list-ul"></i><span class="sb-label">All Plans</span></a>
                <a href="{{ route('collection-plans.create') }}" class="child-lnk"><i class="bi bi-plus-circle"></i><span class="sb-label">New Plan</span></a>
            </div>
            @endif
            @if(auth()->user()?->hasFeature('saving_plans'))
            @php $spOpen = request()->routeIs('saving-plans.*'); @endphp
            <button class="sb-acc-btn {{ $spOpen ? 'open' : '' }}" onclick="toggleAcc('accSP',this)">
                <i class="bi bi-piggy-bank"></i><span class="sb-label">Saving Plans</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $spOpen ? '' : 'd-none' }}" id="accSP">
                <a href="{{ route('saving-plans.index') }}" class="child-lnk"><i class="bi bi-list-ul"></i><span class="sb-label">All Plans</span></a>
                <a href="{{ route('saving-plans.create') }}" class="child-lnk"><i class="bi bi-plus-circle"></i><span class="sb-label">New Plan</span></a>
            </div>
            @endif
        </div>
        @endif

        {{-- BANKING EXTRAS GROUP --}}
        @php $extActive = request()->routeIs('fixed-deposits.*')||request()->routeIs('recurring-deposits.*')||request()->routeIs('cheques.*')||request()->routeIs('fund-transfers.*')||request()->routeIs('standing-instructions.*')||request()->routeIs('demand-drafts.*'); @endphp
        <button class="sec-group-btn" onclick="toggleGroup('grpBankExt',this)" id="btnGrpBankExt">
            <span class="sec-group-label"><i class="bi bi-safe2 me-1"></i><span class="sb-label">Deposits & Payments</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpBankExt">
            <a href="{{ route('fixed-deposits.index') }}" class="direct-lnk {{ request()->routeIs('fixed-deposits.*') ? 'active' : '' }}"><i class="bi bi-safe2"></i><span class="sb-label">Fixed Deposits</span></a>
            <a href="{{ route('recurring-deposits.index') }}" class="direct-lnk {{ request()->routeIs('recurring-deposits.*') ? 'active' : '' }}"><i class="bi bi-calendar-week"></i><span class="sb-label">Recurring Deposits</span></a>
            <a href="{{ route('cheques.index') }}" class="direct-lnk {{ request()->routeIs('cheques.*') ? 'active' : '' }}"><i class="bi bi-check2-square"></i><span class="sb-label">Cheque Management</span></a>
            <a href="{{ route('fund-transfers.index') }}" class="direct-lnk {{ request()->routeIs('fund-transfers.*') ? 'active' : '' }}"><i class="bi bi-send"></i><span class="sb-label">NEFT / RTGS / IMPS</span></a>
            <a href="{{ route('standing-instructions.index') }}" class="direct-lnk {{ request()->routeIs('standing-instructions.*') ? 'active' : '' }}"><i class="bi bi-repeat"></i><span class="sb-label">Standing Instructions</span></a>
            <a href="{{ route('demand-drafts.index') }}" class="direct-lnk {{ request()->routeIs('demand-drafts.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-text"></i><span class="sb-label">Demand Drafts / PO</span></a>
        </div>

        {{-- KYC & COMPLIANCE --}}
        @php $kycActive = request()->routeIs('kyc.*')||request()->routeIs('grievances.*')||request()->routeIs('service-requests.*'); @endphp
        <button class="sec-group-btn" onclick="toggleGroup('grpKYC',this)" id="btnGrpKYC">
            <span class="sec-group-label"><i class="bi bi-shield-check me-1"></i><span class="sb-label">KYC & Compliance</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpKYC">
            <a href="{{ route('kyc.index') }}" class="direct-lnk {{ request()->routeIs('kyc.*') ? 'active' : '' }}"><i class="bi bi-person-check"></i><span class="sb-label">KYC Verification</span></a>
            <a href="{{ route('grievances.index') }}" class="direct-lnk {{ request()->routeIs('grievances.*') ? 'active' : '' }}"><i class="bi bi-chat-square-text"></i><span class="sb-label">Grievances</span></a>
            <a href="{{ route('service-requests.index') }}" class="direct-lnk {{ request()->routeIs('service-requests.*') ? 'active' : '' }}"><i class="bi bi-clipboard-check"></i><span class="sb-label">Service Requests</span></a>
        </div>

        {{-- ANALYTICS GROUP --}}
        @if(auth()->user()?->hasFeature('reports'))
        @php $repActive = request()->routeIs('reports.*'); @endphp
        <button class="sec-group-btn" onclick="toggleGroup('grpAnalytics',this)" id="btnGrpAnalytics">
            <span class="sec-group-label"><i class="bi bi-bar-chart-fill me-1"></i><span class="sb-label">Analytics</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpAnalytics">
            @php $repOpen = request()->routeIs('reports.*'); @endphp
            <button class="sb-acc-btn {{ $repOpen ? 'open' : '' }}" onclick="toggleAcc('accRep',this)">
                <i class="bi bi-bar-chart-line"></i><span class="sb-label">Reports</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $repOpen ? '' : 'd-none' }}" id="accRep">
                <a href="{{ route('reports.transactions') }}" class="child-lnk"><i class="bi bi-receipt"></i><span class="sb-label">Transactions</span></a>
                <a href="{{ route('reports.statement') }}" class="child-lnk"><i class="bi bi-file-text"></i><span class="sb-label">Account Statement</span></a>
                <a href="{{ route('reports.loans') }}" class="child-lnk"><i class="bi bi-graph-up"></i><span class="sb-label">Loan Report</span></a>
                <a href="{{ route('reports.npa') }}" class="child-lnk {{ request()->routeIs('reports.npa') ? 'active' : '' }}"><i class="bi bi-exclamation-triangle"></i><span class="sb-label">NPA Report</span></a>
                <a href="{{ route('reports.cashflow') }}" class="child-lnk {{ request()->routeIs('reports.cashflow') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i><span class="sb-label">Cash Flow</span></a>
                <a href="{{ route('reports.regulatory') }}" class="child-lnk {{ request()->routeIs('reports.regulatory') ? 'active' : '' }}"><i class="bi bi-file-earmark-ruled"></i><span class="sb-label">Regulatory Returns</span></a>
            </div>
        </div>
        @endif

        {{-- GENERAL LEDGER --}}
        @php $glActive = request()->routeIs('gl.*'); @endphp
        <button class="sec-group-btn" onclick="toggleGroup('grpGL',this)" id="btnGrpGL">
            <span class="sec-group-label"><i class="bi bi-journal-text me-1"></i><span class="sb-label">General Ledger</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body {{ $glActive ? 'show' : '' }}" id="grpGL">
            <a href="{{ route('gl.chart-of-accounts') }}" class="direct-lnk {{ request()->routeIs('gl.chart-of-accounts') ? 'active' : '' }}"><i class="bi bi-list-columns"></i><span class="sb-label">Chart of Accounts</span></a>
            <a href="{{ route('gl.entries') }}" class="direct-lnk {{ request()->routeIs('gl.entries*') ? 'active' : '' }}"><i class="bi bi-receipt"></i><span class="sb-label">Journal Entries</span></a>
            <a href="{{ route('gl.trial-balance') }}" class="direct-lnk {{ request()->routeIs('gl.trial-balance') ? 'active' : '' }}"><i class="bi bi-bar-chart-steps"></i><span class="sb-label">Trial Balance</span></a>
            <a href="{{ route('gl.ledger') }}" class="direct-lnk {{ request()->routeIs('gl.ledger') ? 'active' : '' }}"><i class="bi bi-book"></i><span class="sb-label">General Ledger</span></a>
        </div>

        @php $opsActive = request()->routeIs('eod.*')||request()->routeIs('branches.*')||request()->routeIs('audit-log.*'); @endphp
        <button class="sec-group-btn" onclick="toggleGroup('grpOps',this)" id="btnGrpOps">
            <span class="sec-group-label"><i class="bi bi-gear-wide-connected me-1"></i><span class="sb-label">Operations</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpOps">
            <a href="{{ route('eod.index') }}" class="direct-lnk {{ request()->routeIs('eod.*') ? 'active' : '' }}"><i class="bi bi-calendar2-check"></i><span class="sb-label">EOD Processing</span></a>
            <a href="{{ route('branches.index') }}" class="direct-lnk {{ request()->routeIs('branches.*') ? 'active' : '' }}"><i class="bi bi-building"></i><span class="sb-label">Branches</span></a>
            <a href="{{ route('audit-log.index') }}" class="direct-lnk {{ request()->routeIs('audit-log.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="sb-label">Audit Trail</span></a>
        </div>

        {{-- HR & ADMIN GROUP --}}
        @if(auth()->user()?->hasFeature('employees') || auth()->user()?->hasFeature('expenses') || auth()->user()?->hasFeature('users'))
        @php $hrActive = request()->routeIs('employees.*') || request()->routeIs('expenses.*') || request()->routeIs('users.*'); @endphp
        <button class="sec-group-btn" onclick="toggleGroup('grpHR',this)" id="btnGrpHR">
            <span class="sec-group-label"><i class="bi bi-person-workspace me-1"></i><span class="sb-label">HR & Admin</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpHR">
            @if(auth()->user()?->hasFeature('employees'))
            @php $empOpen = request()->routeIs('employees.*'); @endphp
            <button class="sb-acc-btn {{ $empOpen ? 'open' : '' }}" onclick="toggleAcc('accHR',this)">
                <i class="bi bi-person-badge"></i><span class="sb-label">HR & Payroll</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $empOpen ? '' : 'd-none' }}" id="accHR">
                <a href="{{ route('employees.index') }}" class="child-lnk"><i class="bi bi-people"></i><span class="sb-label">Employees</span></a>
                <a href="{{ route('employees.create') }}" class="child-lnk"><i class="bi bi-person-plus"></i><span class="sb-label">Add Employee</span></a>
            </div>
            @endif
            @if(auth()->user()?->hasFeature('expenses'))
            @php $expOpen = request()->routeIs('expenses.*'); @endphp
            <button class="sb-acc-btn {{ $expOpen ? 'open' : '' }}" onclick="toggleAcc('accExp',this)">
                <i class="bi bi-receipt-cutoff"></i><span class="sb-label">Expenses</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $expOpen ? '' : 'd-none' }}" id="accExp">
                <a href="{{ route('expenses.index') }}" class="child-lnk"><i class="bi bi-list-ul"></i><span class="sb-label">All Expenses</span></a>
                <a href="{{ route('expenses.create') }}" class="child-lnk"><i class="bi bi-plus-circle"></i><span class="sb-label">Add Expense</span></a>
            </div>
            @endif
            @if(auth()->user()?->hasFeature('users'))
            @php $usrOpen = request()->routeIs('users.*'); @endphp
            <button class="sb-acc-btn {{ $usrOpen ? 'open' : '' }}" onclick="toggleAcc('accUsr',this)">
                <i class="bi bi-gear"></i><span class="sb-label">Settings</span><i class="bi bi-chevron-down"></i>
            </button>
            <div class="acc-children {{ $usrOpen ? '' : 'd-none' }}" id="accUsr">
                <a href="{{ route('users.index') }}" class="child-lnk"><i class="bi bi-shield-person"></i><span class="sb-label">User Management</span></a>
            </div>
            @endif
        </div>
        @endif

        {{-- SUPER ADMIN GROUP --}}
        @if(auth()->user()?->isSuperAdmin())
        <button class="sec-group-btn" onclick="toggleGroup('grpSA',this)" id="btnGrpSA" style="color:#FFD700;border-color:rgba(255,215,0,.2)">
            <span class="sec-group-label"><i class="bi bi-shield-fill-check me-1"></i><span class="sb-label">Super Admin</span></span>
            <i class="bi bi-chevron-down sec-chevron sb-label"></i>
        </button>
        <div class="sec-group-body" id="grpSA">
            <a href="{{ route('super-admin.dashboard') }}" class="direct-lnk {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-shield-lock-fill" style="color:#FFD700"></i><span class="sb-label">SA Dashboard</span>
            </a>
            <a href="{{ route('super-admin.permissions') }}" class="direct-lnk {{ request()->routeIs('super-admin.permissions') ? 'active' : '' }}">
                <i class="bi bi-toggles" style="color:#FFD700"></i><span class="sb-label">Permissions</span>
            </a>
        </div>
        @endif
    </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content" id="mainContent">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="sb-toggle" id="sbToggle"><i class="bi bi-list"></i></button>
            <span class="fw-600" style="font-size:.875rem;font-weight:600;color:#374151">@yield('title','Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" style="font-size:.8rem;border-radius:8px">
                    <i class="bi bi-person-circle"></i>
                    <span class="user-name-text">{{ auth()->user()->name }}</span>
                    <span class="badge bg-{{ auth()->user()->isSuperAdmin() ? 'warning text-dark' : (auth()->user()->isAdmin() ? 'primary' : 'secondary') }} ms-1" style="font-size:.6rem">{{ ucfirst(str_replace('_',' ', auth()->user()->role ?? 'user')) }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width:180px;border-radius:12px;border:1px solid #e5e7eb">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2 text-primary"></i>My Profile</a></li>
                    @if(auth()->user()->isSuperAdmin())
                    <li><a class="dropdown-item" href="{{ route('super-admin.dashboard') }}"><i class="bi bi-shield-lock-fill me-2" style="color:#FFD700"></i>Super Admin</a></li>
                    @endif
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2 mb-3" style="border-radius:10px;font-size:.85rem">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" style="border-radius:10px;font-size:.85rem">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" style="border-radius:10px;font-size:.85rem">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleAcc(id, btn) {
    const el = document.getElementById(id);
    const hidden = el.classList.contains('d-none');
    el.classList.toggle('d-none', !hidden);
    btn.classList.toggle('open', hidden);
}

// Section group collapse/expand
function toggleGroup(id, btn) {
    const body = document.getElementById(id);
    const collapsed = btn.classList.contains('collapsed');
    body.classList.toggle('sg-hidden', !collapsed);
    btn.classList.toggle('collapsed', !collapsed);
    // save state
    const states = JSON.parse(localStorage.getItem('sgStates') || '{}');
    states[id] = !collapsed ? 'closed' : 'open';
    localStorage.setItem('sgStates', JSON.stringify(states));
}

// Init section groups on load
document.addEventListener('DOMContentLoaded', () => {
    const states = JSON.parse(localStorage.getItem('sgStates') || '{}');
    document.querySelectorAll('.sec-group-btn').forEach(btn => {
        const bodyId = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
        const body = document.getElementById(bodyId);
        if (!body) return;
        const hasActive = body.querySelector('.active, .open') !== null;
        const savedState = states[bodyId];
        // Keep open if: has active item, or saved as open, or no saved state
        if (!hasActive && savedState === 'closed') {
            body.classList.add('sg-hidden');
            btn.classList.add('collapsed');
        }
    });
});

const sbToggle = document.getElementById('sbToggle');
const overlay  = document.getElementById('sbOverlay');
const isMobile = () => window.innerWidth < 768;

// Desktop: restore collapse state
if (!isMobile() && localStorage.getItem('sbCol') === '1') {
    document.body.classList.add('sb-col');
}

sbToggle.addEventListener('click', () => {
    if (isMobile()) {
        document.body.classList.toggle('sb-open');
    } else {
        document.body.classList.toggle('sb-col');
        localStorage.setItem('sbCol', document.body.classList.contains('sb-col') ? '1' : '0');
    }
});

// Close sidebar on overlay click (mobile)
overlay.addEventListener('click', () => {
    document.body.classList.remove('sb-open');
});

// Close sidebar when a nav link is clicked on mobile
document.querySelectorAll('.sidebar .child-lnk, .sidebar .direct-lnk').forEach(link => {
    link.addEventListener('click', () => {
        if (isMobile()) document.body.classList.remove('sb-open');
    });
});

// On resize: clean up mobile state
window.addEventListener('resize', () => {
    if (!isMobile()) document.body.classList.remove('sb-open');
});
</script>
@stack('scripts')
</body>
</html>
