<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Scopes\ZoneScope;
use Laravel\Passport\HasApiTokens;
class DeliveryMan extends Authenticatable
{
    use HasFactory,Notifiable,HasApiTokens;

    protected $casts = [
        'zone_id' => 'integer',
        'status'=>'boolean',
        'active'=>'integer',
        'available'=>'integer',
        'earning'=>'float',
        'restaurant_id'=>'integer',
        'current_orders'=>'integer',
        'current_lat'=>'double',
        'current_lng'=>'double',

    ];

    protected $hidden = [
        'password',
        'auth_token',
    ];

    public function wallet()
    {
        return $this->hasOne(DeliveryManWallet::class);
    }

    public function userinfo()
    {
        return $this->hasOne(UserInfo::class,'deliveryman_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function time_logs()
    {
        return $this->hasMany(TimeLog::class, 'user_id', 'id');
    }

    public function order_transaction()
    {
        return $this->hasMany(OrderTransaction::class);
    }

    public function todays_earning()
    {
        return $this->hasMany(OrderTransaction::class)->whereDate('created_at',now());
    }

    public function this_week_earning()
    {
        return $this->hasMany(OrderTransaction::class)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function this_month_earning()
    {
        return $this->hasMany(OrderTransaction::class)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
    }

    public function todaysorders()
    {
        return $this->hasMany(Order::class)->whereDate('accepted',now());
    }

    public function this_week_orders()
    {
        return $this->hasMany(Order::class)->whereBetween('accepted', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function delivery_history()
    {
        return $this->hasMany(DeliveryHistory::class, 'delivery_man_id');
    }

    public function last_location()
    {
        return $this->hasOne(DeliveryHistory::class, 'delivery_man_id')->latest();
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function reviews()
    {
        return $this->hasMany(DMReview::class);
    }

    public function rating()
    {
        return $this->hasMany(DMReview::class)
            ->select(DB::raw('avg(rating) average, count(delivery_man_id) rating_count, delivery_man_id'))
            ->groupBy('delivery_man_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1)->where('application_status','approved');
    }

    public function scopeEarning($query)
    {
        return $query->where('earning', 1);
    }
    
    public function scopeAbsherrider($query)
    {
        return $query->where('earning', 0);
    }

    public function scopeAvailable($query)
    {
        return $query->where('current_orders', '<' ,config('dm_maximum_orders'));
    }

    public function scopeZonewise($query)
    {
        return $query->where('type','zone_wise');
    }
    public function scopeNotonbreak($query)
    {
        return $query->where('on_break','0');
    }
    
    public function scopeDistance($query,$lat,$lng){
        $query->selectRaw('*,(6371 * acos(cos(radians(?)) 
                   * cos(radians(current_lat)) 
                   * cos(radians(current_lng) - radians(?)) 
                   + sin(radians(?)) 
                   * sin(radians(current_lat)))) 
                   AS distance', [$lat, $lng, $lat]);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }
}
