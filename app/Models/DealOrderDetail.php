<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealOrderDetail  extends Model
{
    /*use HasFactory;*/
    protected $table = 'deal_order_detail';
    
    public function deal_data()
    {
        return $this->belongsTo(SpecialDeal::class, 'deal_id');
    }

    protected $fillable = [
    'order_id',
    'deal_id',
    'quantity',
    'price',
    'comment',
    'required_products',
    'optional_products',
    'tax_amount'
    ];
    
}
