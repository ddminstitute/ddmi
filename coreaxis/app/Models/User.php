<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\FeatureService;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phone', 'role', 'is_active', 'customer_id'];
    protected $hidden = ['password', 'remember_token'];

    public function accounts() { return $this->hasMany(Account::class); }
    public function loans() { return $this->hasMany(Loan::class); }
    public function customer() { return $this->belongsTo(Customer::class, 'customer_id'); }

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool { return in_array($this->role, ['super_admin', 'admin']); }
    public function hasRole(string $role): bool { return $this->role === $role; }
    public function hasFeature(string $feature): bool { return FeatureService::roleHas($this->role ?? 'cashier', $feature); }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }
}
