<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model {
    protected $fillable = [
        'employee_id','name','phone','email','designation','department','joining_date',
        'basic_salary','hra','other_allowance','pan_number','aadhaar_number',
        'bank_account','bank_name','ifsc_code','photo','status'
    ];
    protected $casts = ['joining_date'=>'date'];

    public static function generateEmployeeId(): string {
        $count = static::count() + 1;
        return 'EMP' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    public function attendances() { return $this->hasMany(EmployeeAttendance::class); }
    public function payslips() { return $this->hasMany(Payslip::class); }
    public function grossSalary(): float { return $this->basic_salary + $this->hra + $this->other_allowance; }
    public function monthAttendance(int $month, int $year) {
        return $this->attendances()->whereMonth('date', $month)->whereYear('date', $year)->get();
    }
}
