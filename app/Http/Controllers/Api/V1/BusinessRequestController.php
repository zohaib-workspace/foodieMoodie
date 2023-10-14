<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BusinessSlider;
use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
use App\Models\User;
use App\Models\BusinessRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessRequestController extends Controller
{
    public function submitRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            
        ]);

        if ($validator->fails()) {
            return _response(0,"Failed to submit your request.",['errors' => Helpers::error_processor($validator)]);
        }
        $id = $request->user_id; 
        $business_request = BusinessRequest::Where("user_id",$id)->get();
        
        if(count($business_request)>0){
            
            foreach($business_request as $r){
                $status = $r->status;
                if($status == 'approved'){
                    return _response(1,"Success",["message"=>"Your request has been accepted to become partner."]);
                }else if($status == 'pending'){
                    return _response(1,"Success",["message"=>"You request is pending to review. Please wait."]);
                }else {
                    return _response(1,"Success",["message"=>"You are unable to become our business partner."]);
                }
            }
            
        }else{
            $b = new BusinessRequest();
            $b->user_id = $id;
            $b->save();
            return _response(1,"Success",["message"=>"Your request has been submitted successfully."]);
        }
        
    }
}
