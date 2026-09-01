<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CompanyExpense extends Model {
    protected $fillable = [
        'expense_number','category','description','amount','expense_date',
        'payment_mode','paid_to','receipt_file','approved_by','notes'
    ];
    protected $casts = ['expense_date'=>'date'];

    public static function generateExpenseNumber(): string {
        $count = static::count() + 1;
        return 'EXP' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
