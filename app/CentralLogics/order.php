<?php

namespace App\CentralLogics;

use App\Models\Food;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Shift;
use App\Models\ShiftHistory;
use App\Models\AdminWallet;
use App\Models\BusinessSetting;
use App\Models\OrderTransaction;
use App\Models\RestaurantWallet;
use App\Models\DeliveryManWallet;
use App\Models\DeliveryMan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderLogic
{
    public static function gen_unique_id()
    {
        return rand(1000, 9999) . '-' . Str::random(5) . '-' . time();
    }

    public static function track_order($order_id)
    {
        return Helpers::order_data_formatting(Order::with(['details', 'delivery_man.rating'])->where(['id' => $order_id])->first(), false);
    }

    public static function place_order($customer_id, $email, $customer_info, $cart, $payment_method, $discount, $coupon_code = null)
    {
        try {
            $or = [
                'id' => 100000 + Order::all()->count() + 1,
                'user_id' => $customer_id,
                'order_amount' => CartManager::cart_grand_total($cart) - $discount,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'payment_method' => $payment_method,
                'transaction_ref' => null,
                'discount_amount' => $discount,
                'coupon_code' => $coupon_code,
                'discount_type' => $discount == 0 ? null : 'coupon_discount',
                'shipping_address' => $customer_info['address_id'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            $o_id = DB::table('orders')->insertGetId($or);

            foreach ($cart as $c) {
                $product = Food::where('id', $c['id'])->first();
                $or_d = [
                    'order_id' => $o_id,
                    'food_id' => $c['id'],
                    'seller_id' => $product->added_by == 'seller' ? $product->user_id : '0',
                    'product_details' => $product,
                    'qty' => $c['quantity'],
                    'price' => $c['price'],
                    'tax' => $c['tax'] * $c['quantity'],
                    'discount' => $c['discount'] * $c['quantity'],
                    'discount_type' => 'discount_on_product',
                    'variant' => $c['variant'],
                    'variation' => json_encode($c['variations']),
                    'delivery_status' => 'pending',
                    'shipping_method_id' => $c['shipping_method_id'],
                    'payment_status' => 'unpaid',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('order_details')->insert($or_d);
            }
            if(config('mail.status'))
            {
                Mail::to($email)->send(new \App\Mail\OrderPlaced($o_id));
            }

        } catch (\Exception $e) {

        }

        return $o_id;
    }

    public static function updated_order_calculation($order)
    {
        return true;
    }

    //here
    public static function create_transaction($order, $received_by=false, $status = null)
    {
        $comission = !isset($order->restaurant->comission)?\App\Models\BusinessSetting::where('key','admin_commission')->first()->value:$order->restaurant->comission;
        $order_amount = $order->order_amount - $order->delivery_charge - $order->total_tax_amount - $order->dm_tips;
        $comission_amount = $comission?($order_amount/ 100) * $comission:0;
        $admin_subsidy = 0;

        $delivery_charge_comission = BusinessSetting::where('key', 'delivery_charge_comission')->first();
        $delivery_charge_comission_percentage = $delivery_charge_comission ? $delivery_charge_comission->value : 0;
        $comission_on_delivery = $delivery_charge_comission_percentage * ( $order->original_delivery_charge / 100 );
        $comission_on_actual_delivery_fee = ($order->delivery_charge > 0) ? $comission_on_delivery : 0;

        if($order->free_delivery_by == 'admin')
        {
            $admin_subsidy = $order->original_delivery_charge;
        }

        try{
            OrderTransaction::insert([
                'vendor_id' =>$order->restaurant->vendor->id,
                'delivery_man_id'=>$order->delivery_man_id,
                'order_id' =>$order->id,
                'order_amount'=>$order->order_amount,
                'restaurant_amount'=>$order_amount + $order->total_tax_amount - $comission_amount,
                'admin_commission'=>$comission_amount - $admin_subsidy,
                //add a new column. add the comission here
                'delivery_charge'=>$order->delivery_charge - $comission_on_actual_delivery_fee,//minus here
                'original_delivery_charge'=>$order->original_delivery_charge - $comission_on_delivery,//calculate the comission with this. minus here
                'tax'=>$order->total_tax_amount,
                'received_by'=> $received_by?$received_by:'admin',
                'zone_id'=>$order->zone_id,
                'status'=> $status,
                'dm_tips'=> $order->dm_tips,
                'created_at' => now(),
                'updated_at' => now(),
                'delivery_fee_comission'=>$comission_on_actual_delivery_fee
            ]);
            $adminWallet = AdminWallet::firstOrNew(
                ['admin_id' => Admin::where('role_id', 1)->first()->id]
            );

            $vendorWallet = RestaurantWallet::firstOrNew(
                ['vendor_id' => $order->restaurant->vendor->id]
            );
            if($order->delivery_man && !$order->restaurant->self_delivery_system){
                $dmWallet = DeliveryManWallet::firstOrNew(
                    ['delivery_man_id' => $order->delivery_man_id]
                );

                if ($order->delivery_man->earning == 1) {
                    $dmWallet->total_earning = $dmWallet->total_earning + $order->dm_tips + $order->original_delivery_charge - $comission_on_delivery;
                } else {
                    $adminWallet->total_commission_earning = $adminWallet->total_commission_earning + $order->dm_tips + $order->original_delivery_charge - $comission_on_delivery;
                }
            }


            $adminWallet->total_commission_earning = $adminWallet->total_commission_earning + $comission_amount + $comission_on_actual_delivery_fee - $admin_subsidy;

            if($order->restaurant->self_delivery_system)
            {
                $vendorWallet->total_earning = $vendorWallet->total_earning + $order->delivery_charge + $order->dm_tips;
            }
            else{
                $adminWallet->delivery_charge = $adminWallet->delivery_charge + $order->delivery_charge - $comission_on_actual_delivery_fee;
            }

            $vendorWallet->total_earning = $vendorWallet->total_earning + ($order_amount + $order->total_tax_amount - $comission_amount);
            try
            {
                DB::beginTransaction();
                if($received_by=='admin')
                {
                    $adminWallet->digital_received = $adminWallet->digital_received + $order->order_amount;
                }
                else if($received_by=='restaurant' && $order->payment_method == 'cash_on_delivery')
                {
                    $vendorWallet->collected_cash = $vendorWallet->collected_cash + $order->order_amount;
                }
                else if($received_by==false)
                {
                    $adminWallet->manual_received = $adminWallet->manual_received + $order->order_amount;
                }
                else if($received_by=='deliveryman' && $order->delivery_man->type == 'zone_wise' && $order->payment_method == 'cash_on_delivery')
                {
                    if(!isset($dmWallet)) {
                        $dmWallet = DeliveryManWallet::firstOrNew(
                            ['delivery_man_id' => $order->delivery_man_id]
                        );
                    }
                    $dmWallet->collected_cash=$dmWallet->collected_cash+$order->order_amount;
                }
                if(isset($dmWallet)) {
                    $dmWallet->save();
                }
                $adminWallet->save();
                $vendorWallet->save();
                DB::commit();
                if($order->user_id) CustomerLogic::create_loyalty_point_transaction($order->user_id, $order->id, $order->order_amount, 'order_place');

            }
            catch(\Exception $e)
            {
                DB::rollBack();
                info($e);
                return false;
            }
        }
        catch(\Exception $e){
            info($e);
            return false;
        }

        return true;
    }

    public static function refund_order($order)
    {
        $order_transaction = $order->transaction;
        if($order_transaction == null || $order->restaurant == null)
        {
            return false;
        }
        $received_by = $order_transaction->received_by;

        $adminWallet = AdminWallet::firstOrNew(
            ['admin_id' => Admin::where('role_id', 1)->first()->id]
        );

        $vendorWallet = RestaurantWallet::firstOrNew(
            ['vendor_id' => $order->restaurant->vendor->id]
        );


        $adminWallet->total_commission_earning = $adminWallet->total_commission_earning - $order_transaction->admin_commission;

        $vendorWallet->total_earning = $vendorWallet->total_earning - $order_transaction->restaurant_amount;

        $refund_amount = $order->order_amount;

        $status = 'refunded_with_delivery_charge';
        if($order->order_status == 'delivered')
        {
            $refund_amount = $order->order_amount - $order->delivery_charge;
            $status = 'refunded_without_delivery_charge';
        }
        else
        {
            $adminWallet->delivery_charge = $adminWallet->delivery_charge - $order_transaction->delivery_charge;
        }
        try
        {
            DB::beginTransaction();
            if($received_by=='admin')
            {
                if($order->delivery_man_id && $order->payment_method != "cash_on_delivery")
                {
                    $adminWallet->digital_received = $adminWallet->digital_received - $refund_amount;
                }
                else
                {
                    $adminWallet->manual_received = $adminWallet->manual_received - $refund_amount;
                }

            }
            else if($received_by=='restaurant')
            {
                $vendorWallet->collected_cash = $vendorWallet->collected_cash - $refund_amount;
            }

                // DB::table('account_transactions')->insert([
                //     'from_type'=>'customer',
                //     'from_id'=>$order->user_id,
                //     'current_balance'=> 0,
                //     'amount'=> $refund_amount,
                //     'method'=>'CASH',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ]);

            else if($received_by=='deliveryman')
            {
                $dmWallet = DeliveryManWallet::firstOrNew(
                    ['delivery_man_id' => $order->delivery_man_id]
                );
                $dmWallet->collected_cash=$dmWallet->collected_cash - $refund_amount;
                $dmWallet->save();
            }
            $order_transaction->status = $status;
            $order_transaction->save();
            $adminWallet->save();
            $vendorWallet->save();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            info($e);
            return false;
        }
        return true;

    }

    public static function format_export_data($orders)
    {
        $data = [];
        foreach($orders as $key=>$order)
        {

            $data[]=[
                '#'=>$key+1,
                translate('messages.order')=>$order['id'],
                translate('messages.date')=>date('d M Y',strtotime($order['created_at'])),
                translate('messages.customer')=>$order->customer?$order->customer['f_name'].' '.$order->customer['l_name']:translate('messages.invalid').' '.translate('messages.customer').' '.translate('messages.data'),
                translate('messages.Restaurant')=>\Str::limit($order->restaurant?$order->restaurant->name:translate('messages.Restaurant deleted!'),20,'...'),
                translate('messages.payment').' '.translate('messages.status')=>$order->payment_status=='paid'?translate('messages.paid'):translate('messages.unpaid'),
                translate('messages.total')=>\App\CentralLogics\Helpers::format_currency($order['order_amount']),
                translate('messages.order').' '.translate('messages.status')=>translate('messages.'. $order['order_status']),
                translate('messages.order').' '.translate('messages.type')=>translate('messages.'.$order['order_type'])
            ];
        }
        return $data;
    }
    public static function get_distance_delivery_man($riders,$lat,$lng){
        $deliveryMen =  DeliveryMan::distance($lat,$lng)->notonbreak()->whereIn('id',$riders)->orderBy('distance')->get();
        if(count($deliveryMen) < 1){
            return [];
        }
        return $deliveryMen;
    }
    public static function get_available_riders($order_id,$restaurant_id,$restaurant_lat,$restaurant_lng,$zone_id){
        
        
        $nowDate =  \App\CentralLogics\GeneralLogic::getZoneDate($zone_id,true);
        
        
        
        $nowDateTime = \App\CentralLogics\GeneralLogic::getZoneDate($zone_id);
        $time = explode(" ",$nowDateTime)[1];
        $shifts = Shift::where("zone_id",$zone_id)
        ->where("end_time",">=",$time)->where("shift_date",$nowDate)->started()->get();
        $riders = [];
        foreach($shifts as $shift){
            // Filter Shift
            $riders[] = $shift->delivery_man;
        }
        if(count($riders) < 1){
            return ["status"=>-1]; // no rider available
        }
        
        $riderResult = static::get_distance_delivery_man($riders,$restaurant_lat,$restaurant_lng);
        if(count($riderResult) < 1){
            return ["status"=>-1]; // no rider available
        }
        return ['status'=>1,'data'=>$riderResult];
        // Assign Order Here
    }
    public static function checkAssignOrder($order_id,$rider){
       $orders = Order::where("id",$order_id)->where("is_locked",1)->get();
       return count($orders) > 0;
        // Assign Order Here
    }    
    public static function checkRider($rider_id,$zone_id){
        $nowDate =  \App\CentralLogics\GeneralLogic::getZoneDate($zone_id,true); 
        $nowDateTime = \App\CentralLogics\GeneralLogic::getZoneDate($zone_id);
        $time = explode(" ",$nowDateTime)[1];
        // CHECK IF RIDER HAVE NOT ENDED SHIFT BY CHANCE
        $shifts = Shift::where("delivery_man",$rider_id)->where("end_time",">=",$time)->where("shift_date",$nowDate)->started()->get();
        return count($shifts) > 0;
        
    }
}
