<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model {
    protected $fillable = ['employee_id','date','status','check_in','check_out','notes'];
    protected $casts = ['date'=>'date'];
    public function employee() { return $this->belongsTo(Employee::class); }
    public function getStatusBadge(): string {
        return match($this->status) { 'present'=>'success','absent'=>'danger','half_day'=>'warning','holiday'=>'info','leave'=>'secondary', default=>'secondary' };
    }
}
