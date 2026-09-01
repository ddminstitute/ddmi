<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandDraft extends Model
{
    protected $fillable = [
        'dd_number','account_id','instrument_type','payee_name','payable_at_city',
        'payable_at_bank','amount','charges','total_debited','issue_date','valid_until',
        'status','cancellation_reason','cancelled_at','created_by',
    ];
    protected $casts = ['amount' => 'decimal:2','charges' => 'decimal:2','total_debited' => 'decimal:2','issue_date' => 'date','valid_until' => 'date','cancelled_at' => 'datetime'];
    public function account() { return $this->belongsTo(Account::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public static function generateDdNumber(): string {
        do { $n = 'DD'.date('Ymd').str_pad(random_int(0,9999),4,'0',STR_PAD_LEFT); }
        while (static::where('dd_number',$n)->exists());
        return $n;
    }
}
