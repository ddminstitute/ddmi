<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EodRecord extends Model
{
    protected $fillable = [
        'business_date','total_deposits','total_withdrawals','total_transfers',
        'interest_posted','penalties_applied','accounts_count','transactions_count',
        'emis_marked_overdue','status','notes','processed_by','processed_at',
    ];
    protected $casts = ['business_date' => 'date','processed_at' => 'datetime'];
    public function processedBy() { return $this->belongsTo(User::class, 'processed_by'); }
}
