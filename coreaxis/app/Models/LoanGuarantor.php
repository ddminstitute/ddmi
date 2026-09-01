<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanGuarantor extends Model
{
    protected $fillable = [
        'loan_id','name','relation','phone','email','address',
        'id_proof_type','id_proof_number','id_proof_file',
    ];
    public function loan() { return $this->belongsTo(Loan::class); }
}
