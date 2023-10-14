<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\CouponLogic;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function list(Request $request)
    {
        
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            return response()->json([
                'status_code' => 0,
                'message' => translate('messages.zone_id_required'),
            ], 403);
        }
        
        $zone_id= json_decode($request->header('zoneId'), true);
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
            
            $resp = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit']??10,
            'offset' => $request['offset']??0,
            'data' => $data
        ];

            return _response(1, 'Couponse fetched successfully', $resp, 200);
        // } catch (\Exception $e) {
        //     return response()->json(['errors' => $e], 403);
        // }
    }

    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'restaurant_id' => 'required',
        ]);

        if ($validator->errors()->count()>0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        try {
            $coupon = Coupon::active()->where(['code' => $request['code']])->first();
            if (isset($coupon)) {
                $staus = CouponLogic::is_valide($coupon, $request->user()->id ,$request['restaurant_id']);

                switch ($staus) {
                case 200:
                    return _response(1, 'Code fetched successfully', ['code' => $coupon], 200);
                case 406:
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.coupon_usage_limit_over')]
                        ]
                    ], 406);
                case 407:
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.coupon_expire')]
                        ]
                    ], 407);
                default:
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.not_found')]
                        ]
                    ], 404);
                }
            } else {
                return response()->json([
                    'errors' => [
                        ['code' => 'coupon', 'message' => translate('messages.not_found')]
                    ]
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }
}
