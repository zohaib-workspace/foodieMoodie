<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\RestaurantLogic;
use App\CentralLogics\GeneralLogic;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\ShiftHistory;
use App\Models\DeliveryMan;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

class ShiftController extends Controller
{
    public function test(){
       ob_start();

        // Perform API logic and return response
        $response = array('message' => 'API response');
        echo json_encode($response);
        
        // Flush output buffer and send response
        ob_end_flush();
        
        // Close connection to the client
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            ob_flush();
            flush();
        }
        
        // Execute additional code with a 5-second delay
        sleep(5);
        echo "Additional code executed";
        ShiftHistory::insert([
            "shift_id"=>-2,
            "rider_id"=>-2,
            "description"=>"Shift has been started",
            "status"=>"Started"
            ]);
    }
    public function get_shifts(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return _response(0,'error',$errors,403);
        }
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        
        $shiftDate = $request->date;
        
        $zone_id= json_decode($request->header('zoneId'), true);
        $shifts = GeneralLogic::getShifts($zone_id, $shiftDate);
        // $shifts = Helpers::restaurant_data_formatting($restaurants['restaurants'], true);
        return _response(1,translate("messages.success"),$shifts,200);
        // return response()->json($restaurants, 200);
    }
    public function get_ended_shifts(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return _response(0,'error',$errors,403);
        }
        
        $validator = Validator::make($request->all(), [
            'rider_id'=> 'required'
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        
        $zone_id= json_decode($request->header('zoneId'), true);
        $shifts = GeneralLogic::getEndedShifts($zone_id, $request->rider_id,$request['limit'], $request['offset']);
        return _response(1,translate("messages.success"),$shifts,200);
        // return response()->json($restaurants, 200);
    }
    public function get_upcoming_shifts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        $dm = DeliveryMan::with(['rating','userinfo'])->where(['auth_token' => $request['token']])->first();
        $shiftDate = $request->date;
        $dmId = $dm->id;
        $shifts = GeneralLogic::getUpcomingShift($shiftDate,$dmId);
        // $shifts = Helpers::restaurant_data_formatting($restaurants['restaurants'], true);
        return _response(1,translate("messages.success"),$shifts,200);
        // return response()->json($restaurants, 200);
    }
     public function get_current_started_shifts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rider_id'=> 'required'
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        
        $riderId = $request->rider_id;
        $shifts = GeneralLogic::getTodayCurrentShift($riderId);
        if($shifts['status'] == '-1'){
            return _response(1,translate("messages.no_current_shift"),[],403);
        }
        // $shifts = Helpers::restaurant_data_formatting($restaurants['restaurants'], true);
        return _response(1,translate("messages.success"),$shifts["data"],200);
        // return response()->json($restaurants, 200);
    }
    public function take_shift(Request $request){
         $validator = Validator::make($request->all(), [
            'rider_id' => 'required',
            'shift_id' => 'required'
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        $shiftId = $request->shift_id;
        $riderId = $request->rider_id;
        // echo date("Y-m-d");
        $shift = GeneralLogic::checkShift($shiftId,$riderId);
        if($shift['status'] == -1){
            $errors = [];
            array_push($errors, ['code' => 'shift', 'message' => translate('messages.aleady_assigned')]);
            return _response(0,translate("messages.failed"),['errors' => $errors],403);
        }
        if($shift['status'] == -2){
            $errors = [];
            array_push($errors, ['code' => 'shift', 'message' => translate('messages.already_assigned_a_shift_in_perticular_time')]);
            return _response(0,translate("messages.failed"),['errors' => $errors],403);
        }
        if($shift['status'] == 1){
            $result = GeneralLogic::assign_shift($shiftId,$riderId);
            if($result){
                return _response(1,translate("messages.success"),[],200);
            }else{
                $errors = [];
                array_push($errors, ['code' => 'shift', 'message' => translate('messages.cannot_assign_shift')]);
                return _response(0,translate("messages.failed"),['errors' => $errors],403);
            }
        }else{
            $errors = [];
            array_push($errors, ['code' => 'shift', 'message' => translate('messages.shift_not_found')]);
            return _response(0,translate("messages.failed"),['errors' => $errors],403);
        }
    }
    
    public function start_shift(Request $request){
        // checkStartShift
         $validator = Validator::make($request->all(), [
            'rider_id' => 'required',
            'shift_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        $riderId = $request->rider_id;
        $shiftId = $request->shift_id;
        $lat = $request->lat;
        $lng = $request->lng;
        $zones = GeneralLogic::getCurrentZone($lat,$lng);
        if(!$zones){
            return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift_zone","message"=>translate("messages.invalid_zone_area")]],403);
        }
        $checkShift = GeneralLogic::checkStartShift($shiftId);
        if($checkShift['status'] == -1){
            return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.no_shift_found")]],403);
        }else if($checkShift['status'] == -2){
            return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.shift_expired")]],403);
        }else if($checkShift['status'] == -3){
            return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.shift_can_be_started_in_30_mints_or_less"),
            "time_left"=>$checkShift['time']]],403);
        }else if($checkShift['status'] == 1){
            $shift = $checkShift['data']; 
            $zoneId = $shift->zone_id;
            $check = false;
            foreach($zones as $zone){
                if($zone['id'] == $zoneId){
                    $check = true;
                    break;
                }
            }
            if(!$check){
                return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift_zone","message"=>translate("messages.service_not_available_in_this_area")]],403);
            }
            $result = GeneralLogic::startShift($shift->id,$riderId);
            if($result == -1){
                return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.you_have_a_shift_already_started_end_that_first")]],403);
            }
            if($result){
                return _response(1,translate("messages.success"),GeneralLogic::getShift($shiftId),200);
            }else{
                return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.cannot_start_shift")]],403);
            }
        }else{
           return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.asign_shift_operation_failed")]],403); 
        }
    }
    
    public function end_shift(Request $request){
        // checkStartShift
         $validator = Validator::make($request->all(), [
            'rider_id' => 'required',
            'shift_id' => 'required',
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        $riderId = $request->rider_id;
        $shiftId = $request->shift_id;
        $shift = GeneralLogic::getShift($shiftId);
        if($shift){
            if($shift->status == "Ended"){
                return _response(1,translate("messages.shift_is_already_ended"),[],200); 
            }
            $date = GeneralLogic::getZoneDate($shift->zone_id);
            $response = Shift::where("id",$shiftId)->update([
                "status"=>"Ended",
                "shift_ended_at"=>$date,
                ]);

                if($response){
                    ShiftHistory::insert([
                        "shift_id"=>$shift->id,
                        "rider_id"=>$riderId,
                        "status"=>"Ended",
                        "created_at"=>$date,
                        "description"=>"Shift ended by rider"
                        ]);
                    return _response(1,translate("messages.shift_ended_successfully"),[],200); 

                }else{
                    return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.cannot_end_shift_try_again")]],403); 
                }
        }else{
           return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.invalid_shift")]],403); 
        }
    }
    
    public function pause_shift(Request $request){
        // checkStartShift
         $validator = Validator::make($request->all(), [
            'rider_id' => 'required',
            'shift_id' => 'required',
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        $riderId = $request->rider_id;
        $shiftId = $request->shift_id;
        $shift = GeneralLogic::getShift($shiftId);
        if($shift){
            if($shift->status == "Paused"){
                return _response(1,translate("messages.shift_is_already_paused"),[],200); 
            }
            $date = GeneralLogic::getZoneDate($shift->zone_id);
            $response = Shift::where("id",$shiftId)->update([
                "status"=>"Paused"
                ]);

                if($response){
                  
                    return _response(1,translate("messages.shift_paused_successfully"),[],200); 

                }else{
                    return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.cannot_pause_shift_try_again")]],403); 
                }
        }else{
           return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.invalid_shift")]],403); 
        }
    }
    
    public function resume_shift(Request $request){
        // checkStartShift
         $validator = Validator::make($request->all(), [
            'rider_id' => 'required',
            'shift_id' => 'required',
        ]);
        if ($validator->fails()) {
            return _response(0,translate("messages.failed"),['errors' => Helpers::error_processor($validator)],403);
        }
        $riderId = $request->rider_id;
        $shiftId = $request->shift_id;
        $shift = GeneralLogic::getShift($shiftId);
        if($shift){
            if($shift->status == "Started"){
                return _response(1,translate("messages.shift_is_already_started"),[],200); 
            }
            $date = GeneralLogic::getZoneDate($shift->zone_id);
            $response = Shift::where("id",$shiftId)->update([
                "status"=>"Started"
                ]);

                if($response){
                  
                    return _response(1,translate("messages.shift_started_successfully"),[],200); 

                }else{
                    return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.cannot_start_shift_try_again")]],403); 
                }
        }else{
           return _response(0,translate("messages.failed"),['errors' => ["code"=>"shift","message"=>translate("messages.invalid_shift")]],403); 
        }
    }
    
   

}
