<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'password', 'pin_hash',
        'role', 'quartier', 'ville', 'emergency_contact',
        'latitude', 'longitude', 'is_active', 'is_verified',
    ];

    protected $hidden = ['password', 'pin_hash', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Order::class, 'livreur_id');
    }

    public function tontineMemberships()
    {
        return $this->hasMany(TontineMember::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'administrateur';
    }

    public function isVendeur(): bool
    {
        return $this->role === 'vendeur';
    }

    public function isLivreur(): bool
    {
        return $this->role === 'livreur';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}
