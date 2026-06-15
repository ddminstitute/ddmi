<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\Payslip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller {
    public function index(Request $request) {
        $query = Employee::query();
        if ($request->search) $query->where('name','like',"%{$request->search}%")->orWhere('employee_id','like',"%{$request->search}%");
        if ($request->department) $query->where('department', $request->department);
        $employees = $query->latest()->paginate(15)->withQueryString();
        $departments = Employee::distinct()->pluck('department');
        return view('employees.index', compact('employees','departments'));
    }
    public function create() { return view('employees.create'); }
    public function store(Request $request) {
        $data = $request->validate([
            'name'=>'required|string|max:100',
            'phone'=>'required|string|max:15',
            'email'=>'nullable|email',
            'designation'=>'required|string|max:100',
            'department'=>'required|string|max:100',
            'joining_date'=>'required|date',
            'basic_salary'=>'required|numeric|min:0',
            'hra'=>'nullable|numeric|min:0',
            'other_allowance'=>'nullable|numeric|min:0',
            'pan_number'=>'nullable|string|max:20',
            'aadhaar_number'=>'nullable|string|max:20',
            'bank_account'=>'nullable|string|max:30',
            'bank_name'=>'nullable|string|max:100',
            'ifsc_code'=>'nullable|string|max:20',
            'photo'=>'nullable|image|max:2048',
        ]);
        $data['employee_id'] = Employee::generateEmployeeId();
        $data['hra'] = $data['hra'] ?? 0;
        $data['other_allowance'] = $data['other_allowance'] ?? 0;
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees','public');
        }
        $employee = Employee::create($data);
        return redirect()->route('employees.show', $employee)->with('success', "Employee {$employee->employee_id} created.");
    }
    public function show(Employee $employee) {
        $employee->load('payslips');
        $thisMonthAttendance = $employee->monthAttendance(now()->month, now()->year);
        return view('employees.show', compact('employee','thisMonthAttendance'));
    }
    public function edit(Employee $employee) { return view('employees.edit', compact('employee')); }
    public function update(Request $request, Employee $employee) {
        $data = $request->validate([
            'name'=>'required|string|max:100',
            'phone'=>'required|string|max:15',
            'email'=>'nullable|email',
            'designation'=>'required|string|max:100',
            'department'=>'required|string|max:100',
            'joining_date'=>'required|date',
            'basic_salary'=>'required|numeric|min:0',
            'hra'=>'nullable|numeric|min:0',
            'other_allowance'=>'nullable|numeric|min:0',
            'pan_number'=>'nullable|string|max:20',
            'aadhaar_number'=>'nullable|string|max:20',
            'bank_account'=>'nullable|string|max:30',
            'bank_name'=>'nullable|string|max:100',
            'ifsc_code'=>'nullable|string|max:20',
            'status'=>'required|in:active,inactive',
            'photo'=>'nullable|image|max:2048',
        ]);
        $data['hra'] = $data['hra'] ?? 0;
        $data['other_allowance'] = $data['other_allowance'] ?? 0;
        if ($request->hasFile('photo')) {
            if ($employee->photo) Storage::disk('public')->delete($employee->photo);
            $data['photo'] = $request->file('photo')->store('employees','public');
        }
        $employee->update($data);
        return redirect()->route('employees.show', $employee)->with('success', 'Employee updated.');
    }
    public function attendance(Employee $employee, $year = null, $month = null) {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        $attendances = $employee->attendances()->whereYear('date',$year)->whereMonth('date',$month)->get()->keyBy(fn($a) => $a->date->format('Y-m-d'));
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        return view('employees.attendance', compact('employee','attendances','year','month','daysInMonth'));
    }
    public function markAttendance(Request $request, Employee $employee) {
        $data = $request->validate([
            'date'=>'required|date',
            'status'=>'required|in:present,absent,half_day,holiday,leave',
            'check_in'=>'nullable|date_format:H:i',
            'check_out'=>'nullable|date_format:H:i',
            'notes'=>'nullable|string|max:200',
        ]);
        $data['employee_id'] = $employee->id;
        EmployeeAttendance::updateOrCreate(
            ['employee_id'=>$employee->id,'date'=>$data['date']],
            $data
        );
        return back()->with('success', 'Attendance marked.');
    }
    public function generatePayslip(Request $request, Employee $employee) {
        $data = $request->validate([
            'month'=>'required|integer|min:1|max:12',
            'year'=>'required|integer|min:2020',
            'deductions'=>'nullable|numeric|min:0',
            'notes'=>'nullable|string|max:300',
        ]);
        $workingDays = cal_days_in_month(CAL_GREGORIAN, $data['month'], $data['year']);
        $presentDays = $employee->attendances()
            ->whereMonth('date',$data['month'])->whereYear('date',$data['year'])
            ->whereIn('status',['present','half_day'])->count();
        $gross = $employee->grossSalary();
        $netSalary = ($gross / $workingDays * $presentDays) - ($data['deductions'] ?? 0);
        $payslip = Payslip::updateOrCreate(
            ['employee_id'=>$employee->id,'month'=>$data['month'],'year'=>$data['year']],
            [
                'working_days'=>$workingDays,
                'present_days'=>$presentDays,
                'basic_salary'=>$employee->basic_salary,
                'hra'=>$employee->hra,
                'other_allowance'=>$employee->other_allowance,
                'gross_salary'=>$gross,
                'deductions'=>$data['deductions'] ?? 0,
                'net_salary'=>max(0, $netSalary),
                'notes'=>$data['notes'] ?? null,
                'status'=>'generated',
            ]
        );
        return redirect()->route('print.payslip', $payslip)->with('success', 'Payslip generated.');
    }
}
