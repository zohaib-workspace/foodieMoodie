<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ZoneScope;

class BusinessType extends Model
{
    use HasFactory;
    protected $casts = [
        'type' => 'string',
    ];
    public function zone()
    {
        return $this->belongsTo(Restaurant::class);
    }
    
    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }
}
