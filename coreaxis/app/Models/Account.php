<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id', 'customer_id', 'account_number', 'account_type', 'balance',
        'currency', 'status', 'notes',
    ];

    protected $casts = ['balance' => 'decimal:2'];

    public function user() { return $this->belongsTo(User::class); }
    public function nominees() { return $this->hasMany(\App\Models\Nominee::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function transactions() { return $this->hasMany(Transaction::class)->latest(); }
    public function loans() { return $this->hasMany(Loan::class); }

    public static function generateAccountNumber(): string
    {
        do {
            $number = 'CA' . date('y') . str_pad(random_int(0, 9999999), 7, '0', STR_PAD_LEFT);
        } while (self::where('account_number', $number)->exists());
        return $number;
    }

    public function getTypeLabel(): string
    {
        return match($this->account_type) {
            'savings' => 'Savings Account',
            'checking' => 'Checking Account',
            'current' => 'Current Account',
            'fixed_deposit' => 'Fixed Deposit',
            default => ucfirst($this->account_type),
        };
    }
}
