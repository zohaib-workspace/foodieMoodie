<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuery extends Model
{
    protected $table = 'user_queries';
    protected $fillable = ['user_id', 'query_id', 'name', 'description', 'images'];
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
