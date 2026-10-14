<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'shop_id', 'livreur_id', 'status',
        'delivery_type', 'delivery_date', 'delivery_time', 'delivery_address',
        'quartier', 'zone', 'subtotal', 'delivery_fee', 'discount', 'total', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'subtotal' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function livreur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tracking(): HasMany
    {
        return $this->hasMany(DeliveryTracking::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'SKY-' . strtoupper(substr(uniqid(), -8));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'en_preparation' => 'En préparation',
            'expediee' => 'Expédiée',
            'en_cours_livraison' => 'En cours de livraison',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
            default => $this->status,
        };
    }
}
