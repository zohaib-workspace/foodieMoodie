<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Service;
use App\Models\ExpressCategory;
use App\Models\User;
use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
// use App\Services\FirebaseService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    
    
    public function send_noti($id)
    {
        
        $user = User::find($id);
        // echo 'here';return;
        if($user && $user->cm_firebase_token){
            // Helpers::sendPushNotification('Hello World', 'Test Notification from Server', $user->cm_firebase_token, ['title' => 'Hello']);
            Helpers::send_push_notif_to_device($user->cm_firebase_token, [
                    'title' => 'Hello World',
                    'description' => 'Test Notification from Server',
                    'order_id' => '0000',
                    'image' => '',
                    'type' => 'order_status',
                ]);
            return _response(1,'success sent',[], 200);
        }else{
            
            return _response(0,'unable to send noti',[], 200);
        }
    
    }
    
    public function get_services(Request $request)
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
        $services['services'] = Service::active()->whereIn('zone_id', $zone_ids)->get();
        
        try {
            return _response(1,'success',$services, 200);
        } catch (\Exception $e) {
            return _response(0,$e->getMessage(),$responseData, 200);
        }
    }
    public function get_express_categories(Request $request)
    {
        // var_dump($zone_id);
        // exit;
        $cats['categories'] = ExpressCategory::active()->get();
        
        try {
            return _response(1,'success',$cats, 200);
        } catch (\Exception $e) {
            return _response(0,$e->getMessage(),$responseData, 200);
        }
    }
}
