<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'request_number','customer_id','account_id','requested_by','request_type',
        'details','status','processed_by','remarks','processed_at',
    ];
    protected $casts = ['processed_at' => 'datetime'];
    public function customer() { return $this->belongsTo(Customer::class); }
    public function account() { return $this->belongsTo(Account::class); }
    public function requestedBy() { return $this->belongsTo(User::class, 'requested_by'); }
    public function processedBy() { return $this->belongsTo(User::class, 'processed_by'); }
    public static function generateNumber(): string {
        do { $n = 'SR'.date('Ym').str_pad(random_int(0,9999),4,'0',STR_PAD_LEFT); }
        while (static::where('request_number',$n)->exists());
        return $n;
    }
    public function getStatusBadge(): string {
        return match($this->status) {
            'pending' => 'warning','approved' => 'info','completed' => 'success','rejected' => 'danger', default => 'secondary',
        };
    }
}
