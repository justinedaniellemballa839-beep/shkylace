<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'shop_id', 'category_id', 'name', 'description', 'price',
        'quantity', 'unit', 'image', 'discount_percent', 'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getFinalPriceAttribute(): float
    {
        if ($this->discount_percent > 0) {
            return round($this->price * (1 - $this->discount_percent / 100), 2);
        }
        return (float) $this->price;
    }

    public function getLocationAttribute(): string
    {
        return $this->shop
            ? "{$this->shop->quartier}, {$this->shop->ville}"
            : 'Non spécifié';
    }
}
