<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }
    public function delivery_man()
    {
        return $this->hasOne(DeliveryMan::class, 'id', 'delivery_man_id');
    }
    
    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s',strtotime($value));
    }
}
