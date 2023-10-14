<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $casts = [
        'parent_id' => 'integer',
        'position' => 'integer',
        'priority' => 'integer',
        'status' => 'integer'
    ];

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    public function category_products(){
        return $this->hasMany(Food::class,'category_id','id')->select('id','name','image','description','price','available_time_starts',
        'available_time_ends','category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function childes()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }
}
