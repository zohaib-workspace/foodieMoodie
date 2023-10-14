<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Express;
use App\Models\ExpressTracking;
use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class ExpressController extends Controller
{
    public function place_express_order(Request $request)
    {
    
        // return _response(0,translate('messages.error'),$request->lat, 200);
        
        $validator = Validator::make($request->all(), [
            'pickup_name' => 'required',
            'pickup_phone' => 'required',
            'pickup_address' => 'required',
            'dropoff_name' => 'required',
            'dropoff_phone' => 'required',
            'dropoff_address' => 'required',
            'pickup_address_details' => 'required',
            'dropoff_address_details' => 'required',
            'price' => 'required',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'dropoff_lat' => 'required',
            'dropoff_lng' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return _response(0,'error',Helpers::error_processor($validator),403);
        }

        // var_dump($request->lat);
        
        $order = Express::create([
            'pickup_name' => $request->pickup_name,
            'user_id' => $request->user_id,
            'pickup_phone' => $request->pickup_phone,
            'pickup_address' => $request->pickup_address,
            'dropoff_name' => $request->dropoff_name,
            'dropoff_phone' => $request->dropoff_phone,
            'dropoff_address' => $request->dropoff_address,
            'pickup_address_details' => $request->pickup_address_details,
            'dropoff_address_details' => $request->dropoff_address_details,
            'price' => $request->price,
            'pickup_lat' => $request->pickup_lat,
            'pickup_lng' => $request->pickup_lng,
            'dropoff_lat' => $request->dropoff_lat,
            'dropoff_lng' => $request->dropoff_lng,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => "pending"
        ]);
        $order->save();
        
        return _response(1,'success',$order,200);
        
        
        // $banners = BannerLogic::get_banners($zone_ids);
        // $campaigns = Campaign::whereHas('restaurants', function($query)use($zone_ids){
        //     $query->whereIn('zone_id', $zone_ids);
        // })->with('restaurants',function($query)use($zone_ids){
        //     return $query->WithOpen()->whereIn('zone_id', $zone_ids);
        // })->running()->active()->get();
        // try {
        //     $responseData = ['campaigns'=>Helpers::basic_campaign_data_formatting($campaigns, true),'banners'=>$banners];
        //     return _response(1,'success',$responseData, 200);
        // } catch (\Exception $e) {
        //     return _response(0,$e->getMessage(),$responseData, 200);
        // }
    }
    
    public function get_absher_express_list($id)
    {
        // var_dump($zone_id);
        // exit;
        $expresses['data'] = Express::where('user_id', $id)->get();
        
        try {
            return _response(1,'success',$expresses, 200);
        } catch (\Exception $e) {
            return _response(0,$e->getMessage(),$responseData, 200);
        }
        
    }
    
    public function calculate_price(Request $request) {
    $lat1 = $request->input('lat1');
    $lng1 = $request->input('lng1');
    $lat2 = $request->input('lat2');
    $lng2 = $request->input('lng2');
    
    $earthRadius = 6371; // km
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng/2) * sin($dLng/2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earthRadius * $c; // km
    
    $pricePerKm = 2; // Set your own price per km here
    $price = $distance * $pricePerKm;
    
    return _response(1,'success',[
        'distance' => $distance,
        'price' => $price
    ], 200);
    
    // return response()->json([
    //     'distance' => $distance,
    //     'price' => $price
    // ]);
}

}
