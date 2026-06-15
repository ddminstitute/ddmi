@extends('layouts.banking')
@section('title','Employee Profile')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2 text-primary"></i>Employee Profile</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('employees.attendance',$employee) }}" class="btn btn-info btn-sm text-white"><i class="bi bi-calendar3 me-1"></i>Attendance</a>
        <a href="{{ route('employees.edit',$employee) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center p-3">
            @if($employee->photo)
                <img src="{{ Storage::url($employee->photo) }}" class="rounded-circle mx-auto mb-3" width="100" height="100" style="object-fit:cover;border:4px solid #e2e8f0">
            @else
                <div class="rounded-circle bg-info mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:100px;height:100px">
                    <span class="text-white fw-bold" style="font-size:2rem">{{ strtoupper(substr($employee->name,0,1)) }}</span>
                </div>
            @endif
            <h6 class="fw-bold mb-1">{{ $employee->name }}</h6>
            <div class="text-muted small mb-2">{{ $employee->designation }}</div>
            <span class="badge bg-secondary">{{ $employee->employee_id }}</span>
            <div class="mt-2"><span class="badge bg-{{ $employee->status==='active'?'success':'secondary' }}">{{ ucfirst($employee->status) }}</span></div>
        </div>
        <!-- Generate Payslip -->
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-file-earmark-text me-2"></i>Generate Payslip</div>
            <div class="card-body">
                <form method="POST" action="{{ route('employees.payslip.generate',$employee) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="small">Month</label>
                        <select name="month" class="form-select form-select-sm">
                            @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}" {{ now()->month==$m?'selected':'' }}>{{ date('F',mktime(0,0,0,$m,1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="small">Year</label>
                        <select name="year" class="form-select form-select-sm">
                            @for($y=now()->year;$y>=2020;$y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="small">Deductions (₹)</label>
                        <input type="number" name="deductions" value="0" class="form-control form-control-sm" step="0.01" min="0">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="bi bi-file-earmark-check me-1"></i>Generate & Print</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Employment Details</div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach([['Department',$employee->department],['Joining Date',$employee->joining_date?->format('d M Y')],['Phone',$employee->phone],['Email',$employee->email??'—'],['PAN',$employee->pan_number??'—'],['Aadhaar',$employee->aadhaar_number??'—'],['Bank Account',$employee->bank_account??'—'],['Bank Name',$employee->bank_name??'—'],['IFSC',$employee->ifsc_code??'—']] as [$l,$v])
                    <div class="col-md-6"><div class="d-flex gap-2"><span class="text-muted small" style="min-width:110px">{{ $l }}:</span><span class="small fw-semibold">{{ $v }}</span></div></div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-currency-rupee me-2"></i>Salary Breakdown</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-4 text-center"><div class="text-muted small">Basic Salary</div><div class="fw-bold">₹{{ number_format($employee->basic_salary,2) }}</div></div>
                    <div class="col-4 text-center"><div class="text-muted small">HRA</div><div class="fw-bold">₹{{ number_format($employee->hra,2) }}</div></div>
                    <div class="col-4 text-center"><div class="text-muted small">Other Allow.</div><div class="fw-bold">₹{{ number_format($employee->other_allowance,2) }}</div></div>
                    <div class="col-12 text-center border-top pt-2"><div class="text-muted small">Gross Salary</div><div class="fw-bold text-primary fs-5">₹{{ number_format($employee->grossSalary(),2) }}</div></div>
                </div>
            </div>
        </div>
        <!-- This month attendance -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-check me-2"></i>This Month Attendance</span>
                <a href="{{ route('employees.attendance',$employee) }}" class="btn btn-sm btn-outline-primary">Full View</a>
            </div>
            <div class="card-body">
                <div class="row g-2 text-center">
                    @php
                        $p = $thisMonthAttendance->where('status','present')->count();
                        $a = $thisMonthAttendance->where('status','absent')->count();
                        $h = $thisMonthAttendance->where('status','half_day')->count();
                        $l = $thisMonthAttendance->where('status','leave')->count();
                    @endphp
                    <div class="col-3"><div class="p-2 bg-success bg-opacity-10 rounded"><div class="fw-bold text-success fs-5">{{ $p }}</div><div class="text-muted" style="font-size:.72rem">Present</div></div></div>
                    <div class="col-3"><div class="p-2 bg-danger bg-opacity-10 rounded"><div class="fw-bold text-danger fs-5">{{ $a }}</div><div class="text-muted" style="font-size:.72rem">Absent</div></div></div>
                    <div class="col-3"><div class="p-2 bg-warning bg-opacity-10 rounded"><div class="fw-bold text-warning fs-5">{{ $h }}</div><div class="text-muted" style="font-size:.72rem">Half Day</div></div></div>
                    <div class="col-3"><div class="p-2 bg-info bg-opacity-10 rounded"><div class="fw-bold text-info fs-5">{{ $l }}</div><div class="text-muted" style="font-size:.72rem">Leave</div></div></div>
                </div>
            </div>
        </div>
        <!-- Payslips -->
        <div class="card">
            <div class="card-header"><i class="bi bi-file-text me-2"></i>Recent Payslips</div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light"><tr><th>Month</th><th>Year</th><th>Net Salary</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($employee->payslips->sortByDesc('id')->take(6) as $ps)
                        <tr>
                            <td class="small">{{ date('F',mktime(0,0,0,$ps->month,1)) }}</td>
                            <td class="small">{{ $ps->year }}</td>
                            <td class="fw-semibold text-success small">₹{{ number_format($ps->net_salary,2) }}</td>
                            <td><span class="badge bg-{{ $ps->status==='paid'?'success':($ps->status==='generated'?'primary':'secondary') }}">{{ ucfirst($ps->status) }}</span></td>
                            <td><a href="{{ route('print.payslip',$ps) }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="font-size:.72rem;padding:.2rem .5rem"><i class="bi bi-printer"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3 small">No payslips generated</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
