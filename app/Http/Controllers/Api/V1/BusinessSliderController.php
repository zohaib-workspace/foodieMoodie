<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BusinessSlider;
use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessSliderController extends Controller
{
    public function get_banners(Request $request)
    {
     
     $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            // return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            return _response(0,translate('messages.error'),['errors' => Helpers::error_processor($validator)], 403);
        }
       
        $banners["slider"] = BusinessSlider::active()->where("business_id", $request->id)->get();
    
        try {
            return _response(1,'success',$banners, 200);
        } catch (\Exception $e) {
            return _response(0,$e->getMessage(),$responseData, 200);
        }
    }
}
