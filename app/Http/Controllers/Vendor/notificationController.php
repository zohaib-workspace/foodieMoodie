<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Models\UserNotification;
use App\Models\User;
use App\Models\DeliveryMan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class notificationController extends Controller
{
    

    public function list(Request $request){
        $id= Helpers::get_restaurant_id();
        // print_r($id);
        // exit;
        $notify = UserNotification::where('vendor_id','=',$id)->get();    
        // print_r($notify);
        // exit;
        return view('vendor-views.notification.index', compact('notify'));
    }
    public function read_status(Request $request){
        $id =$request->id;
        $order_id = $request->order_id;
        // print_r($order_id);
        // print_r($id);
        // echo "hit";
        // exit;
        $noti = UserNotification::findOrFail($id);
        $noti->read_by_vendor = 1;
        $noti->save();
        return redirect()->route('vendor.order.details',['id'=>$noti['data']['order_id']]);
    }
}
