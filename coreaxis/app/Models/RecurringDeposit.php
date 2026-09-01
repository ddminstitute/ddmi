<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringDeposit extends Model
{
    protected $fillable = [
        'rd_number','customer_id','account_id','monthly_installment','interest_rate',
        'tenure_months','start_date','maturity_date','total_deposited','interest_earned',
        'maturity_amount','installments_paid','installments_missed','status',
        'next_due_date','matured_at','closed_at','created_by',
    ];
    protected $casts = [
        'start_date' => 'date','maturity_date' => 'date','next_due_date' => 'date',
        'monthly_installment' => 'decimal:2','maturity_amount' => 'decimal:2',
        'total_deposited' => 'decimal:2','interest_earned' => 'decimal:2',
    ];

    public function account() { return $this->belongsTo(Account::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function installments() { return $this->hasMany(RdInstallment::class, 'rd_id')->orderBy('installment_number'); }

    public static function generateRdNumber(): string
    {
        do { $n = 'RD'.date('Y').str_pad(random_int(0,99999),5,'0',STR_PAD_LEFT); }
        while (static::where('rd_number',$n)->exists());
        return $n;
    }

    public function getStatusBadge(): string
    {
        return match($this->status) {
            'active'   => 'success', 'matured' => 'info',
            'closed'   => 'secondary', 'defaulted' => 'danger', default => 'secondary',
        };
    }
}
