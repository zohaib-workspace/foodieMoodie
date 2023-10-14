<?php

namespace App\CentralLogics;

use App\Models\Category;
use App\Models\Food;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Shift;
use App\Models\LoyaltyPoints;
use App\Models\ShiftHistory;
use App\Models\Zone;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use \DateTime;

class GeneralLogic
{
    // SHIFT SECTION
    public static function getShift($shiftId){
        return Shift::with(['zone.timezone'])->where('id',$shiftId)->first();
    }
    public static function getShifts($zone_id, $date){
        $shifts = Shift::with('zone')->whereIn('zone_id', $zone_id)
        ->where('shift_date',$date)->where('status','Active')->orderBy('start_time', 'asc')->get();
        return $shifts;
    }
    public static function getEndedShifts($zone_id, $user_id, $limit = 10, $offset = 1){
        $now = now()->toDateString();
        $shifts = Shift::with('zone')->where('delivery_man', $user_id)->whereIn('zone_id', $zone_id)
         ->where(function ($query) use ($now) {
        $query->where('status', 'Ended')->orWhere(function ($query) use ($now) {
                  $query->where('shift_date', '<', $now);
              });
    })
        ->orderBy('shift_date', 'desc')->paginate($limit, ['*'], 'page', $offset);
        // return $shifts->items();
        return [
            'total_size' => $shifts->total(),
            'limit' => $limit,
            'offset' => $offset,
            'shifts' => $shifts->items()
        ];
    }
    public static function getTodayCurrentShift($riderId){
        $shifts = Shift::with(['zone.timezone'])->where('delivery_man',$riderId)->whereIn('status',['Started', 'Paused'])->get();
        if(count($shifts) > 0){
            $shiftArr = [];
            foreach($shifts as $shift){
                $shiftDate = $shift->shift_date." ".$shift->end_time . "\n";
                $nowDate =  static::getZoneDate($shift->zone_id) . "\n";
                
                if($nowDate > $shiftDate){
                    $shift->working_status = 'Over'; // shift over
                }else{
                    $shift->working_status = 'Working';
                }
                // $currentDate =  static::getZoneDate($shift->zone_id,true);
                // if($currentDate > $shift->shift_date){
                //   continue;
                // }
                $shiftArr[] = $shift;
            }
          
            return ["status"=>1, "data"=>$shiftArr];
         }else{
             return ["status"=>-1]; //no shift found to start
         }
        return $shifts;
    }
    public static function getUpcomingShift($date,$dmId){
        $shifts = Shift::with('zone')->where('shift_date',$date)->where("delivery_man",$dmId)->where('status','Assigned')->get();
        return $shifts;
    }
    public static function isShiftStarted($shiftId){
        $shifts = Shift::where('id',$shiftId)
         ->whereIn('status',['Started', "Paused"])->get();
         if(count($shifts) > 0){
            $shift = $shifts[0];
            $shiftDate = $shift->shift_date." ".$shift->end_time;
            $nowDate =  static::getZoneDate($shift->zone_id);
            if($nowDate > $shiftDate){
                return ["status"=>-2]; // shift over
            }
            return ["status"=>1, "data"=>$shift];
         }else{
             return ["status"=>-1]; //no shift found to start
         }
    }
    public static function checkStartShift($shiftId){
         $shifts = Shift::where('id',$shiftId)
         ->where('status','Assigned')->get();
         if(count($shifts) > 0){
            $shift = $shifts[0];
            $shiftDate = $shift->shift_date." ".$shift->end_time;
            $nowDate =  static::getZoneDate($shift->zone_id);
            if($nowDate > $shiftDate){
                return ["status"=>-2]; // shift expired
            }
            $shiftDate = $shift->shift_date." ".$shift->start_time;
            $diff_time=(strtotime(date($nowDate))-strtotime(date($shiftDate)))/60;
            if($diff_time >= -30){
                return ["status"=>1, "data"=>$shift];
            }else{
                return ["status"=>-3,"time"=> round(($diff_time/60),2)."h left"]; // you can start shift when 30 mints or less remaining
            }
            
         }else{
              return ["status"=>-1]; //no shift found to start
         }
    }
    public static function checkShift($shift_id,$rider_id){
        $shifts = Shift::where('id',$shift_id)
        ->where('status','Active')->get();
        if(count($shifts) > 0){
            $shift = $shifts[0];
            // echo "here";
            $riderShifts = Shift::where('shift_date', $shift->shift_date)
                ->whereIn('status', ['Assigned', 'Started', 'Paused'])
                ->where('delivery_man', $rider_id)
                ->where(function($mainQuery) use ($shift) {
                    $mainQuery->where(function($q) use ($shift) {
                    $q->where(function($q2) use ($shift) {
                        $q2->where('start_time', '>=', $shift->start_time)
                            ->where('start_time', '<=', $shift->end_time); 
                    })->orWhere(function($q2) use ($shift) {
                        $q2->where('end_time', '>=', $shift->start_time)
                            ->where('end_time', '<=', $shift->end_time); 
                    });
                })
                ->orWhere(function($q) use ($shift) {
                    $q->where(function($q2) use ($shift) {
                        $q2->where('start_time', '<=', $shift->start_time) // B >= A
                            ->where('end_time', '>=', $shift->start_time); // B <= A
                    })->orWhere(function($q2) use ($shift) {
                        $q2->where('start_time', '<=', $shift->end_time) // B >= A
                            ->where('end_time', '>=', $shift->end_time); // B <= A
                    });
                });
            
        })
                ->get();
                // print_r($riderShifts);
                // var_dump(count($riderShifts));
                // exit;
            if(count($riderShifts)>0){
                return ["status"=>-2]; // already available shift in perticular time
            }
            return ["status"=>1,"data"=>$shifts[0]];
        }else{
            return ["status"=>-1]; //  No Active shift
        }
    }
    
