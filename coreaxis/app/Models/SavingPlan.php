<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SavingPlan extends Model {
    protected $fillable = [
        'plan_code','plan_name','plan_type','minimum_amount','interest_rate','tenure_months','description','is_active'
    ];
    protected $casts = ['is_active'=>'boolean'];

    public static function generatePlanCode(): string {
        $count = static::count() + 1;
        return 'SP' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
    public function getTypeBadge(): string {
        return match($this->plan_type) { 'daily'=>'info','weekly'=>'warning','monthly'=>'primary','yearly'=>'success', default=>'secondary' };
    }
}
