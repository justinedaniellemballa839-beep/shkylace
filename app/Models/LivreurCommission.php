<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LivreurCommission extends Model
{
    protected $fillable = [
        'livreur_id', 'order_id', 'base_amount', 'punctuality_bonus',
        'satisfaction_bonus', 'total_commission', 'client_rating', 'was_punctual',
    ];

    public function livreur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function calculate(float $deliveryFee, bool $punctual, int $rating = 5): array
    {
        $base = $deliveryFee * 0.10;
        $punctualityBonus = $punctual ? $deliveryFee * 0.10 : 0;
        $satisfactionBonus = $rating >= 4 ? $deliveryFee * 0.05 : 0;

        return [
            'base_amount' => $base,
            'punctuality_bonus' => $punctualityBonus,
            'satisfaction_bonus' => $satisfactionBonus,
            'total_commission' => $base + $punctualityBonus + $satisfactionBonus,
        ];
    }
}
