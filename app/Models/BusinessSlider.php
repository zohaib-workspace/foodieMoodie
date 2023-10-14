<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ZoneScope;

class BusinessSlider extends Model
{
    use HasFactory;
    protected $table = "business_slider";
    
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    

}
