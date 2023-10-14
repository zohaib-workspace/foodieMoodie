<?php

namespace App\Models;

use App\Scopes\RestaurantScope;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoints extends Model
{
    protected $table = 'loyalty_points_history';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
