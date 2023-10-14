<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ZoneScope;


class Express extends Model
{
    use HasFactory;
    protected $table = "absher_express";

    protected $fillable = [
        'user_id',
        'pickup_name',
            'pickup_phone',
            'pickup_address',
            'dropoff_name',
            'dropoff_phone',
            'dropoff_address',
            'price',
            'pickup_lat',
            'pickup_lng',
            'dropoff_lat',
            'dropoff_lng',
            'description',
            'category_id',
            'status',
            'pickup_address_details',
            'dropoff_address_details',
    ];


    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function category(){
        return $this->belongsTo(ExpressCategory::class);
    }
    
    // public function scopeActive($query)
    // {
    //     return $query->where('status', '=', 1);
    // }

    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }
}
