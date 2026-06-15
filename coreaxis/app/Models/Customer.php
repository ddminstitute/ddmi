<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    protected $fillable = [
        'customer_id','name','father_name','mother_name','gender','date_of_birth',
        'phone','alternate_phone','email','address','city','state','pincode',
        'pan_number','aadhaar_number','occupation','annual_income',
        'photo','signature','pan_document','aadhaar_document','status','notes'
    ];
    protected $casts = ['date_of_birth' => 'date'];

    public static function generateCustomerId(): string {
        $count = static::count() + 1;
        return 'CUST' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
    public function accounts() { return $this->hasMany(Account::class); }
    public function loans() {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('loans', 'customer_id')) {
            return $this->hasMany(Loan::class)->whereRaw('0=1');
        }
        return $this->hasMany(Loan::class);
    }
    public function collectionPlans() { return $this->hasMany(CollectionPlan::class); }
    public function getStatusBadge(): string {
        return match($this->status) { 'active'=>'success','inactive'=>'secondary','blacklisted'=>'danger', default=>'secondary' };
    }
}
