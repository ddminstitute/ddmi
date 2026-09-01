<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $fillable = ['code','name','type','normal_balance','description','is_system','is_active'];
    protected $casts = ['is_system'=>'boolean','is_active'=>'boolean'];

    public function lines() { return $this->hasMany(JournalEntryLine::class); }

    public function getBalance(): float
    {
        $debits  = $this->lines()->where('type','debit')->sum('amount');
        $credits = $this->lines()->where('type','credit')->sum('amount');
        return $this->normal_balance === 'debit' ? ($debits - $credits) : ($credits - $debits);
    }

    public function getTypeBadge(): string
    {
        return match($this->type) {
            'asset'=>'primary','liability'=>'danger','equity'=>'info','income'=>'success','expense'=>'warning',default=>'secondary',
        };
    }

    public static function byCode(string $code): self
    {
        return static::where('code',$code)->firstOrFail();
    }
}
