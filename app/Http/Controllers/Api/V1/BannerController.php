<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Campaign;
use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function get_banners(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $zone_ids= json_decode($request->header('zoneId'), true);
        // var_dump($zone_id);
        // exit;
        $banners = BannerLogic::get_banners($zone_ids);
        $campaigns = Campaign::whereHas('restaurants', function($query)use($zone_ids){
            $query->whereIn('zone_id', $zone_ids);
        })->with('restaurants',function($query)use($zone_ids){
            return $query->WithOpen()->whereIn('zone_id', $zone_ids);
        })->running()->active()->get();
        try {
            $responseData = ['campaigns'=>Helpers::basic_campaign_data_formatting($campaigns, true),'banners'=>$banners];
            return _response(1,'success',$responseData, 200);
        } catch (\Exception $e) {
            return _response(0,$e->getMessage(),$responseData, 200);
        }
    }
}
