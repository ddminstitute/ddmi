<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandingInstruction extends Model
{
    protected $fillable = [
        'account_id','instruction_type','to_account_id','amount','frequency',
        'execution_day','start_date','end_date','last_executed_date','next_execution_date',
        'executed_count','status','description','created_by',
    ];
    protected $casts = ['amount' => 'decimal:2','start_date' => 'date','end_date' => 'date','last_executed_date' => 'date','next_execution_date' => 'date'];
    public function account() { return $this->belongsTo(Account::class); }
    public function toAccount() { return $this->belongsTo(Account::class, 'to_account_id'); }
}
