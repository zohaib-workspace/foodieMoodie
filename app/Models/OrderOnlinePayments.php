<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Scopes\ZoneScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderOnlinePayments extends Model
{
    use HasFactory;
    protected $table = "order_online_payments";
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'user_id' => 'integer',
        'payable_amount' => 'float',
        'paid_amount' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        
    ];
}    
    