<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use App\CentralLogics\RestaurantLogic;
use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\SpecialDeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function get_latest_products(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
            'category_id' => 'required',
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $type = $request->query('type', 'all');

        $products = ProductLogic::get_latest_products($request['limit'], $request['offset'], $request['restaurant_id'], $request['category_id'], $type);
        $products['products'] = Helpers::product_data_formatting($products['products'], true, false, app()->getLocale());
        return response()->json($products, 200);
    }

    public function get_searched_products(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $zone_id = json_decode($request->header('zoneId'), true);

        $key = explode(' ', $request['name']);

        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        $type = $request->query('type', 'all');

        $products = Food::active()->type($type)
            ->whereHas('restaurant', function ($q) use ($zone_id) {
                $q->whereIn('zone_id', $zone_id);
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->whereHas('category', function ($q) use ($request) {
                    return $q->whereId($request->category_id)->orWhere('parent_id', $request->category_id);
                });
            })
            ->when($request->restaurant_id, function ($query) use ($request) {
                return $query->where('restaurant_id', $request->restaurant_id);
            })
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })
            ->paginate($limit, ['*'], 'page', $offset);

        $data =  [
            'total_size' => $products->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $products->items()
        ];

        $data['products'] = Helpers::product_data_formatting($data['products'], true, false, app()->getLocale());
        return _response(1, "Success", $data);
        //return response()->json($data, 200);
    }

    public function get_popular_products(Request $request)
    {
        // return $request->headers;
  
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');
        $restaurantId = $request['restaurantId'];
        $zone_id =json_decode($request->header('zoneId'), true);
        $products = ProductLogic::popular_products($zone_id, $request['limit'], $request['offset'], $type, $restaurantId);
        $products['products'] = Helpers::product_data_formatting($products['products'], true, false, app()->getLocale());
        return _response(1, "Success", $products ,200);
        return response()->json($products, 200);
    }

    public function get_most_reviewed_products(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');

        $zone_id = json_decode($request->header('zoneId'), true);
        $products = ProductLogic::most_reviewed_products($zone_id, $request['limit'], $request['offset'], $type);
        $products['products'] = Helpers::product_data_formatting($products['products'], true, false, app()->getLocale());
        return response()->json($products, 200);
    }

    public function get_product(Request $request, $id)
    {

        try {

            $user_id = $request->user_id;
            $product = ProductLogic::get_product($id);
            
            $product = Helpers::product_data_formatting($product, false, false, app()->getLocale());
            if (!empty($user_id)) {
                $wishlisted = Wishlist::where('food_id', $id)->where('user_id', $user_id)->get();
                $product['is_favorite'] = count($wishlisted) > 0 ? 1 : 0;
            }

            return _response(1, 'success', $product, 200);
        } catch (\Exception $e) {

            return _response(0, 'errors', ['code' => 'product-001', 'message' => translate('messages.not_found')], 200);
        }
    }
    public function get_products(Request $request)
    {
        try {
            $ids = json_decode($request->ids);
            if (empty($ids)) {
                return _response(0, translate('no_product_found'), [], 200);
            }
            $products = ProductLogic::get_products($ids);
            $productArr = [];
            foreach ($products as $product) {
                $productArr[] = Helpers::product_data_formatting($product, false, false, app()->getLocale());
            }
            $productData["data"] = $productArr;
            return _response(1, translate('success'), $productData, 200);
        } catch (\Exception $e) {
            return _response(1, 'errors', ['code' => 'product-001', 'message' => translate('messages.not_found'), "exception" => $e->getMessage()], 200);
        }
    }

    public function get_related_products($id)
    {
        if (Food::find($id)) {
            $products = ProductLogic::get_related_products($id);
            $products = Helpers::product_data_formatting($products, true, false, app()->getLocale());
            return response()->json($products, 200);
        }
        return response()->json([
            'errors' => ['code' => 'product-001', 'message' => translate('messages.not_found')]
        ], 404);
    }

    public function get_set_menus()
    {
        try {
            $products = Helpers::product_data_formatting(Food::active()->with(['rating'])->where(['set_menu' => 1, 'status' => 1])->get(), true, false, app()->getLocale());
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['code' => 'product-001', 'message' => 'Set menu not found!']
            ], 404);
        }
    }

    public function get_product_reviews($food_id)
    {
        $reviews = Review::with(['customer', 'food'])->where(['food_id' => $food_id])->active()->get();

        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $item['food_name'] = null;
            if ($item->food) {
                $item['food_name'] = $item->food->name;
                if (count($item->food->translations) > 0) {
                    $translate = array_column($item->food->translations->toArray(), 'value', 'key');
                    $item['food_name'] = $translate['name'];
                }
            }

            unset($item['food']);
            array_push($storage, $item);
        }

        return response()->json($storage, 200);
    }

    public function get_product_rating($id)
    {
        try {
            $product = Food::find($id);
            $overallRating = ProductLogic::get_overall_rating($product->reviews);
            return response()->json(floatval($overallRating[0]), 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function submit_product_review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_id' => 'required',
            'order_id' => 'required',
            'comment' => 'required',
            'rating' => 'required|numeric|max:5',
        ]);

        $product = Food::find($request->food_id);
        if (isset($product) == false) {
            $validator->errors()->add('food_id', translate('messages.food_not_found'));
        }

        $multi_review = Review::where(['food_id' => $request->food_id, 'user_id' => $request->user()->id, 'order_id' => $request->order_id])->first();
        if (isset($multi_review)) {
            return response()->json([
                'errors' => [
                    ['code' => 'review', 'message' => translate('messages.already_submitted')]
                ]
            ], 403);
        } else {
            $review = new Review;
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
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

        $review->user_id = $request->user()->id;
        $review->food_id = $request->food_id;
        $review->order_id = $request->order_id;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->attachment = json_encode($image_array);
        $review->save();

        if ($product->restaurant) {
            $restaurant_rating = RestaurantLogic::update_restaurant_rating($product->restaurant->rating, (int)$request->rating);
            $product->restaurant->rating = $restaurant_rating;
            $product->restaurant->save();
        }

        $product->rating = ProductLogic::update_rating($product->rating, (int)$request->rating);
        $product->avg_rating = ProductLogic::get_avg_rating(json_decode($product->rating, true));
        $product->save();
        $product->increment('rating_count');

        return response()->json(['message' => translate('messages.review_submited_successfully')], 200);
    }
    /*Code by Ramzan*/
    public function getDealDetail($dealId)
    {
        try {

            $deal = DB::table('special_deals')
                ->where('id', $dealId)
                ->first();

            $dealProducts = DB::table('deal_products')
                ->where('deal_id', $dealId)
                ->get();

            $foodIds = $dealProducts->pluck('food_id')->toArray();
            $hasOptions = $dealProducts->pluck('has_options')->toArray();


            $foodData = ProductLogic::get_products($foodIds);
            $foodData = Helpers::product_data_formatting($foodData, true, false, app()->getLocale());

            for ($i = 0; $i < count($foodData); $i++) {

                if ($hasOptions[$i] == true) {
                    $optionsData = DB::table('deal_options')
                        ->where('deal_id', $dealId)
                        ->where('food_id', $foodIds[$i])
                        ->get();
                    $foodData[$i]->options = $optionsData;
                }
            }

            //if deal contains optional product then it will fectch food items of that deal
            if ($deal->has_optional_products == 'true') {
                $optionalProducts = DB::table('deal_optional_products')
                    ->where('deal_id', $dealId)
                    ->get();

                $optionalProductIds = $optionalProducts->pluck('food_id')->toArray();
                $dealPrices = $optionalProducts->pluck('deal_price')->toArray();

                $optionalFoodProducts = [];
                for ($i = 0; $i < count($optionalProductIds); $i++) {
                    $food = DB::table('food')
                        ->where('id', $optionalProductIds[$i])
                        ->get();
                    $food = ProductLogic::get_product($optionalProductIds[$i]);
                    $food = Helpers::product_data_formatting($food, false, false, app()->getLocale());
                    $food['deal_price'] = $dealPrices[$i];

                    $optionalFoodProducts[] = $food;
                }
            }
            $deal->optional_products = $optionalFoodProducts;
            $deal->deal_products = $foodData;

            $response['deals'] = $deal;

            return _response(1, translate('success'), $response, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function getDeals($restaurantId)
    {
        try {
            $deals = DB::table('special_deals')
                ->where('restaurant_id', $restaurantId)
                ->get();

            $response['deals'] = $deals;
            return _response(1, translate('success'), $response, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }
    public function getAllDeals()
    {
        try {
            $deals = SpecialDeal::where('status', 'active')->get();

            $response['deals'] = $deals;
            return _response(1, translate('success'), $response, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }
    public function get_all_deals(Request $request)
    {

        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;



        $paginator = SpecialDeal::where('status', 'active')
            ->paginate($limit, ['*'], 'page', $offset);

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'data' => $paginator->items()
        ];
        return _response(1, translate('messages.success'), $data, 200);
    }
    public function get_top_deals(Request $request)
    {

        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;

        // $deals = SpecialDeal::whereHas('products', function ($query1) {
        //     $query1->whereHas("food",function($query){
        //             $query->selectRaw('SUM(food.price) as total_price')
        //             ->groupBy('deal_products.deal_id')
        //             ->havingRaw('total_price < special_deals.price');
        //     });
        // })->get();

        $paginator = DB::select("select sum(f.price) as total_price,sd.price,sd.id from
        special_deals sd join deal_products dp on sd.id = dp.deal_id join food f on dp.id = f.id
        group by dp.deal_id having sd.price < total_price LIMIT $limit OFFSET $offset");

        $total = 0;
        $q = DB::select("select sum(f.price) as total_price,sd.price,sd.id from
        special_deals sd join deal_products dp on sd.id = dp.deal_id join food f on dp.id = f.id
        group by dp.deal_id having sd.price < total_price");
        $total = count($q);



        $deal_ids = [];
        foreach ($paginator as $item) {
            array_push($deal_ids, $item->id);
        }

        $deals = SpecialDeal::whereIn('id', $deal_ids)->get();

        $data = [
            'total_size' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'data' => $deals
        ];
        return _response(1, translate('messages.success'), $data, 200);
    }
}
