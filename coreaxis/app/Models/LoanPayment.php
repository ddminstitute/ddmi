<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $fillable = [
        'loan_id', 'account_id', 'payment_number', 'amount',
        'principal_component', 'interest_component', 'outstanding_after',
        'payment_date', 'status', 'reference_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'principal_component' => 'decimal:2',
        'interest_component' => 'decimal:2',
        'outstanding_after' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function loan() { return $this->belongsTo(Loan::class); }
    public function account() { return $this->belongsTo(Account::class); }
}
