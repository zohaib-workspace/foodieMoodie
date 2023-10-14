<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entity_id' => 'required',
            'entity_type' => 'required'
            
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        
        
        $favorite = Favorite::where('user_id', $request->user_id)->where('entity_id', $request->entity_id)->where('entity_type', $request->entity_type)->first();
        if (empty($favorite)) {
            $favorite = new Favorite;
            $favorite->user_id = $request->user_id;
            $favorite->entity_id = $request->entity_id;
            $favorite->entity_type = $request->entity_type;
            $favorite->save();
            return _response(1,"Added to favorite successfully.",["message"=>"Added to favorite successfully."]);
        }

        return _response(0,"Already exists in favorites.",[]);
    }

    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entity_id' => 'required',
            'entity_type' => 'required'
            
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        
        
        $favorite = Favorite::where('user_id', $request->user_id)->where('entity_id', $request->entity_id)->where('entity_type', $request->entity_type)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => translate('messages.successfully_removed')], 200);

        }
        return response()->json(['message' => translate('messages.not_found')], 404);
    }

    public function list(Request $request, $id)
    {
        
        $favorites = Favorite::where('user_id', $id)
            ->select('entity_id', 'entity_type')
            ->orderBy('entity_type')
            ->get();

            $fav_product_ids = $favorites->where('entity_type', 'product')->pluck('entity_id')->toArray();
            $fav_restaurant_ids = $favorites->where('entity_type', 'restaurant')->pluck('entity_id')->toArray();
            
            $response["fav_product_ids"] = $fav_product_ids;
            $response["fav_restaurant_ids"] = $fav_restaurant_ids;
            
            return _response(1,"Success",$response);
            
    }
}