    public static function assign_shift($shift_id, $rider_id){
         
        $result = Shift::where("id",$shift_id)->update([
            "status"=>'Assigned',
            "delivery_man"=>$rider_id
            ]);
        if($result){
            ShiftHistory::insert([
                "shift_id"=>$shift_id,
                "rider_id"=>$rider_id,
                "description" => "Shift has been assgined",
                "status" => "Assigned"
                ]);
        }
            
        return $result;
    }
    public static function startShift($shift_id,$rider = ''){
        $shifts = Shift::where('id',$shift_id)
        ->where('status','Assigned')->get();
        if(!empty($rider)){
            $rider_shifts = Shift::where('delivery_man',$rider)
            ->whereIn('status',['Started', 'Paused'])->get();
            if(count($rider_shifts) > 0){
                return -1;   
            }
        }
        if(count($shifts) > 0){ 
            $shift  = $shifts[0];
        }else{
            return null;
        }
        $nowDate =  static::getZoneDate($shift->zone_id);
         $result = Shift::where("id",$shift_id)->update([
            "status"=>'Started',
            "shift_started_at"=>$nowDate
            ]);
        if($result){
            ShiftHistory::insert([
                "shift_id"=>$shift_id,
                "rider_id"=>$shift->delivery_man,
                "description" => "Shift has been started",
                "status" => "Started",
                "created_at"=>$nowDate
                ]);
        }
            
        return $result;
    }
    // SHIFT SECTION END
    
    public static function getZoneDate($zoneId,$onlyDate = false){
        
        $zone = Zone::with("timezone")->where("id",$zoneId)->first();
        
        if($zone){
            
            $timezone = $zone->timezone;
            date_default_timezone_set($timezone->timezone);
            // $time = $onlyDate?"":" H:i:s";
            if($onlyDate){ return date("Y-m-d");}else{ return date("Y-m-d H:i:s");}
           
        }else{
            return null;
        }
    }
   
    
    public static function getCurrentZone($lat, $lng){
        $point = new Point($lat, $lng);
        $zones = Zone::contains('coordinates', $point)->latest()->get();
        if (count($zones) < 1) {
            return null;
        }
        
        $data = array_filter($zones->toArray(), function ($zone) {
            if ($zone['status'] == 1) {
                return $zone;
            }
        });

        if (count($data) > 0) {
            return $data;
        }
        return null;
        
    }
    public static function calculatePoints($order_amount,$zone_id)
    {
        $zone = Zone::find($zone_id);
        
        if (!$zone) {
            return 0;
        }
        
        $min_purchase_for_point = $zone->min_purchase_for_point;
        
         // Check if the order amount is greater than or equal to the minimum purchase amount for one loyalty point
        if ($order_amount >= $min_purchase_for_point) {
            // Calculate the loyalty points based on the ratio of order amount to the minimum purchase amount for one loyalty point
            $points = floor($order_amount / $min_purchase_for_point);
        } else {
            // Order amount is below the minimum purchase amount, no loyalty points earned
            $points = 0;
        }
    
        return $points;
    }
   public static function createLoyaltyPoints($user_id, $order_amount, $order_id, $zone_id)
    {
        // Fetch the user
        $user = User::find($user_id);
        
        if (!$user) {
            return;
        }
        
        // Calculate the loyalty points based on the order amount
        $points = self::calculatePoints($order_amount, $zone_id);
        
        // return $points;
        
        // Update the user's loyalty points
        $user->loyalty_point += $points;
        $user->save();
        
        // Create a new loyalty points history record
        $loyaltyPointsHistory = new LoyaltyPoints();
        $loyaltyPointsHistory->user_id = $user_id;
        $loyaltyPointsHistory->source = 'order';
        $loyaltyPointsHistory->order_id = $order_id;
        $loyaltyPointsHistory->points = $points;
        $loyaltyPointsHistory->save();
    }
}
