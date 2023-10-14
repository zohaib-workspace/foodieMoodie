<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEarning extends Model
{
    use HasFactory;
    
    protected $table = "order_earning";

    protected $casts = [
        'delivery_man_earning' => 'float',
        'restaurant_earning' => 'float',
        
    ];

    protected $fillable = ['delivery_man_id','order_id','restaurant_id','restaurant_earning','paid_to_delivery_man','paid_to_restaurant'];
}
