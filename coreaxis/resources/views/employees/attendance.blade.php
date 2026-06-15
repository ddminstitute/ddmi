@extends('layouts.banking')
@section('title','Attendance — {{ $employee->name }}')
@section('content')
@php
    $monthName = date('F', mktime(0,0,0,$month,1,$year));
    $prevMonth = $month==1?12:$month-1;
    $prevYear = $month==1?$year-1:$year;
    $nextMonth = $month==12?1:$month+1;
    $nextYear = $month==12?$year+1:$year;
    $firstDayOfWeek = date('N', mktime(0,0,0,$month,1,$year)); // 1=Mon 7=Sun
    $statusColors = ['present'=>'success','absent'=>'danger','half_day'=>'warning','holiday'=>'info','leave'=>'secondary'];
@endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar3 me-2 text-primary"></i>{{ $employee->name }} — Attendance</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('employees.attendance',$employee,false).('/'.$prevYear.'/'.$prevMonth) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-left"></i></a>
        <span class="btn btn-light btn-sm disabled fw-semibold">{{ $monthName }} {{ $year }}</span>
        <a href="{{ route('employees.attendance',$employee,false).('/'.$nextYear.'/'.$nextMonth) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('employees.show',$employee) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-9">
        <!-- Calendar -->
        <div class="card">
            <div class="card-header"><i class="bi bi-grid me-2"></i>{{ $monthName }} {{ $year }} Calendar</div>
            <div class="card-body p-2">
                <div class="row g-0 mb-1">
                    @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
                    <div class="col text-center py-1 fw-semibold text-muted" style="font-size:.75rem">{{ $d }}</div>
                    @endforeach
                </div>
                @php $day = 1; $col = 0; @endphp
                <div class="row g-1">
                    @for($i=1; $i<$firstDayOfWeek; $i++)
                    <div class="col p-1"></div>
                    @php $col++; @endphp
                    @endfor
                    @while($day <= $daysInMonth)
                    @php
                        $dateStr = sprintf('%d-%02d-%02d', $year, $month, $day);
                        $att = $attendances[$dateStr] ?? null;
                        $isToday = $dateStr == date('Y-m-d');
                    @endphp
                    <div class="col p-1">
                        <div class="text-center p-1 rounded {{ $att ? 'bg-'.$statusColors[$att->status].($att->status=='half_day'?' bg-opacity-25':' bg-opacity-15') : '' }} {{ $isToday ? 'border border-primary' : '' }}" style="min-height:48px;font-size:.78rem">
                            <div class="fw-semibold {{ $isToday?'text-primary':'' }}">{{ $day }}</div>
                            @if($att)
                            <span class="badge bg-{{ $statusColors[$att->status] }}" style="font-size:.6rem">{{ strtoupper(substr($att->status,0,1)) }}</span>
                            @endif
                        </div>
                    </div>
                    @php $col++; if($col%7==0 && $day<$daysInMonth) echo '</div><div class="row g-1">'; $day++; @endphp
                    @endwhile
                </div>
                <!-- Legend -->
                <div class="d-flex flex-wrap gap-2 mt-3 pt-2 border-top">
                    @foreach(['present'=>['success','P','Present'],'absent'=>['danger','A','Absent'],'half_day'=>['warning','H','Half Day'],'holiday'=>['info','Ho','Holiday'],'leave'=>['secondary','L','Leave']] as $s=>[$c,$short,$label])
                    <div class="d-flex align-items-center gap-1"><span class="badge bg-{{ $c }}" style="font-size:.65rem">{{ $short }}</span><span style="font-size:.75rem;color:#666">{{ $label }}</span></div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Summary -->
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-bar-chart me-2"></i>Monthly Summary</div>
            <div class="card-body">
                @php
                    $counts = $attendances->groupBy('status')->map->count();
                    $total = $daysInMonth;
                    $present = ($counts['present']??0) + ($counts['half_day']??0)*0.5;
                @endphp
                <div class="row g-3 text-center">
                    <div class="col"><div class="p-2 bg-success bg-opacity-10 rounded"><div class="fw-bold text-success fs-4">{{ $counts['present']??0 }}</div><div class="small text-muted">Present</div></div></div>
                    <div class="col"><div class="p-2 bg-danger bg-opacity-10 rounded"><div class="fw-bold text-danger fs-4">{{ $counts['absent']??0 }}</div><div class="small text-muted">Absent</div></div></div>
                    <div class="col"><div class="p-2 bg-warning bg-opacity-10 rounded"><div class="fw-bold text-warning fs-4">{{ $counts['half_day']??0 }}</div><div class="small text-muted">Half Day</div></div></div>
                    <div class="col"><div class="p-2 bg-info bg-opacity-10 rounded"><div class="fw-bold text-info fs-4">{{ $counts['holiday']??0 }}</div><div class="small text-muted">Holiday</div></div></div>
                    <div class="col"><div class="p-2 bg-secondary bg-opacity-10 rounded"><div class="fw-bold text-secondary fs-4">{{ $counts['leave']??0 }}</div><div class="small text-muted">Leave</div></div></div>
                    <div class="col"><div class="p-2 bg-primary bg-opacity-10 rounded"><div class="fw-bold text-primary fs-4">{{ $present }}</div><div class="small text-muted">Effective Days</div></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Mark Attendance</div>
            <div class="card-body">
                <form method="POST" action="{{ route('employees.attendance.store',$employee) }}">
                    @csrf
                    <div class="mb-2"><label class="small">Date</label><input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required></div>
                    <div class="mb-2"><label class="small">Status</label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="half_day">Half Day</option>
                            <option value="holiday">Holiday</option>
                            <option value="leave">Leave</option>
                        </select>
                    </div>
                    <div class="mb-2"><label class="small">Check In</label><input type="time" name="check_in" value="09:00" class="form-control form-control-sm"></div>
                    <div class="mb-2"><label class="small">Check Out</label><input type="time" name="check_out" value="18:00" class="form-control form-control-sm"></div>
                    <div class="mb-3"><label class="small">Notes</label><input type="text" name="notes" class="form-control form-control-sm" placeholder="Optional"></div>
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-check2 me-1"></i>Mark</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
