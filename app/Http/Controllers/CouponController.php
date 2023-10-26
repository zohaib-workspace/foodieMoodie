<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Restaurant,Coupon};
class CouponController extends Controller
{
    
    public function index()
    {
        $zone_id=session()->get('zone_id');
        if (!$zone_id) {
         return redirect()->route('user.home')->with('warning','Please your address.');
        }
        
        // $zone_id= json_decode($s_zone_id, true);
        $data = [];
        // try {
            $paginator = Coupon::active()->whereDate('expire_date', '>=', date('Y-m-d'))->whereDate('start_date', '<=', date('Y-m-d'))
            ->paginate($request['limit']??10, ['*'], 'page', $request['offset']??0);;
            $coupons = $paginator->items();
            foreach($coupons as $key=>$coupon)
            {
                if($coupon->coupon_type == 'restaurant_wise')
                {
                    $temp = Restaurant::active()->whereIn('zone_id', $zone_id)->whereIn('id', json_decode($coupon->data, true))->first();
                    if($temp)
                    {
                        $coupon->data = $temp->name;
                        $data[] = $coupon;
                    }
                }
                else if($coupon->coupon_type == 'zone_wise')
                {
                    foreach($zone_id as $z_id) {
                        if(in_array($z_id, json_decode($coupon->data,true)))
                        {
                            $data[] = $coupon;
                            break;
                        }
                    }

                }
                else{
                    $data[] = $coupon;
                }
            }
            
            $coupons = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit']??10,
            'offset' => $request['offset']??0,
            'coupons' => $data
        ];
        return view('home.coupon.index',$coupons);
    }
}
