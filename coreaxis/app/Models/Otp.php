<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Otp extends Model
{
    protected $fillable = ['user_id','code','purpose','used','expires_at'];
    protected $casts = ['used' => 'boolean','expires_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }

    public static function generate(int $userId, string $purpose = 'transaction'): string
    {
        static::where('user_id', $userId)->where('purpose', $purpose)->update(['used' => true]);
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        static::create([
            'user_id'    => $userId,
            'code'       => $code,
            'purpose'    => $purpose,
            'used'       => false,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
        return $code;
    }

    public static function verify(int $userId, string $code, string $purpose = 'transaction'): bool
    {
        $otp = static::where('user_id', $userId)
            ->where('purpose', $purpose)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
        if ($otp) { $otp->update(['used' => true]); return true; }
        return false;
    }
}
