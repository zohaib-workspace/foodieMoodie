<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CountryScope;
class country extends Model
{
    use HasFactory;

    protected $casts = [
        'id'=>'integer',
        'status'=>'integer',
        'phonecode'=>'integer',
        'timezone_id' => 'integer',
    ];
     public function timezone()
    {
        return $this->hasOne(Timezone::class,'id','timezone_id');
    }
}
