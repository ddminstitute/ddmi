<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    protected $fillable = [
        'cheque_number','account_id','cheque_type','drawee_bank','drawee_branch',
        'drawer_name','amount','cheque_date','deposit_date','clearing_date',
        'status','bounce_reason','bounce_charge','description','reference_number','created_by',
    ];
    protected $casts = ['amount' => 'decimal:2','cheque_date' => 'date','deposit_date' => 'date','clearing_date' => 'date'];
    public function account() { return $this->belongsTo(Account::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function getStatusBadge(): string {
        return match($this->status) {
            'pending' => 'warning','cleared' => 'success','bounced' => 'danger','cancelled' => 'secondary', default => 'secondary',
        };
    }
}
