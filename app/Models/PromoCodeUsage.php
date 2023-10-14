<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeUsage extends Model
{
    protected $fillable = [
        'user_id',
        'promo_code_id',
    ];

    // Define the relationship with PromoCode model
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }

    // Define the relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

