<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCollateral extends Model
{
    protected $fillable = [
        'loan_id','collateral_type','description','estimated_value',
        'valuation_date','document_file','charge_created_date','charge_released_date','status',
    ];
    protected $casts = ['valuation_date' => 'date','charge_created_date' => 'date','charge_released_date' => 'date','estimated_value' => 'decimal:2'];
    public function loan() { return $this->belongsTo(Loan::class); }
}
