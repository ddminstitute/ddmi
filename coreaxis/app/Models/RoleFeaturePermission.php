<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleFeaturePermission extends Model
{
    protected $fillable = ['feature_key', 'role', 'is_enabled'];
    protected $casts = ['is_enabled' => 'boolean'];

    public static function isEnabled(string $role, string $feature): bool
    {
        $perm = static::where('role', $role)->where('feature_key', $feature)->first();
        return $perm ? $perm->is_enabled : true;
    }
}
