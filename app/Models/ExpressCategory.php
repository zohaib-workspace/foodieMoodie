<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ZoneScope;

class ExpressCategory extends Model
{
    use HasFactory;
    protected $table = "express_categories";
    
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    

}
