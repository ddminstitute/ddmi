<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['entry_number','entry_date','narration','source_type','source_id','reference','total_debit','total_credit','is_balanced','created_by'];
    protected $casts = ['entry_date'=>'date','is_balanced'=>'boolean'];

    public function lines() { return $this->hasMany(JournalEntryLine::class); }
    public function createdBy() { return $this->belongsTo(User::class,'created_by'); }

    public static function generateEntryNumber(): string
    {
        $count = static::count() + 1;
        return 'JE'.date('Ym').str_pad($count,5,'0',STR_PAD_LEFT);
    }
}
