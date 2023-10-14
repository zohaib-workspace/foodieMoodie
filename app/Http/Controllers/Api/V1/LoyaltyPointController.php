<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\LoyaltyPointTransaction;
use App\Models\LoyaltyGift;
use App\Models\GiftRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoyaltyPointController extends Controller
{
    public function loyalty_gifts(Request $request)
    {
        try
        {
            $gifts = LoyaltyGift::where('status', 'active')->get();
             
            return _response(1,translate('messages.success'),['gifts' => $gifts], 200);
        }catch(\Exception $ex){
             return _response(0,translate('messages.error'),['error' => $ex], 200);
        }
    }
    public function loyalty_requests(Request $request)
    {
        
        // $validator = Validator::make($request->all(), [
        //     'user_id' => 'required'
        // ]);

        // if ($validator->fails()) {
        //     return _response(0,'Invalid request data',['error' => $validator->errors(), 'data' => $request->all()], 200);
        // }
        // $user_id = $request->input('user_id');
        $user_id = auth()->user()->id;
        try
        {
            $gifts = GiftRequest::with('gift')->where('user_id', $user_id)->get();
             
            return _response(1,translate('messages.success'),['requests' => $gifts], 200);
            
            
        }catch(\Exception $ex){
             return _response(0,translate('messages.error'),['error' => $ex], 200);
        }
    }
    
     public function redeem_gift(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'gift_id' => 'required'
        ]);

        if ($validator->fails()) {
            return _response(0,'Invalid request data',['error' => $validator->errors()], 200);
        }
        $user_id = $request->input('user_id');
        $gift_id = $request->input('gift_id');
        
        $user = User::find($user_id);
        if (!$user) {
            return _response(0,'User not found',[], 200);
        }
        
        $gift = LoyaltyGift::where('id', $gift_id)->where('status', 'active')->first();
        
        if (!$gift) {
            return _response(0,'Gift not found',[], 200);
        }
        if($gift->points > $user->loyalty_point){
            return _response(0,'You have insufficient points',[], 200);
        }

        $giftRequest = GiftRequest::create([
            'user_id' => $user_id,
            'gift_id' => $gift_id,
            'status' => 'pending',
        ]);
        
        if($giftRequest){

        $user->loyalty_point -= $gift->points;
        $user->save();
        return _response(1,'Redeem request created successfully',[], 200);
        }else{
           return _response(0,'Unable to create Redeem Request',[], 200); 
        }
        
    }
    public function point_transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'point' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if($request->user()->loyalty_point < (int)BusinessSetting::where('key','loyalty_point_minimum_point')->first()->value) return response()->json(['errors' => [ ['code' => 'point', 'message' => translate('messages.insufficient_point')]]], 203);

        try
        {
            $wallet_transaction = CustomerLogic::create_wallet_transaction($request->user()->id,$request->point,'loyalty_point',$request->reference);
            CustomerLogic::create_loyalty_point_transaction($request->user()->id, $wallet_transaction->transaction_id, $request->point, 'point_to_wallet');
            if(config('mail.status')) {
                Mail::to($request->user()->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));
            }

            return response()->json(['message' => translate('messages.point_to_wallet_transfer_successfully')], 200);
        }catch(\Exception $ex){
            info($ex);
        }

        return response()->json(['errors' => [ ['code' => 'customer_wallet', 'message' => translate('messages.failed_to_transfer')]]], 203);
    }

    public function transactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $paginator = LoyaltyPointTransaction::where('user_id', $request->user()->id)->latest()->paginate($request->limit, ['*'], 'page', $request->offset);

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request->limit,
            'offset' => $request->offset,
            'data' => $paginator->items()
        ];
        return response()->json($data, 200);
    }
}
