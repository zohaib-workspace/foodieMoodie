<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ZoneScope;

class Query extends Model
{
    use HasFactory;
    protected $table = 'queries';
    
    public function children()
    {
        return $this->hasMany(Query::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Query::class, 'parent_id');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }
}
