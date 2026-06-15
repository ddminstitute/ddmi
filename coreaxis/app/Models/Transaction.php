<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id', 'transaction_type', 'amount', 'balance_before',
        'balance_after', 'description', 'reference_number', 'related_account_id', 'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function account() { return $this->belongsTo(Account::class); }
    public function relatedAccount() { return $this->belongsTo(Account::class, 'related_account_id'); }

    public static function generateReference(): string
    {
        do {
            $ref = 'TXN' . date('Ymd') . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('reference_number', $ref)->exists());
        return $ref;
    }

    public function getTypeLabel(): string
    {
        return match($this->transaction_type) {
            'deposit' => 'Deposit',
            'withdrawal' => 'Withdrawal',
            'transfer_in' => 'Transfer In',
            'transfer_out' => 'Transfer Out',
            default => ucfirst($this->transaction_type),
        };
    }

    public function getTypeBadge(): string
    {
        return match($this->transaction_type) {
            'deposit', 'transfer_in' => 'success',
            'withdrawal', 'transfer_out' => 'danger',
            default => 'secondary',
        };
    }
}
