<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialDeal  extends Model
{
    use HasFactory;
    protected $table = 'special_deals';
    public $timestamps = false;
    
    public function products()
    {
        return $this->hasMany(DealProducts::class, 'deal_id');
    }

    // protected $fillable = [
    // 'order_id',
    // 'deal_id',
    // 'quantity',
    // 'price',
    // 'comment',
    // 'required_products',
    // 'optional_products',
    // 'tax_amount'
    // ];
    
}
