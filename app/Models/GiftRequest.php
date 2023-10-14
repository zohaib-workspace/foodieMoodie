<?php

namespace App\Models;

use App\Scopes\RestaurantScope;
use Illuminate\Database\Eloquent\Model;

class GiftRequest extends Model
{
    protected $table = 'gift_requests';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'gift_id',
        'status',
        'admin_response',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function gift()
    {
        return $this->belongsTo(LoyaltyGift::class, 'gift_id', 'id');
    }
    
}
