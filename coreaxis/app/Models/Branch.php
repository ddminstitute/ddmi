<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['branch_code','branch_name','address','city','state','pincode','phone','email','manager_name','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function accounts() { return $this->hasMany(Account::class); }
}
