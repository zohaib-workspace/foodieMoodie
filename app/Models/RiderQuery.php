<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\ZoneScope;
class RiderQuery extends Model
{
    protected $table = 'rider_queries';
    protected $fillable = ['rider_id', 'query_id', 'name', 'description', 'images'];
public $timestamps = false;
    public function queries()
    {
        return $this->belongsTo('App\Models\Query');
    }

    public function rider()
    {
        return $this->belongsTo(DeliveryMan::class, 'rider_id');
    }
}
