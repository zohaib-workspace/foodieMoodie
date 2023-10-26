<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\CentralLogics\Helpers;
use App\CentralLogics\CouponLogic;
use App\CentralLogics\CustomerLogic;
use App\CentralLogics\GeneralLogic;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\OrderDetail;
use App\Models\Food;
use App\Models\Restaurant;
use App\Models\ItemCampaign;
use App\Models\DealOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Zone;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\OrderReview;
use App\Models\DMReview;
use App\Models\DeliveryMan;
use App\Models\DeliveryManWallet;
use App\Models\OrderEarning;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function order_shipping_charges(Request $request)
    {
        // return 'hello from order shipping charges method';
        $validator = Validator::make($request->all(), [
            'distance' => 'required',
            'business_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $per_km_shipping_charge = (float)BusinessSetting::where(['key' => 'per_km_shipping_charge'])->first()->value;
        $minimum_shipping_charge = (float)BusinessSetting::where(['key' => 'minimum_shipping_charge'])->first()->value;

        $restaurant = Restaurant::where('id', $request->business_id)->first();

        if ($restaurant) {

            $zone = Zone::where('id', $restaurant->zone_id)->first();
            if (!$zone) {

                return _response(0, translate('messages.unable_to_find_zone'), $errors, 403);
            }
            if ($zone->per_km_shipping_charge && $zone->minimum_shipping_charge) {
                $per_km_shipping_charge = $zone->per_km_shipping_charge;
                $minimum_shipping_charge = $zone->minimum_shipping_charge;
            }

            if ($restaurant->self_delivery_system) {
                if (!$restaurant->free_delivery) {
                    $per_km_shipping_charge = $restaurant->per_km_shipping_charge;
                    $minimum_shipping_charge = $restaurant->minimum_shipping_charge;
                } else {
                    $per_km_shipping_charge = 0;
                    $minimum_shipping_charge = 0;
                }
            }
        }

        $original_delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge : $minimum_shipping_charge;

        return _response(1, translate('messages.delivery_charges_are_fetched'), [
            'charges' => round($original_delivery_charge, config('round_up_to_digit')) ?? 0
        ], 200);

        //  else{
        //      return _response(1,translate('messages.delivery_charges_are_not_fetched'),[
        //         'charges' => null
        //     ],200);
        //  }

    }


    public function orderReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'user_id' => 'required',
            'order_rating' => 'required|numeric|max:5',
            // 'order_comment' => 'required',
            // 'order_liked_category' => 'required',
            'restaurant_id' => 'required',

            // 'delivery_man_id' => 'required',
            // 'dm_comment' => 'required',
            // 'dm_rating' => 'required|numeric|max:5',
            // 'dm_liked_category' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        try {

            $order = \App\Models\Order::find($request->order_id);
            if ($order->order_status != 'delivered') {
                throw new \Exception('Order is not delivered yet.');
            }
            $multi_review = OrderReview::where(['user_id' => $request->user_id, 'order_id' => $request->order_id])->first();
            if (isset($multi_review)) {
                return _response(0, translate('messages.order_review_already_submitted'), 403);
            }
            $orderReview = new OrderReview;
            $orderReview->order_id = $request->order_id;
            $orderReview->user_id = $request->user_id;
            $orderReview->restaurant_id = $request->restaurant_id;
            $orderReview->rating = $request->order_rating;
            $orderReview->comment = $request->order_comment ?? '';
            $orderReview->liked_category = $request->order_liked_category ?? '';
            $res = $orderReview->save();

            if ($order->orderType == 'take_away') {
                if ($review->save()) {
                    return _response(1, 'Feedback submitted successfully', 200);
                } else {
                    return _response(0, 'Unable to save feedback', 300);
                }
            }


            $dm = DeliveryMan::find($request->delivery_man_id);
            if (isset($dm) == false) {
                $validator->errors()->add('delivery_man_id', translate('messages.not_found'));
            }

            if ($validator->errors()->count() > 0) {

                return _response(0, ['errors' => Helpers::error_processor($validator)], 403);
            }

            // $multi_review = DMReview::where(['delivery_man_id' => $request->delivery_man_id, 'user_id' => $request->user()->id, 'order_id'=>$request->order_id])->first();
            $multi_review = DMReview::where(['delivery_man_id' => $request->delivery_man_id, 'user_id' => $request->user_id, 'order_id' => $request->order_id])->first();
            if (isset($multi_review)) {
                return _response(0, translate('messages.already_submitted'), 403);
            }


            $image_array = [];
            if (!empty($request->file('attachment'))) {
                foreach ($request->file('attachment') as $image) {
                    if ($image != null) {
                        if (!Storage::disk('public')->exists('review')) {
                            Storage::disk('public')->makeDirectory('review');
                        }
                        array_push($image_array, Storage::disk('public')->put('review', $image));
                    }
                }
            }

            $review = new DMReview();
            // $review->user_id = $request->user()->id;
            $review->user_id = $request->user_id;
            $review->delivery_man_id = $request->delivery_man_id;
            $review->order_id = $request->order_id;
            $review->comment = $request->dm_comment ?? '';
            $review->rating = $request->dm_rating;
            $review->liked_category = $request->dm_liked_category ?? '';
            // $review->attachment = json_encode($image_array);

            if ($review->save()) {
                return _response(1, 'Feedback submitted successfully', 200);
            } else {
                return _response(0, 'Unable to save feedback', 300);
            }
        } catch (\Exception $e) {

            return _response(0, $e->getMessage(), 200);
        }
    }
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $order = Order::with(['restaurant', 'delivery_man.rating'])->withCount(['details', 'deals' => function ($q) {
            return $q->with('deal_data');
        }])->where(['id' => $request['order_id'], 'user_id' => $request->user()->id])->Notpos()->first();
        if ($order) {
            $order['restaurant'] = $order['restaurant'] ? Helpers::restaurant_data_formatting($order['restaurant']) : $order['restaurant'];
            $order['delivery_address'] = $order['delivery_address'] ? json_decode($order['delivery_address']) : $order['delivery_address'];
            $order['delivery_man'] = $order['delivery_man'] ? Helpers::deliverymen_data_formatting([$order['delivery_man']]) : $order['delivery_man'];
            unset($order['details']);
        } else {
            return response()->json([
                'errors' => [
                    ['code' => 'schedule_at', 'message' => translate('messages.not_found')]
                ]
            ], 404);
        }
        return response()->json($order, 200);
    }

    public function place_order(Request $request)
    {
        // return Auth()->user();
        // return $request;
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'payment_method' => 'required|in:cash_on_delivery,digital_payment,wallet',
            'order_type' => 'required|in:take_away,delivery,dine_in',
            'restaurant_id' => 'required',
            'distance' => 'required_if:order_type,delivery',
            'address' => 'required_if:order_type,delivery',
            'longitude' => 'required_if:order_type,delivery',
            'latitude' => 'required_if:order_type,delivery',
            'dm_tips' => 'nullable|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        // return $request;

        if ($request->payment_method == 'wallet' && Helpers::get_business_settings('wallet_status', false) != 1) {
            return _response(0, translate('messages.customer_wallet_disable_warning'), [
                'errors' => [
                    ['code' => 'payment_method', 'message' => translate('messages.customer_wallet_disable_warning')]
                ]
            ], 203);
        }
        $coupon = null;
        $delivery_charge = null;
        $free_delivery_by = null;
        $schedule_at = $request->schedule_at ? \Carbon\Carbon::parse($request->schedule_at) : now();
        if ($request->schedule_at && $schedule_at < now()) {
            return _response(0, translate('messages.you_can_not_schedule_a_order_in_past'), [
                'errors' => [
                    ['code' => 'schedule', 'message' => translate('messages.you_can_not_schedule_a_order_in_past')]
                ]
            ], 406);
        }
        $restaurant = Restaurant::with('discount')->selectRaw('*, IF(((select count(*) from `restaurant_schedule` where `restaurants`.`id` = `restaurant_schedule`.`restaurant_id` and `restaurant_schedule`.`day` = ' . $schedule_at->format('w') . ' and `restaurant_schedule`.`opening_time` < "' . $schedule_at->format('H:i:s') . '" and `restaurant_schedule`.`closing_time` >"' . $schedule_at->format('H:i:s') . '") > 0), true, false) as open')->where('id', $request->restaurant_id)->first();

        if (!$restaurant) {
            return _response(0, translate('messages.restaurant_not_found'), [
                'errors' => [
                    ['code' => 'restaurant', 'message' => translate('messages.restaurant_not_found')]
                ]
            ], 404);
        }

        if ($request->schedule_at && !$restaurant->schedule_order) {
            return _response(0, translate('messages.schedule_order_not_available'), [
                'errors' => [
                    ['code' => 'schedule', 'message' => translate('messages.schedule_order_not_available')]
                ]
            ], 406);
        }
        // comment just for order place
        // if ($restaurant->open == false) {
        //     return _response(0, translate('messages.restaurant_is_closed_at_order_time'), [
        //         'errors' => [
        //             ['code' => 'open', 'message' => translate('messages.restaurant_is_closed_at_order_time')]
        //         ]
        //     ], 406);
        // }

        if ($request['coupon_code']) {
            $coupon = Coupon::active()->where(['code' => $request['coupon_code']])->first();
            if (isset($coupon)) {
                $staus = CouponLogic::is_valide($coupon, $request->user()->id, $request['restaurant_id']);
                if ($staus == 407) {
                    return _response(0, translate('messages.coupon_expire'), [
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.coupon_expire')]
                        ]
                    ], 407);
                } else if ($staus == 406) {
                    return _response(0, translate('messages.coupon_usage_limit_over'), [
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.coupon_usage_limit_over')]
                        ]
                    ], 406);
                } else if ($staus == 404) {
                    return _response(0, translate('messages.not_found'), [
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.not_found')]
                        ]
                    ], 404);
                }
                if ($coupon->coupon_type == 'free_delivery') {
                    $delivery_charge = 0;
                    $coupon = null;
                    $free_delivery_by = 'admin';
                }
            } else {
                return _response(0, translate('messages.not_found'), [
                    'errors' => [
                        ['code' => 'coupon', 'message' => translate('messages.not_found')]
                    ]
                ], 401);
            }
        }
        $per_km_shipping_charge = (float)BusinessSetting::where(['key' => 'per_km_shipping_charge'])->first()->value;
        $minimum_shipping_charge = (float)BusinessSetting::where(['key' => 'minimum_shipping_charge'])->first()->value;

        if ($request->latitude && $request->longitude) {
            $point = new Point($request->latitude, $request->longitude);
            $zone = Zone::where('id', $restaurant->zone_id)->contains('coordinates', $point)->first();
            if (!$zone) {
                $errors = [];
                array_push($errors, ['code' => 'coordinates', 'message' => translate('messages.failed')]);
                return _response(0, translate('messages.insufficient_balance'), $errors, 403);
            }
            if ($zone->per_km_shipping_charge && $zone->minimum_shipping_charge) {
                $per_km_shipping_charge = $zone->per_km_shipping_charge;
                $minimum_shipping_charge = $zone->minimum_shipping_charge;
            }
        }

        if ($request['order_type'] != 'take_away' && !$restaurant->free_delivery && !isset($delivery_charge)) {
            if ($restaurant->self_delivery_system) {
                $per_km_shipping_charge = $restaurant->per_km_shipping_charge;
                $minimum_shipping_charge = $restaurant->minimum_shipping_charge;
            }
        }

        $original_delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge : $minimum_shipping_charge;

        if ($request['order_type'] == 'take_away') {
            $per_km_shipping_charge = 0;
            $minimum_shipping_charge = 0;
        }
        if (!isset($delivery_charge)) {
            $delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge : $minimum_shipping_charge;
        }


        $address = [
            'contact_person_name' => $request->contact_person_name ? $request->contact_person_name : $request->user()->f_name . ' ' . $request->user()->f_name,
            // 'contact_person_name' => $request->contact_person_name?$request->contact_person_name:$request->user()->f_name.' '.$request->user()->f_name,
            'contact_person_number' => $request->contact_person_number ? $request->contact_person_number : $request->user()->phone,
            'address_type' => $request->address_type ? $request->address_type : 'Delivery',
            'address' => $request->address,
            'floor' => $request->floor,
            'road' => $request->road,
            'house' => $request->house,
            'longitude' => (string)$request->longitude,
            'latitude' => (string)$request->latitude,
        ];

        $total_addon_price = 0;
        $product_price = 0;
        $restaurant_discount_amount = 0;

        $order_details = [];
        $order = new Order();
        $order->id = 100000 + Order::all()->count() + 1;
        if (Order::find($order->id)) {
            $order->id = Order::orderBy('id', 'desc')->first()->id + 1;
        }

        $order->user_id = $request->user()->id;
        $order->order_amount = $request['order_amount'];

        $order->payment_status = $request['payment_method'] == 'wallet' ? 'paid' : 'unpaid';
        $order->order_status = $request['payment_method'] == 'digital_payment' ? 'failed' : ($request->payment_method == 'wallet' ? 'confirmed' : 'pending');
        $order->coupon_code = $request['coupon_code'];
        $order->payment_method = $request->payment_method;
        $order->transaction_reference = null;
        $order->order_note = $request['order_note'];
        $order->order_type = $request['order_type'];
        $order->restaurant_id = $request['restaurant_id'];
        $order->delivery_charge = round($delivery_charge, config('round_up_to_digit')) ?? 0;
        $order->original_delivery_charge = round($original_delivery_charge, config('round_up_to_digit'));
        $order->delivery_address = json_encode($address);
        $order->schedule_at = $schedule_at;
        $order->scheduled = $request->schedule_at ? 1 : 0;
        $order->otp = rand(1000, 9999);
        $order->zone_id = $restaurant->zone_id;
        $dm_tips_manage_status = BusinessSetting::where('key', 'dm_tips_status')->first()->value;
        if ($dm_tips_manage_status == 1) {
            $order->dm_tips = $request->dm_tips ?? 0;
        } else {
            $order->dm_tips = 0;
        }
        $order->pending = now();
        $order->confirmed = $request->payment_method == 'wallet' ? now() : null;
        $order->created_at = now();
        $order->updated_at = now();
        $flag = is_string($request['cart']);

        if ($flag) {
            $cart_list = json_decode($request['cart'], true);
        } else {
            $cart_list = $request['cart'];
        }
        if($cart_list)
        {

            foreach ($cart_list as $c) {
                // $c = get_object_vars($c);
                // var_dump(isset($temp['item_campaign_id']));exit;
                // if(isset())
                if (isset($c['item_campaign_id']) && $c['item_campaign_id'] != null) {
                    $product = ItemCampaign::active()->find($c['item_campaign_id']);
                    if ($product) {
                        if (count(json_decode($product['variations'], true)) > 0) {
                            $price = Helpers::variation_price($product, json_encode($c['variation']));
                        } else {
                            $price = $product['price'];
                        }
                        $product->tax = $restaurant->tax;
                        $product = Helpers::product_data_formatting($product, false, false, app()->getLocale());
                        $addon_data = Helpers::calculate_addon_price(\App\Models\AddOn::whereIn('id', $c['add_on_ids'])->get(), $c['add_on_qtys']);
                        $or_d = [
                            'food_id' => null,
                            'item_campaign_id' => $c['item_campaign_id'],
                            'food_details' => json_encode($product),
                            'quantity' => $c['quantity'],
                            'price' => $price,
                            'tax_amount' => Helpers::tax_calculate($product, $price),
                            'discount_on_food' => Helpers::product_discount_calculate($product, $price, $restaurant),
                            'discount_type' => 'discount_on_product',
                            'variant' => json_encode($c['variant']),
                            'variation' => json_encode($c['variation']),
                            'add_ons' => json_encode($addon_data['addons']),
                            'total_add_on_price' => $addon_data['total_add_on_price'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $order_details[] = $or_d;
                        $total_addon_price += $or_d['total_add_on_price'];
                        $product_price += $price * $or_d['quantity'];
                        $restaurant_discount_amount += $or_d['discount_on_food'] * $or_d['quantity'];
                    } else {
                        return _response(0, translate('messages.product_unavailable_warning'), [
                            'errors' => [
                                ['code' => 'campaign', 'message' => translate('messages.product_unavailable_warning')]
                            ]
                        ], 401);
                    }
                } else {
                    $product = Food::active()->find($c['food_id']);
                    if ($product) {
                        if (count(json_decode($product['variations'], true)) > 0) {
                            $price = Helpers::variation_price($product, json_encode($c['variation']));
                        } else {
                            $price = $product['price'];
                        }
    
                        $product->tax = $restaurant->tax;
                        $product = Helpers::product_data_formatting($product, false, false, app()->getLocale());
                        $addon_data = Helpers::calculate_addon_price(\App\Models\AddOn::whereIn('id', $c['add_on_ids']??[])->get(), $c['add_on_qtys']??[]);
                        $or_d = [
                            'food_id' => $c['food_id'],
                            'item_campaign_id' => null,
                            'food_details' => json_encode($product),
                            'quantity' => $c['quantity'],
                            'price' => round($price, config('round_up_to_digit')),
                            'tax_amount' => round(Helpers::tax_calculate($product, $price), config('round_up_to_digit')),
                            'discount_on_food' => Helpers::product_discount_calculate($product, $price, $restaurant),
                            'discount_type' => 'discount_on_product',
                            'variant' => json_encode($c['variant']),
                            'variation' => json_encode($c['variation']),
                            'add_ons' => json_encode($addon_data['addons']),
                            'total_add_on_price' => round($addon_data['total_add_on_price'], config('round_up_to_digit')),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $total_addon_price += $or_d['total_add_on_price'];
                        $product_price += $price * $or_d['quantity'];
                        $restaurant_discount_amount += $or_d['discount_on_food'] * $or_d['quantity'];
                        $order_details[] = $or_d;
                    } else {
                        return _response(0, translate('messages.product_unavailable_warning'), [
                            'errors' => [
                                ['code' => 'food', 'message' => translate('messages.product_unavailable_warning')]
                            ]
                        ], 401);
                    }
                }
            }
        }
        

        /*Deal Order (start)*/

        $isDeal = is_string($request['deals']);

        if ($isDeal) {
            $deals_list = json_decode($request['deals'], true);
        } else {
            $deals_list = $request['deals'];
        }
        $deal_price = 0;
        if($request['deals'])
        {

            foreach ($deals_list as $d) {
    
                $quantity = intval($d["quantity"]);
                $price = intval($d["total_price"]);
    
                $deal_price += $quantity * $price;
    
                $deal_order = DealOrderDetail::create([
                    "order_id" => $order->id,
                    "deal_id" => $d["deal_id"],
                    "quantity" => $d["quantity"],
                    "price" => $d["total_price"],
                    "comment" => $d["comment"]??'',
                    "tax_amount" => $d["tax_amount"],
                    "required_products" => json_encode($d["required_products"]??[]),
                    "optional_products" => json_encode($d["optional_products"]??[])
                ]);
            }
        }
        

        $restaurant_discount = Helpers::get_restaurant_discount($restaurant);
        if (isset($restaurant_discount)) {
            if ($product_price + $total_addon_price + $deal_price < $restaurant_discount['min_purchase']) {
                $restaurant_discount_amount = 0;
            }

            if ($restaurant_discount_amount > $restaurant_discount['max_discount']) {
                $restaurant_discount_amount = $restaurant_discount['max_discount'];
            }
        }
        $coupon_discount_amount = $coupon ? CouponLogic::get_discount($coupon, $product_price + $total_addon_price - $restaurant_discount_amount) : 0;
        $total_price = $product_price + $total_addon_price + $deal_price - $restaurant_discount_amount - $coupon_discount_amount;

        $tax = $restaurant->tax;
        $total_tax_amount = ($tax > 0) ? (($total_price * $tax) / 100) : 0;



        if ($restaurant->minimum_order > $product_price + $total_addon_price + $deal_price) {
            return _response(0, translate('messages.failed'), [
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.you_need_to_order_at_least', ['amount' => $restaurant->minimum_order . ' ' . Helpers::currency_code()])]
                ]
            ], 406);
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.you_need_to_order_at_least', ['amount' => $restaurant->minimum_order . ' ' . Helpers::currency_code()])]
                ]
            ], 406);
        }

        $free_delivery_over = BusinessSetting::where('key', 'free_delivery_over')->first()->value;
        if (isset($free_delivery_over)) {
            if ($free_delivery_over <= $total_price - $coupon_discount_amount - $restaurant_discount_amount) {
                $order->delivery_charge = 0;
                $free_delivery_by = 'admin';
            }
        }

        if ($restaurant->free_delivery) {
            $order->delivery_charge = 0;
            $free_delivery_by = 'vendor';
        }

        if ($coupon) {
            $coupon->increment('total_uses');
        }

        $order_amount = round($total_price + $total_tax_amount + $order->delivery_charge, config('round_up_to_digit'));



        if ($request->payment_method == 'wallet' && $request->user()->wallet_balance < $order_amount) {
            return _response(0, translate('messages.insufficient_balance'), [
                'errors' => [
                    ['code' => 'order_amount', 'message' => translate('messages.insufficient_balance')]
                ]
            ], 203);
        }

        try {
            $order->coupon_discount_amount = round($coupon_discount_amount, config('round_up_to_digit'));
            $order->coupon_discount_title = $coupon ? $coupon->title : '';
            $order->free_delivery_by = $free_delivery_by;
            $order->restaurant_discount_amount = round($restaurant_discount_amount, config('round_up_to_digit'));
            $order->total_tax_amount = round($total_tax_amount, config('round_up_to_digit'));
            $order->order_amount = $order_amount + $order->dm_tips;
            $order->save();
            foreach ($order_details as $key => $item) {
                $order_details[$key]['order_id'] = $order->id;
            }
            OrderDetail::insert($order_details);
            \App\Services\FirebaseService::setOrderStatus($order->id, "pending");
            \App\Services\FirebaseService::setOrderStatusVendor($request->restaurant_id, $order->id, "pending");
            Helpers::send_order_notification($order);

            $customer = $request->user();
            $customer->zone_id = $restaurant->zone_id;
            $customer->save();

            $restaurant->increment('total_order');
            if ($request->payment_method == 'wallet') CustomerLogic::create_wallet_transaction($order->user_id, $order->order_amount, 'order_place', $order->id);

            try {
                if ($order->order_status == 'pending') {
                    Mail::to($customer['email'])->send(new \App\Mail\OrderPlaced($order->id));
                }
            } catch (\Exception $ex) {
                info($ex);
            }
            return _response(1, translate('messages.order_placed_successfully'), [
                'order' => $order,
                'order_id' => $order->id,
                'total_ammount' => $total_price + $order->delivery_charge + $total_tax_amount
            ], 200);
        } catch (\Exception $e) {
            info($e);
            return response()->json([$e], 403);
        }
        return _response(0, translate('messages.failed_to_place_order'), [
            'errors' => [
                ['code' => 'order_time', 'message' => translate('messages.failed_to_place_order')]
            ]
        ], 403);
    }

    public function get_order_list(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }


        $paginator = Order::with(['restaurant', 'delivery_man.rating', 'restaurant.business_type'])->withCount('details')
            ->where(['user_id' => $request->user()->id])
            ->whereIn('order_status', ['confirmed', 'pending', 'delivered', 'canceled', 'refund_requested', 'refunded', 'failed'])
            ->Notpos()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address'] ? json_decode($data['delivery_address']) : $data['delivery_address'];
            $data['restaurant'] = $data['restaurant'] ? Helpers::restaurant_data_formatting($data['restaurant']) : $data['restaurant'];
            $data['delivery_man'] = $data['delivery_man'] ? Helpers::deliverymen_data_formatting([$data['delivery_man']]) : $data['delivery_man'];
            return $data;
        }, $paginator->items());

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return _response(1, translate('messages.success'), $data, 200);
    }


    public function get_running_orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }


        $paginator = Order::with(['restaurant', 'delivery_man.rating', 'restaurant.business_type'])
            ->withCount('details')->where(['user_id' => $request->user()->id])
            ->whereNotIn('order_status', ['delivered', 'canceled', 'refund_requested', 'refunded', 'failed'])
            ->Notpos()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address'] ? json_decode($data['delivery_address']) : $data['delivery_address'];
            $data['restaurant'] = $data['restaurant'] ? Helpers::restaurant_data_formatting($data['restaurant']) : $data['restaurant'];
            $data['delivery_man'] = $data['delivery_man'] ? Helpers::deliverymen_data_formatting([$data['delivery_man']]) : $data['delivery_man'];
            return $data;
        }, $paginator->items());
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return _response(1, translate('messages.success'), $data, 200);
        // return response()->json($data, 200);
    }

    public function get_completed_orders(Request $request, $type = 'customer')
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->rider_id) {
            $userId = $request->rider_id;
        } else {
            $userId = $request->user()->id;
        }

        $paginator = Order::with(['restaurant', 'delivery_man.rating', 'details', 'deals' => function ($q) {
            return $q->with('deal_data');
        }])
            ->withCount('details')
            ->when($type == 'rider', function ($q) use ($userId) {
                $q->where(['delivery_man_id' => $userId]);
            })
            ->when($type == 'customer', function ($q) use ($userId) {
                $q->where(['user_id' => $userId]);
            })
            ->whereIn('order_status', ['delivered', 'canceled', 'refund_requested', 'refunded', 'failed'])
            ->Notpos()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address'] ? json_decode($data['delivery_address']) : $data['delivery_address'];
            $data['restaurant'] = $data['restaurant'] ? Helpers::restaurant_data_formatting($data['restaurant']) : $data['restaurant'];
            $data['delivery_man'] = $data['delivery_man'] ? Helpers::deliverymen_data_formatting([$data['delivery_man']]) : $data['delivery_man'];
            $data = $data['details'] ? Helpers::order_details_data_formatting_single($data) : $data;
            return $data;
        }, $paginator->items());
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return _response(1, translate('messages.success'), $data, 200);
        // return response()->json($data, 200);
    }

    public function get_running_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rider_id' => 'required'
        ]);

        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }

        $detail = Order::where('delivery_man_id', $request['rider_id'])
            ->where("assign_status", "Assigned")
            ->with(['details', 'deals' => function ($q) {
                return $q->with('deal_data');
            }])->with(['restaurant' => function ($query) {
                $query->with('business_type');
            }])->get();
        if (count($detail) < 1) {
            return _response(0, 'No orders found', ["orders" => ''], 200);
        }

        foreach ($detail as $item) {
            $item = Helpers::order_details_data_formatting_single($item);
        }

        // $detail = Helpers::order_details_data_formatting_single($detail);

        return _response(1, translate('messages.success'), ["orders" => $detail], 200);


        // $details = OrderDetail::whereHas('order', function($query)use($request){
        //     return $query->where('user_id', $request->user()->id);
        // })->where(['order_id' => $request['order_id']])->get();

        // if($details->count() == 1){
        //     $details = Helpers::order_details_data_formatting($details);
        //     $order["order"] = $details[0];
        //         return _response(1,translate('messages.success'),$order,200);
        // }
        // else 

        // if ($details->count() > 0) {
        //     $details = Helpers::order_details_data_formatting($details);
        //     return _response(1,translate('messages.success'),["order"=>$details],200);
        // } else {
        //     return _response(0, translate('messages.not_found'),[
        //         'errors' => [
        //             ['code' => 'order', 'message' => translate('messages.not_found')]
        //         ]
        //     ], 401);
        // }
    }

    public function get_order_details(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }


        $detail = Order::with(['details', 'deals' => function ($q) {
            return $q->with('deal_data');
        }])->with(['restaurant' => function ($query) {
            $query->with('business_type');
        }])->with('delivery_man')->with('reports')->with('orderRatings')->with('riderRatings')->find($request['order_id']);

        if (!$detail) {
            return _response(0, 'Unable to fetch order details', ['errors' => ['message' => 'Unable to fetch order details']], 403);
        }

        $detail = Helpers::order_details_data_formatting_single($detail);

        return _response(1, translate('messages.success'), ["order" => $detail], 200);


        // $details = OrderDetail::whereHas('order', function($query)use($request){
        //     return $query->where('user_id', $request->user()->id);
        // })->where(['order_id' => $request['order_id']])->get();

        // // if($details->count() == 1){
        // //     $details = Helpers::order_details_data_formatting($details);
        // //     $order["order"] = $details[0];
        // //         return _response(1,translate('messages.success'),$order,200);
        // // }
        // // else 
        // if ($details->count() > 0) {
        //     $details = Helpers::order_details_data_formatting($details);
        //     return _response(1,translate('messages.success'),["order"=>$details],200);
        // } else {
        //     return _response(0, translate('messages.not_found'),[
        //         'errors' => [
        //             ['code' => 'order', 'message' => translate('messages.not_found')]
        //         ]
        //     ], 401);
        // }
    }

    public function cancel_order(Request $request)
    {
        // return $request;
        $order = Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->Notpos()->first();
        if (!$order) {
            return _response(0, "Order not found.", []);
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 401);
        } else if ($order->order_status == 'pending') {

            $order->order_status = 'canceled';
            $order->canceled = now();
            $order->save();
            Helpers::send_order_notification($order);
            \App\Services\FirebaseService::setOrderStatus($order->id, "canceled");
            \App\Services\FirebaseService::setOrderStatusVendor($order->restaurant_id, $order->id, "canceled");
            return _response(0, "Order cancelled successfully.",$order,200);
            // return response()->json(['message' => translate('messages.order_canceled_successfully')], 200);
        }
        return _response(0, "You can't cancel order after confirm.");
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.you_can_not_cancel_after_confirm')]
            ]
        ], 401);
    }

    public function refund_request(Request $request)
    {
        $order = Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->Notpos()->first();
        if (!$order) {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 401);
        } else if ($order->order_status == 'delivered') {

            $order->order_status = 'refund_requested';
            $order->refund_requested = now();
            $order->save();
            return response()->json(['message' => translate('messages.refund_request_placed_successfully')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.you_can_not_request_for_refund_after_delivery')]
            ]
        ], 401);
    }

    public function update_payment_method(Request $request)
    {
        $config = Helpers::get_business_settings('cash_on_delivery');
        if ($config['status'] == 0) {
            return response()->json([
                'errors' => [
                    ['code' => 'cod', 'message' => translate('messages.Cash on delivery order not available at this time')]
                ]
            ], 403);
        }
        $order = Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->Notpos()->first();
        if ($order) {
            Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'payment_method' => 'cash_on_delivery', 'order_status' => 'pending', 'pending' => now()
            ]);

            $fcm_token = $request->user()->cm_firebase_token;
            $value = Helpers::order_status_update_message('pending');
            try {
                if ($value) {
                    $data = [
                        'title' => translate('messages.order_placed_successfully'),
                        'description' => $value,
                        'order_id' => $order->id,
                        'image' => '',
                        'type' => 'order_status',
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'user_id' => $request->user()->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                if ($order->order_type == 'delivery' && !$order->scheduled) {
                    $data = [
                        'title' => translate('messages.order_placed_successfully'),
                        'description' => translate('messages.new_order_push_description'),
                        'order_id' => $order->id,
                        'image' => '',
                    ];
                    Helpers::send_push_notif_to_topic($data, $order->restaurant->zone->deliveryman_wise_topic, 'order_request');
                }
            } catch (\Exception $e) {
                info($e);
            }
            return response()->json(['message' => translate('messages.payment') . ' ' . translate('messages.method') . ' ' . translate('messages.updated_successfully')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.not_found')]
            ]
        ], 404);
    }
    public function assign_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }
        $order_id = $request->order_id;
        $order = Order::where("id", $order_id)->first();
        if (!$order) {
            \App\Services\FirebaseService::notifyAdmin($order_id);
            return _response(0, translate("messages.failed"), [
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.order_not_found')]
                ]
            ], 403);
        }
        $restaurant = Restaurant::where("id", $order->restaurant_id)->first();
        if (!$restaurant) {
            \App\Services\FirebaseService::notifyAdmin($order_id);
            return _response(0, translate("messages.failed"), [
                'errors' => [
                    ['code' => 'restaurant', 'message' => translate('messages.restaurant_not_found')]
                ]
            ], 403);
            // return response()->json();
        }

        $riderResponse = OrderLogic::get_available_riders($order_id, $restaurant->id, $restaurant->latitude, $restaurant->longitude, $restaurant->zone_id);
        if ($riderResponse['status'] == 1) {
            $riders = $riderResponse['data'];

            ob_start();
            echo json_encode(["status_code" => 1, "message" => "success", "response" => []]);

            ob_end_flush();
            if (function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            } else {
                ob_flush();
                flush();
            }
            $check = false;
            $i = 0;

            foreach ($riders as $rider) {
                if ($i == 10) {
                    break;
                }
                // CHECK IF ORDER IS ALREADY ASSIGNED TO SOMEONE
                if (OrderLogic::checkAssignOrder($order_id, $rider->id)) {
                    $check = true;
                    break;
                }
                //CHECK IF RIDER IS VALID OR HIS SHIFT IS STILL ACTIVE
                if (!OrderLogic::checkRider($rider->id, $restaurant->zone_id)) {
                    continue;
                }
                Order::where("id", $order_id)->update([
                    'pending_delivery_man_id' => $rider->id,
                ]);
                \App\Services\FirebaseService::setRiderOrder($order_id, $rider->id);
                // if(count($riders) > 1)
                sleep(35);
                $i++;
            }
            if (OrderLogic::checkAssignOrder($order_id, $rider->id)) {
                $check = true;
            }
            if (!$check) {
                //notify admin
                \App\Services\FirebaseService::notifyAdmin($order_id);
            }
        } else {
            \App\Services\FirebaseService::notifyAdmin($order_id);
            return _response(0, translate("messages.failed"), [
                'errors' => [
                    ['code' => 'rider', 'message' => translate('messages.no_rider_available')]
                ]
            ], 403);
        }
    }
    public function acceptOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'rider_id' => 'required'
        ]);

        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }
        $order_id = $request->order_id;
        $rider_id = $request->rider_id;
        $order = Order::where("id", $order_id)->whereNull("delivery_man_id")->where("assign_status", "Pending")->first();
        if (!$order) {
            return _response(0, translate('messages.failed'), ['errors' => [
                ['code' => 'restaurant', 'message' => translate('messages.order_not_found')]
            ]], 403);
        }
        if ($order->pending_delivery_man_id != $rider_id) {
            return _response(0, translate('messages.failed'), ['errors' => [
                ['code' => 'restaurant', 'message' => translate('messages.already_assigned_order_request_expired')]
            ]], 403);
        }
        $result = Order::where("id", $order_id)
            // ->where("is_locked",0)
            ->where("assign_status", "Pending")->where("id", $order_id)->update([
                "is_locked" => 1,
                "assign_status" => "Assigned",
                "order_status" => "rider_accepted",
                "delivery_man_id" => $rider_id,
                "assign_at" => GeneralLogic::getZoneDate($order->zone_id),
            ]);

        $order = Order::where('id', $order_id)
            ->with(['details', 'deals' => function ($q) {
                return $q->with('deal_data');
            }])->with(['restaurant' => function ($query) {
                $query->with('business_type');
            }])->first();


        $detail = Helpers::order_details_data_formatting_single($order);

        if ($result) {
            Helpers::send_order_notification($order);
            return _response(1, translate('messages.success'), ['order' => $order], 200);
        } else {
            return _response(0, translate('messages.failed'), [
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.unable_to_accept_order')]
                ]
            ], 403);
        }
    }

    public function change_order_status(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required|in:rider_accepted,arrived_at_vendor,picked_up,arrived_at_customer,delivered,confirmed', 'handover'
        ]);

        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }

        $order_id = $request->order_id;
        //$timezone = 'Asia/Karachi';
        //$time = Carbon::now($timezone)->timestamp;
        $order = Order::findOrFail($order_id);



        if (!$order) {
            return _response(0, translate('messages.failed'), ['errors' => [
                ['code' => 'order', 'message' => 'Unable to fulfil request']
            ]], 403);
        }

        // if($request->status == 'picked_up' && $order->order_status != 'handover'){
        //     return _response(0,translate('messages.failed'),['errors' => [
        //             ['code' => 'order', 'message' => 'Order is not ready to be picked up']
        //         ]], 403);
        // }

        $order->order_status = $request->status;

        if ($request->status == 'delivered') {
            $order->assign_status = 'Completed';
        }
        /*if($request->status == 'canceled'){
            $order->canceled = $time;
        }
        if($request->status == 'refunded'){
            $order->refunded = $time;
        }
        if($request->status == 'accepted'){
            $order->accepted = $time;
        }
        if($request->status == 'confirmed'){
            $order->confirmed = $time;
        }
        if($request->status == 'processing'){
            $order->processing = $time;
        }
        if($request->status == 'handover'){
            $order->handover = $time;
        }
        if($request->status == 'picked_up'){
            $order->picked_up = $time;
        }
        if($request->status == 'rider_accepted'){
            $order->rider_accepted = $time;
        }
        if($request->status == 'arrived_at_vendor'){
            $order->arrived_at_vendor = $time;
        }*/
        $result = $order->save();

        if ($request->status == 'delivered') {
            $zone_id = Restaurant::find($order->restaurant_id)->zone_id;
            $points = GeneralLogic::createLoyaltyPoints($order->user_id, $order->order_amount, $order_id, $zone_id);
        }


        if ($result) {
            $order = Order::where('id', $order_id)
                ->with(['details', 'deals' => function ($q) {
                    return $q->with('deal_data');
                }])->with(['restaurant' => function ($query) {
                    $query->with('business_type');
                }])->first();

            Helpers::send_order_notification($order);


            $detail = Helpers::order_details_data_formatting_single($order);
            return _response(1, translate('messages.success'), ['order' => $detail], 200);
        } else {
            return _response(0, translate('messages.failed'), [
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.unable_to_accept_order')]
                ]
            ], 403);
        }
    }

    public function handleOrderEarning(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return _response(0, translate('messages.failed'), ['errors' => Helpers::error_processor($validator)], 403);
        }


        $dm = DeliveryMan::where(['auth_token' => $request->token])->first();

        $wa = DeliveryManWallet::where(['delivery_man_id' => $dm->id])->first();

        $order_id = $request->order_id;
        $order = Order::findOrFail($order_id);

        /*Manage Delivery Man Wallet */
        $order_cash = 0.0;
        $balance = 0.0;
        $payable = 0.0;
        $pending_withdraw = 0.0;

        if ($order->payment_method == "cash_on_delivery") {
            $order_cash = $order->order_amount;
        }

        if ($wa) {
            //old delivery man
            $p_cash = $wa->collected_cash;
            $p_earning = $wa->total_earning;

            /* balance = total_cash - total_earing - total_withdrawn*/

            $balance = ($p_cash + $order_cash)  - ($p_earning + $order->delivery_charge) - ($wa->total_withdrawn);

            if ($balance > 0) {
                $payable = $balance;
                $pending_withdraw = 0.0;
            } else {
                $pending_withdraw = $balance;
                $payable = 0.0;
            }
            $wa->pending_withdraw = $pending_withdraw;
            $wa->payable = $payable;
            $wa->collected_cash = $p_cash + $order_cash;
            $wa->total_earning = $p_earning + $order->delivery_charge;
            $wa->save();
        } else {
            //very first order of a delivery man

            $balance = $order_cash - $order->delivery_charge;

            if ($balance > 0) {
                $payable = $balance;
                $pending_withdraw = 0.0;
            } else {
                $pending_withdraw = $balance;
                $payable = 0.0;
            }
            DB::table('delivery_man_wallets')->insert([
                'delivery_man_id' => $dm['id'],
                'collected_cash' => $order_cash,
                'total_earning' => $order->delivery_charge,
                'total_withdrawn' => 0,
                'pending_withdraw' => $pending_withdraw,
                'payable' => $payable,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        /*Manage Restaurant Earing & Withdrawn*/

        $res =  Restaurant::findOrFail($order->restaurant_id);

        $p_earning = $res->total_earning;
        if ($res) {
            $res->total_earning = $p_earning + ($order->order_amount - $order->delivery_charge);
            $res->save();
        }

        /*Manage Order Earning History*/

        $earning = new OrderEarning();
        $earning->order_id = $order_id;
        $earning->delivery_man_id = $dm->id;
        $earning->restaurant_id = $order->restaurant_id;
        $earning->delivery_man_earning = $order->delivery_charge;
        $earning->restaurant_earning = $order->order_amount - $order->delivery_charge;
        $earning->save();

        return _response(1, "Success", ["message" => "Earning history has been maintained."]);
    }
}
