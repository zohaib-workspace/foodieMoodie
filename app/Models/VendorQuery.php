<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorQuery extends Model
{
    protected $table = 'vendor_queries';
    protected $fillable = ['vendor_id', 'query_id', 'name', 'description', 'images'];
public $timestamps = false;
    public function queries()
    {
        return $this->belongsTo('App\Models\Query');
    }
    
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
