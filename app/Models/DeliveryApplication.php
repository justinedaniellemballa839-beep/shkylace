<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryApplication extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'phone',
        'quartier', 'ville', 'emergency_contact', 'cv_path',
        'photo_path', 'id_card_path', 'cover_letter', 'status', 'reviewed_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
