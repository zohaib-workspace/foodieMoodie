<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Scopes\ZoneScope;

class ShiftHistory extends Model
{
    use HasFactory;
    protected $table = 'shifts_history';
   
    public function shift()
    {
        return $this->hasOne(App\Models\Shift::class,'id','shift_id');
    }
}
