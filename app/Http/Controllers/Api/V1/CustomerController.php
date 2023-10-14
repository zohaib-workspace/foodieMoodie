<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Food;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Zone;
use Grimzy\LaravelMysqlSpatial\Types\Point;
class CustomerController extends Controller
{
    public function test(){
        $data["message"] = "Message Body";
        $data["title"] = "Push Title";
        $data["description"] = "Push Description";
        $data["image"] = "imge.png";
        $data["order_id"] = 100004;
        $data["type"] = "user";
        Helpers::send_push_notif_to_device("eTKy5yjZQIegeIbZwo4esA:APA91bGSCzWSCR9k9Don3rHuOdnvk_O9ZOYw8VBC_xS6gGL0rMNcGWdXLFfit5l69D4_77sHze5hNls6seazNCsRoPXcG7Iq9hne93PqHQNbr7ns_OFhRddGlIqe1ntIlsV_UZK3GgV7",$data);
        //Helpers::sendPushNotification("Title","Message Body","eTKy5yjZQIegeIbZwo4esA:APA91bGSCzWSCR9k9Don3rHuOdnvk_O9ZOYw8VBC_xS6gGL0rMNcGWdXLFfit5l69D4_77sHze5hNls6seazNCsRoPXcG7Iq9hne93PqHQNbr7ns_OFhRddGlIqe1ntIlsV_UZK3GgV7",["name"=>"MySelf"]);
        echo 'testing';
        exit;
    }
    
    public function address_list(Request $request)
    {
        $limit = $request['limit']??10;
        $offset = $request['offset']??1;

        $addresses = CustomerAddress::where('user_id', $request->user()->id)->latest()->paginate($limit, ['*'], 'page', $offset);

        $data =  [
            'total_size' => $addresses->total(),
            'limit' => $limit,
            'offset' => $offset,
            'addresses' => Helpers::address_data_formatting($addresses->items())
        ];
        return response()->json($data, 200);
    }

