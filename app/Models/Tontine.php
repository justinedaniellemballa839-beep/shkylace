<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tontine extends Model
{
    protected $fillable = [
        'shop_id', 'created_by', 'title', 'description',
        'target_amount', 'collected_amount', 'deadline', 'status',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'deadline' => 'date',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TontineMember::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(TontineProduct::class);
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(TontineContribution::class);
    }

    public function getProgressPercentAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, round(($this->collected_amount / $this->target_amount) * 100, 1));
    }

    public function isComplete(): bool
    {
        return $this->collected_amount >= $this->target_amount;
    }
}
