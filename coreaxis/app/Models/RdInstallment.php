<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RdInstallment extends Model
{
    protected $fillable = ['rd_id','installment_number','due_date','paid_date','amount','status','reference_number'];
    protected $casts = ['due_date' => 'date','paid_date' => 'date'];
    public function rd() { return $this->belongsTo(RecurringDeposit::class, 'rd_id'); }
}
