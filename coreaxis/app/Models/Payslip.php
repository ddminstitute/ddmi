<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model {
    protected $fillable = [
        'employee_id','month','year','working_days','present_days',
        'basic_salary','hra','other_allowance','gross_salary','deductions','net_salary','notes','status'
    ];
    public function employee() { return $this->belongsTo(Employee::class); }
    public function getMonthName(): string { return date('F', mktime(0,0,0,$this->month,1)); }
}
