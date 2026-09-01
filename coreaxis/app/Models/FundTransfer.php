<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{
    protected $fillable = [
        'reference_number','account_id','transfer_mode','amount','beneficiary_name',
        'beneficiary_account','beneficiary_ifsc','beneficiary_bank','description',
        'status','bank_reference','initiated_at','completed_at','failure_reason','charges','created_by',
    ];
    protected $casts = ['amount' => 'decimal:2','charges' => 'decimal:2','initiated_at' => 'datetime','completed_at' => 'datetime'];
    public function account() { return $this->belongsTo(Account::class); }
    public static function generateReference(): string {
        do { $r = 'FT'.date('Ymd').str_pad(random_int(0,99999),5,'0',STR_PAD_LEFT); }
        while (static::where('reference_number',$r)->exists());
        return $r;
    }
    public function getStatusBadge(): string {
        return match($this->status) {
            'completed' => 'success','pending' => 'warning','processing' => 'info','failed' => 'danger','reversed' => 'secondary', default => 'secondary',
        };
    }
}
