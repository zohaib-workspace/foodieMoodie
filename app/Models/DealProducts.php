<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealProducts  extends Model
{
    use HasFactory;
    protected $table = 'deal_products';
    public $timestamps = false;
    
    public function Deal()
    {
        return $this->belongsTo(SpecialDeal::class, 'deal_id', 'id');
    }
    public function food(){
        return $this->belongsTo(Food::class, 'food_id', 'id');
        
    }
    
}
