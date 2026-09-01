<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CollectionPlan extends Model {
    protected $fillable = [
        'plan_number','customer_id','account_id','plan_name','collection_type',
        'collection_amount','start_date','end_date','total_installments','maturity_amount','status','notes'
    ];
    protected $casts = ['start_date'=>'date','end_date'=>'date'];

    public static function generatePlanNumber(): string {
        $count = static::count() + 1;
        return 'COL' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function account() { return $this->belongsTo(Account::class); }
    public function entries() { return $this->hasMany(CollectionEntry::class); }
    public function paidInstallments(): int { return $this->entries()->count(); }
    public function totalCollected(): float { return $this->entries()->sum('amount'); }
    public function getTypeBadge(): string {
        return match($this->collection_type) { 'daily'=>'info','weekly'=>'warning','monthly'=>'primary', default=>'secondary' };
    }
}
