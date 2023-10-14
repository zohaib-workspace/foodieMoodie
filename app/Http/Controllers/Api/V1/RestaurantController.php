<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\RestaurantLogic;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use Illuminate\Support\Facades\Http;

class RestaurantController extends Controller
{





    public function get_restaurants(Request $request, $filter_data="all")
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return _response(0,'error',$errors,403);
            // return response()->json([
            //     'errors' => $errors
            // ], 403);
        }

        $type = $request->query('type', 'all');
        $onlyOpen = $request->query('only_open', '0');
        $businessType =$request->query('business_type', '');

        $topRated =$request->query('top_rated', '');
        $searchedText =$request->query('searched_text', '');
        $withRating =$request->query('with_rating', '');
        $selected_cat =$request->query('res_category', '');

        // var_dump($searchedText);exit;
        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::get_restaurants($zone_id, $filter_data, $request['limit'], $request['offset'], $type, $businessType,$onlyOpen, $topRated, $searchedText, $withRating, $selected_cat);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting($restaurants['restaurants'], true);
        return _response(1,'success',$restaurants,200);
        // return response()->json($restaurants, 200);
    }

    public function get_latest_restaurants(Request $request, $filter_data="all")
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');

        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::get_latest_restaurants($zone_id, $request['limit'], $request['offset'], $type);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting($restaurants['restaurants'], true);

        return response()->json($restaurants['restaurants'], 200);
    }

    public function get_popular_restaurants(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $type = $request->query('type', 'all');
        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::get_popular_restaurants($zone_id, $request['limit'], $request['offset'], $type);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting($restaurants['restaurants'], true);

        // return response()->json($restaurants, 200);
        return _response(1, 'success',$restaurants , 200);
    }

    public function get_restaurant_reviews($id){
        $reviews = RestaurantLogic::get_restaurant_reviews($id);

        return _response(1, 'success',['reviews'=>$reviews] , 200);
    }

    public function get_details(Request $request,$id)
    {


        $restaurant = RestaurantLogic::get_restaurant_details($id);
        if($restaurant)
        {
            $category_ids = DB::table('food')
            ->join('categories', 'food.category_id', '=', 'categories.id')
            ->selectRaw('IF((categories.position = "0"), categories.id, categories.parent_id) as categories')
            ->where('food.restaurant_id', $id)
            ->where('categories.status',1)
            ->groupBy('categories')
            ->get();
            $food_category_ids = DB::table('food')
            ->join('categories', 'food.category_id', '=', 'categories.id')
            ->selectRaw('categories.id as food_categories')
            ->where('food.restaurant_id', $id)
            ->where('categories.status',1)
            ->groupBy('food_categories')
            ->get();
            // dd($category_ids->pluck('categories'));
            $restaurant = Helpers::restaurant_data_formatting($restaurant);
            $restaurant['category_ids'] = array_map('intval', $category_ids->pluck('categories')->toArray());
            $restaurant['food_category_ids'] = array_map('intval', $food_category_ids->pluck('food_categories')->toArray());

             $user_id = $request->user_id;

             if(!empty($user_id)){$wishlisted = Wishlist::where('restaurant_id', $id)->where('user_id', $user_id)->get();
             $restaurant['is_favorite'] = count($wishlisted) > 0? 1:0;}
        }
        return _response(1, 'success',$restaurant , 200);
    }

    public function get_searched_restaurants(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $type = $request->query('type', 'all');

        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::search_restaurants($request['name'], $zone_id, $request->category_id,$request['limit'], $request['offset'], $type);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting($restaurants['restaurants'], true);
        // return response()->json($restaurants, 200);
        return _response(1, 'success',$restaurants , 200);
    }

    public function reviews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $id = $request['restaurant_id'];


        $reviews = Review::with(['customer', 'food'])
        ->whereHas('food', function($query)use($id){
            return $query->where('restaurant_id', $id);
        })
        ->active()->latest()->get();

        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $item['food_name'] = null;
            $item['food_image'] = null;
            $item['customer_name'] = null;
            if($item->food)
            {
                $item['food_name'] = $item->food->name;
                $item['food_image'] = $item->food->image;
                if(count($item->food->translations)>0)
                {
                    $translate = array_column($item->food->translations->toArray(), 'value', 'key');
                    $item['food_name'] = $translate['name'];
                }
            }
            if($item->customer)
            {
                $item['customer_name'] = $item->customer->f_name.' '.$item->customer->l_name;
            }

            unset($item['food']);
            unset($item['customer']);
            array_push($storage, $item);
        }

        return response()->json($storage, 200);
    }
    public function get_services(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $zone_ids= json_decode($request->header('zoneId'), true);
        // var_dump($zone_id);
        // exit;
        $services['services'] = Service::active()->whereIn('zone_id', $zone_ids)->get();

        try {
            return _response(1,'success',$services, 200);
        } catch (\Exception $e) {
            return _response(0,$e->getMessage(),$responseData, 200);
        }
    }

    // public function get_product_rating($id)
    // {
    //     try {
    //         $product = Food::find($id);
    //         $overallRating = ProductLogic::get_overall_rating($product->reviews);
    //         return response()->json(floatval($overallRating[0]), 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['errors' => $e], 403);
    //     }
    // }



}
