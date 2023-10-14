<?php

namespace App\Http\Controllers;

use App\CentralLogics\CouponLogic;
use App\CentralLogics\CustomerLogic;
use App\CentralLogics\GeneralLogic;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Coupon;
use App\Models\DealOrderDetail;
use App\Models\DeliveryMan;
use App\Models\DeliveryManWallet;
use App\Models\DMReview;
use App\Models\Food;
use App\Models\ItemCampaign;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderEarning;
use App\Models\OrderReview;
use App\Models\Restaurant;
use App\Models\Zone;
// use auth;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function running_orders()
    {
        if(Auth::check())
        {

            return view('home.orders.running_orders');
        }
        return redirect()->to('user/login');
    }
    public function order_detail()
    {
        if(Auth::check())
        {

            return view('home.orders.order_detail');
        }
        return redirect()->to('user/login');
    }


    public function index()
    {
        return view('home.order');
    }

    public function confirm_order()
    {
        return view('home.confirm-order');
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
            'dm_tips' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            // return 'this is error';

            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

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

        if ($restaurant->open == false) {
            return _response(0, translate('messages.restaurant_is_closed_at_order_time'), [
                'errors' => [
                    ['code' => 'open', 'message' => translate('messages.restaurant_is_closed_at_order_time')]
                ]
            ], 406);
        }

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

        // if($cart_list)
        // {


        foreach ($cart_list as $key => $c) {
            //  return $c['food_id'];
            // $c = get_object_vars($c);
            // var_dump(isset($temp['item_campaign_id']));exit;
            // if(isset())
            if (isset($c['item_campaign_id']) && $c['item_campaign_id'] != null) {
                $product = ItemCampaign::active()->find($c['item_campaign_id']);
                if ($product) {
                    // return 'in the product ';
                    if (!blank($c['variation'])) {
                        if (count(json_decode($product['variations'], true)) > 0) {
                            $price = Helpers::variation_price($product, json_encode($c['variation']));
                        } else {
                             $price = $product['price'];
                            //  return $price;
                        }
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
                    // echo 'add ons quantity '. $or_d['quantity'];
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
                if (isset($c['food_id'])) {
                    $product = Food::active()->find($c['food_id']);

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
                        // return $price;
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
        // if ($isDeal) {
        $deal_price = 0;
        if ($isDeal) {
            foreach ($deals_list as $d) {

                $quantity = intval($d["quantity"]);
                $price = intval($d["total_price"]);

                $deal_price += $quantity * $price;

                $deal_order = DealOrderDetail::create([
                    "order_id" => $order->id,
                    "deal_id" => $d["deal_id"],
                    "quantity" => $d["quantity"],
                    "price" => $d["total_price"],
                    "comment" => $d["comment"],
                    "tax_amount" => $d["tax_amount"],
                    "required_products" => json_encode($d["required_products"]),
                    "optional_products" => json_encode($d["optional_products"])
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
        // return $total_addon_price;
        $total_price = $product_price + $total_addon_price + $deal_price - $restaurant_discount_amount - $coupon_discount_amount;

        $tax = $restaurant->tax;
        $total_tax_amount = ($tax > 0) ? (($total_price * $tax) / 100) : 0;

        if ($restaurant->minimum_order > $product_price + $total_addon_price + $deal_price) {
            // return 'minimum order is here';
            // return $product_price ;
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

        // try {
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
            'order_id' => $order->id,
            'total_ammount' => $total_price + $order->delivery_charge + $total_tax_amount
        ], 200);
        // } catch (\Exception $e) {
        //     info($e);
        //     // return 'this is error';
        //     return response()->json([$e], 403);
        // }
        return _response(0, translate('messages.failed_to_place_order'), [
            'errors' => [
                ['code' => 'order_time', 'message' => translate('messages.failed_to_place_order')]
            ]
        ], 403);
    }
}
