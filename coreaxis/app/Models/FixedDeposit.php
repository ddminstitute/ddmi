<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedDeposit extends Model
{
    protected $fillable = [
        'fd_number','customer_id','account_id','principal_amount','interest_rate',
        'compounding','tenure_months','start_date','maturity_date','maturity_amount',
        'interest_earned','status','auto_renew','premature_penalty_percent',
        'matured_at','closed_at','closure_reason','notes','created_by',
    ];
    protected $casts = [
        'start_date' => 'date','maturity_date' => 'date',
        'principal_amount' => 'decimal:2','maturity_amount' => 'decimal:2',
        'interest_earned' => 'decimal:2','interest_rate' => 'decimal:2',
        'auto_renew' => 'boolean',
    ];
    public function account() { return $this->belongsTo(Account::class); }
    public function customer() { return $this->belongsTo(Customer::class); }

    public static function generateFdNumber(): string {
        do { $n = 'FD'.date('Y').str_pad(random_int(0,99999),5,'0',STR_PAD_LEFT); }
        while (static::where('fd_number',$n)->exists());
        return $n;
    }

    public static function calculateMaturity(float $principal, float $rate, int $months, string $compounding): float {
        $n = match($compounding) {
            'monthly' => 12,'quarterly' => 4,'half_yearly' => 2,'yearly' => 1,'on_maturity' => 1, default => 4,
        };
        if ($compounding === 'on_maturity') return round($principal * (1 + ($rate/100) * ($months/12)), 2);
        return round($principal * pow(1 + ($rate/100/$n), $n * $months/12), 2);
    }

    public function getStatusBadge(): string {
        return match($this->status) {
            'active' => 'success','matured' => 'info','closed' => 'secondary','premature_closed' => 'warning', default => 'secondary',
        };
    }
}
