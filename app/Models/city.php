<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class city extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'provence', 'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(country::class);
    }
}
