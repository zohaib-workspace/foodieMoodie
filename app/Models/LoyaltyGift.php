<?php

namespace App\Models;

use App\Scopes\RestaurantScope;
use Illuminate\Database\Eloquent\Model;

class LoyaltyGift extends Model
{
    protected $table = 'loyalty_gifts';
    protected $primaryKey = 'gift_id';
    public $timestamps = false;

    // Specify the fillable columns
    protected $fillable = [
        'title',
        'description',
        'icon',
        'points',
        'status'
    ];
}
