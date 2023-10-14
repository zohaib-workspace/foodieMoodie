<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'type',
        'max_uses',
        'start_date',
        'end_date',
        'status',
        'business_type',
        'zone_id',
        'minimum_order_value',
        'categories',
        'redemption_rule',
    ];
    
    // Define the relationship with PromoCodeUsage model
    public function usages()
    {
        return $this->hasMany(PromoCodeUsage::class, 'promo_code_id');
    }
}

