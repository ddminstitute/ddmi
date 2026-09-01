<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nominee extends Model
{
    protected $fillable = [
        'account_id','name','relation','date_of_birth','phone',
        'address','share_percent','is_minor','guardian_name',
    ];
    protected $casts = ['is_minor' => 'boolean','date_of_birth' => 'date'];

    public function account() { return $this->belongsTo(Account::class); }
}
