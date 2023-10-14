<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\DeliveryMan;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class NotificationController extends Controller
{
    public function test (Request $request){
        echo 'testing';
        exit;
        Helpers::sendPushNotification("Test Title","Test Message","cFueE9MMQ9-d0jG4P2MexP:APA91bGex25U3GAn88YtRrRuUsE3SFq4YCLVvQxzIHBN9Wu3oh6YsqdmiatIbGEpjqWlovYc9sCT6VjL48gj8isgtbyBDhDciE7DJSsAhm8cwmobN-Baf-vQlnLxba6PC977vF-8xFP_",["msg"=>"Important"]);
    }
    public function send_chat_noti(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'order_id' => 'required',
            'sender_role' => 'required|in:customer,rider,vendor',
            'receiver_role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        
        if($request['sender_role'] == 'customer'){
        $sender = User::find($request['sender_id']);
        }else if($request['sender_role'] == 'rider'){
        $sender = DeliveryMan::find($request['sender_id']);
        }else if($request['sender_role'] == 'vendor'){
        $sender = Restaurant::find($request['sender_id']);
        }
        
        $fcm_token = '';
        
        if($request['receiver_role'] == 'customer'){
            
        $receiver = User::find($request['receiver_id']);
        if($receiver)
        $fcm_token = $receiver->cm_firebase_token;
        
        }else if($request['receiver_role'] == 'rider'){
        $receiver = DeliveryMan::find($request['receiver_id']);
        if($receiver)
        $fcm_token = $receiver->fcm_token;
        
        }else if($request['receiver_role'] == 'vendor'){
        $receiver = Restaurant::find($request['receiver_id']);
        }
        
    
        
        // echo 'here';return;
        if($receiver && !empty($fcm_token)){
            // Helpers::sendPushNotification('Hello World', 'Test Notification from Server', $user->cm_firebase_token, ['title' => 'Hello']);
            
            $sender_postfix = '';
            if($sender){
                if($request['sender_role'] == 'vendor'){
                $sender_postfix = ' from ' . $sender->name??$request['sender_role'];
                }else{
                $sender_postfix = ' from ' . $sender->f_name??$sender->l_name??$request['sender_role'];
                }
            }
            
                
                $data = [
                    'title' => 'Chat message' . $sender_postfix,
                    'description' => 'You have a message for order # ' . $request['order_id'],
                    'order_id' => $request['order_id'],
                    'image' => '',
                    'type' => 'chat',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
                
                $notiData = [
                    'data' => json_encode($data),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                if($request['receiver_role'] == 'customer'){
                    $notiData['user_id'] = $request['receiver_id'];
                    }
                    else if($request['receiver_role'] == 'rider'){
                    $notiData['delivery_man_id'] = $request['receiver_id'];
                    }
                    else if($request['receiver_role'] == 'vendor'){
                    $notiData['vendor_id'] = $request['receiver_id'];
                    }
                
                DB::table('user_notifications')->insert($notiData);
            return _response(1,'notification sent successfully',[], 200);
        }else{
            
            return _response(0,'unable to send notification',[$receiver], 200);
        }
    
    }
    
    
    public function get_notifications(Request $request){


        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => 'Zone id is required!']);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $zone_id= json_decode($request->header('zoneId'), true);
        
        try {
            // $notifications = Notification::active()->where('tergat', 'customer')->where(function($q)use($zone_id){
            //     $q->whereNull('zone_id')->orWhereIn('zone_id', $zone_id);
            // })->where('created_at', '>=', \Carbon\Carbon::today()->subDays(15))->orWhere('updated_at', '>=', \Carbon\Carbon::today()->subDays(15))->get();
            // $notifications->append('data');
            
            $limit = $request['limit'] ?? 10;
            $offset = $request['offset'] ?? 0;
            
            $dm = null;
            if($request->token != null){
            $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
            }
            // var_dump($dm);exit;

            $user_notifications = UserNotification::
                with(['user' => function ($q) { return $q->select('id', 'image');}])
                ->with(['delivery_man' => function ($q) { return $q->select('id', 'image');}])
                ->with(['vendor' => function ($q) { return $q->select('id', 'image');}])
                ->when($dm != null, function ($q) use ($dm){
                    return $q->where('delivery_man_id', $dm->id);
                })
                ->when($dm == null, function($q) use ($request){
                    return $q->where('user_id', $request->user()->id);
                })
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays(15))->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);
            
            
            // echo $user_notifications->items();
            // $notifications =  $notifications->merge($user_notifications);
        $data =  [
            'total_size' => $user_notifications->total(),
            'limit' => $limit,
            'offset' => $offset,
            'notifications' => $user_notifications->items()
        ];
        // echo $data;
            return _response(1,translate('messages.success'),$data,200);
        } catch (\Exception $e) {
            print_r(['Notification api issue_____',$e->getMessage()]);
            return response()->json($e, 300);
        }
    }
    
    public function read_user_notification($id){

        try {
        

        $notification = UserNotification::find($id);
        if($notification){
           $notification->read_by_user = 1;
    
        if($notification->save()){
            return _response(1,translate('messages.success'),$notification,200);
        }
    }
    return _response(0,translate('messages.unable_to_fulfile_request'),$notification,300);
        } catch (\Exception $e) {
            info(['Notification api issue_____',$e->getMessage()]);
            return response()->json([], 200);
        }
    }
    public function read_rider_notification($id){

        try {
        

        $notification = UserNotification::find($id);
        if($notification){
           $notification->read_by_rider = 1;
    
        if($notification->save()){
            return _response(1,translate('messages.success'),$notification,200);
        }
    }
    return _response(0,translate('messages.unable_to_fulfile_request'),$notification,300);
        } catch (\Exception $e) {
            info(['Notification api issue_____',$e->getMessage()]);
            return response()->json([], 200);
        }
    }

}
