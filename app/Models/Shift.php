<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Scopes\ZoneScope;

class Shift extends Model
{
    use HasFactory;
 
    // public function delivery_man()
    // {
    //     return $this->hasOne('App\Models\DeliveryMan','delivery_man');
    // }
    public function rider()
    {
        return $this->hasOne('App\Models\DeliveryMan','id','delivery_man');
    }
     public function zone()
    {
        return $this->hasOne('App\Models\Zone','id','zone_id')->select("id","name","currency","timezone_id");
    }
    public function scopeActive($query){
        $query->where("status","Active");    
    }
     public function scopeInactive($query){
        $query->where("status","Inactive");    
    }
    public function scopeStarted($query){
        $query->where("status","Started");    
    }
    public function scopeEnded($query){
        $query->where("status","Ended");    
    } 
    public function scopeLeft($query){
        $query->where("status","Left");    
    }
    public function scopeAssigned($query){
        $query->where("status","Assigned");    
    }
    
    // protected static function booted()
    // {
    //     static::addGlobalScope(new ZoneScope);
    // }
}
