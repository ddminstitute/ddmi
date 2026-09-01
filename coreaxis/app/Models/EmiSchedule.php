<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmiSchedule extends Model {
    protected $fillable = [
        'loan_id','installment_number','due_date','emi_amount',
        'principal_component','interest_component','outstanding_balance',
        'paid_amount','paid_date','status','receipt_number'
    ];
    protected $casts = ['due_date'=>'date','paid_date'=>'date'];

    public function loan() { return $this->belongsTo(Loan::class); }
    public function getStatusBadge(): string {
        return match($this->status) { 'paid'=>'success','pending'=>'warning','partial'=>'info','overdue'=>'danger', default=>'secondary' };
    }
}
// relationship added by gap implementation
