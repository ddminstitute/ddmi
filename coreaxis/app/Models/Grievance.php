<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grievance extends Model
{
    protected $fillable = [
        'ticket_number','customer_id','account_id','reported_by','subject','description',
        'category','priority','status','assigned_to','resolution_notes','resolved_at','sla_due_date',
    ];
    protected $casts = ['resolved_at' => 'datetime','sla_due_date' => 'date'];
    public function customer() { return $this->belongsTo(Customer::class); }
    public function account() { return $this->belongsTo(Account::class); }
    public function reportedBy() { return $this->belongsTo(User::class, 'reported_by'); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }
    public static function generateTicket(): string {
        do { $n = 'GRV'.date('Ym').str_pad(random_int(0,9999),4,'0',STR_PAD_LEFT); }
        while (static::where('ticket_number',$n)->exists());
        return $n;
    }
    public function getStatusBadge(): string {
        return match($this->status) {
            'open' => 'danger','in_progress' => 'warning','resolved' => 'success','closed' => 'secondary','escalated' => 'dark', default => 'secondary',
        };
    }
}
