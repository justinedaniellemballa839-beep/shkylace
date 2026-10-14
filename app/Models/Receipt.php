<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $fillable = ['payment_id', 'receipt_number', 'details'];

    protected function casts(): array
    {
        return ['details' => 'array'];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public static function generateNumber(): string
    {
        return 'REC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}
