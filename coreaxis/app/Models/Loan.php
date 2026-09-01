<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'customer_id', 'account_id', 'loan_number', 'loan_type', 'amount',
        'principal_amount', 'interest_rate', 'tenure_months', 'monthly_emi', 'emi_amount',
        'total_amount', 'paid_amount', 'outstanding_amount', 'purpose', 'status',
        'approved_by', 'approved_at', 'disbursed_at',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'monthly_emi' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'outstanding_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function account() { return $this->belongsTo(Account::class); }
    public function payments() { return $this->hasMany(LoanPayment::class)->latest(); }
    public function emiSchedules() { return $this->hasMany(EmiSchedule::class)->orderBy('installment_number'); }
    public function guarantors() { return $this->hasMany(\App\Models\LoanGuarantor::class); }
    public function collaterals() { return $this->hasMany(\App\Models\LoanCollateral::class); }

    public static function generateLoanNumber(): string
    {
        do {
            $number = 'LN' . date('Y') . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('loan_number', $number)->exists());
        return $number;
    }

    public static function calculateEmi(float $principal, float $annualRate, int $months): float
    {
        if ($annualRate == 0) return $principal / $months;
        $r = $annualRate / 12 / 100;
        return round($principal * $r * pow(1 + $r, $months) / (pow(1 + $r, $months) - 1), 2);
    }

    public function getStatusBadge(): string
    {
        return match($this->status) {
            'pending' => 'warning','approved' => 'info','active' => 'success',
            'rejected' => 'danger','closed' => 'secondary', default => 'secondary',
        };
    }

    public function getTypeLabel(): string
    {
        return match($this->loan_type) {
            'personal' => 'Personal Loan','home' => 'Home Loan',
            'auto' => 'Auto Loan','business' => 'Business Loan',
            default => ucfirst($this->loan_type),
        };
    }

    public function paidInstallments(): int
    {
        return $this->payments()->where('status', 'paid')->count();
    }
}
