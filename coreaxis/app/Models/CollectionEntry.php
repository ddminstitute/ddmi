<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CollectionEntry extends Model {
    protected $fillable = [
        'collection_plan_id','amount','collection_date','installment_number',
        'receipt_number','payment_mode','collected_by','notes'
    ];
    protected $casts = ['collection_date'=>'date'];

    public static function generateReceiptNumber(): string {
        $count = static::count() + 1;
        return 'RCP' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }
    public function plan() { return $this->belongsTo(CollectionPlan::class, 'collection_plan_id'); }
}