    public function add_new_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $point = new Point($request->latitude,$request->longitude);
        $zone = Zone::contains('coordinates', $point)->get(['id']);
        if(count($zone) == 0)
        {
            $errors = [];
            array_push($errors, ['code' => 'coordinates', 'message' => translate('messages.service_not_available_in_this_area')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $address = [
            'user_id' => $request->user()->id,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'floor' => $request->floor,
            'road' => $request->road,
            'house' => $request->house,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'zone_id' => $zone[0]->id,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->insert($address);
        return response()->json(['message' => translate('messages.successfully_added'),'zone_ids'=>array_column($zone->toArray(), 'id')], 200);
    }

    public function update_address(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $point = new Point($request->latitude,$request->longitude);
        $zone = Zone::contains('coordinates', $point)->first();
        if(!$zone)
        {
            $errors = [];
            array_push($errors, ['code' => 'coordinates', 'message' => translate('messages.service_not_available_in_this_area')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $address = [
            'user_id' => $request->user()->id,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'floor' => $request->floor,
            'road' => $request->road,
            'house' => $request->house,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'zone_id' => $zone->id,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->where('id',$id)->update($address);
        return response()->json(['message' => translate('messages.updated_successfully'),'zone_id'=>$zone->id], 200);
    }

    public function delete_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (DB::table('customer_addresses')->where(['id' => $request['address_id'], 'user_id' => $request->user()->id])->first()) {
            DB::table('customer_addresses')->where(['id' => $request['address_id'], 'user_id' => $request->user()->id])->delete();
            return response()->json(['message' => translate('messages.successfully_removed')], 200);
        }
        return response()->json(['message' => translate('messages.not_found')], 404);
    }

    public function get_order_list(Request $request)
    {
        $orders = Order::with('restaurant')->where(['user_id' => $request->user()->id])->get();
        return response()->json($orders, 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = OrderDetail::where(['order_id' => $request['order_id']])->get();
        foreach ($details as $det) {
            $det['product_details'] = json_decode($det['product_details'], true);
        }

        return response()->json($details, 200);
    }

    public function info(Request $request)
    {
        
        $data = $request->user();
        $data['userinfo'] = $data->userinfo;
        $data['order_count'] =(integer)$request->user()->orders->count();
        $data['member_since_days'] =(integer)$request->user()->created_at->diffInDays();
        unset($data['orders']);
        // return response()->json($data, 200);
        return _response(1,'success',$data,200);
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'email' => 'required|unique:users,email,'.$request->user()->id,
        ], [
            'f_name.required' => 'First name is required!',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        
        $p_user = User::where(['email'=>$request->email])->first();
        $user_update = [];
        
        //echo $p_user->email;
    
        
        if ($request->has('image')) {
            $imageName = Helpers::update('profile/', $request->user()->image, 'png', $request->file('image'));
            $user_update["image"]=$imageName;
        }
        
        if ($request->has('phone')) {
            $phone = $request['phone'];
            $phone_verified = '0';
            $user_update["phone"]=$phone;
            $user_update["is_phone_verified"] = '0';
        } 

        if ($request['password'] != null && strlen($request['password']) > 5) {
            if($request['old_password'] == null){
                return _response(0,'Please provide previous password to update',"", 200);
            }
            $password = bcrypt($request['password']);
            $user_update["password"] = $password;
        
        }
        
        if($request->has('f_name')){
            $user_update['f_name'] = $request->f_name;
        }
        
        if($request->has('l_name')){
            $user_update['l_name'] = $request->l_name;
        }
        
        $user_update["updated_at"] = now();
        
        User::where(['id' => $p_user->id])->update($user_update);

        $user["data"] =  User::where(['email'=>$request->email])->first();

        return _response(1,'Successfully updated',$user, 200);

        
    }
    public function update_interest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'interest' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $userDetails = [
            'interest' => json_encode($request->interest),
        ];

        User::where(['id' => $request->user()->id])->update($userDetails);

        return response()->json(['message' => translate('messages.interest_updated_successfully')], 200);
    }

    public function update_cm_firebase_token(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return _response(0,translate('messages.invalid_data'),"", 403);
        }

        DB::table('users')->where('id',$request->user()->id)->update([
            'cm_firebase_token'=>$request['cm_firebase_token']
        ]);

        return _response(1,translate('messages.updated_successfully'),"", 200);
    }

    public function get_suggested_food(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => 'Zone id is required!']);
            return response()->json([
                'errors' => $errors
            ], 403);
        }


        $zone_id= json_decode($request->header('zoneId'), true);

        $interest = $request->user()->interest;
        $interest = isset($interest) ? json_decode($interest):null;
        // return response()->json($interest, 200);

        $products =  Food::active()->whereHas('restaurant', function($q)use($zone_id){
            $q->whereIn('zone_id', $zone_id);
        })
        ->when(isset($interest), function($q)use($interest){
            return $q->whereIn('category_id',$interest);
        })
        ->when($interest == null, function($q){
            return $q->popular();
        })->limit(5)->get();
        $products = Helpers::product_data_formatting($products, true, false, app()->getLocale());
        return response()->json($products, 200);
    }

    public function update_zone(Request $request)
    {
        if (!$request->hasHeader('zoneId') && is_numeric($request->header('zoneId'))) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $customer = $request->user();
        $customer->zone_id = (integer)json_decode($request->header('zoneId'), true)[0];
        $customer->save();
        return response()->json([], 200);
    }

    public function remove_account(Request $request)
    {
        $user = $request->user();

        if(Order::where('user_id', $user->id)->whereIn('order_status', ['pending','accepted','confirmed','processing','handover','picked_up'])->count())
        {
            return response()->json(['errors'=>[['code'=>'on-going', 'message'=>translate('messages.user_account_delete_warning')]]],203);
        }
        $request->user()->token()->revoke();
        if($user->userinfo){
            $user->userinfo->delete();
        }
        $user->delete();
        return response()->json([]);
    }
}
