<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    protected $fillable = [
        'user_id', 'market_id', 'name', 'description', 'address',
        'quartier', 'ville', 'latitude', 'longitude', 'phone',
        'logo', 'status', 'rating',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function tontines(): HasMany
    {
        return $this->hasMany(Tontine::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
