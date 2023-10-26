<?php

namespace App\Http\Controllers;

use App\Models\OrderReview;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use App\CentralLogics\RestaurantLogic;


class UserRestaurentController extends Controller
{
    public function index()
    {
        $zone_id=session()->get('zone_id');
        if($zone_id)
        {
            $data['restaurants'] = Restaurant::whereIn('zone_id',$zone_id)->get();
            
        }else{
            $data['restaurants'] = Restaurant::get();

        }
        return view('home.restaurant.index', $data);
    }
    public function category_wise(Request $request,$id)
    {
        $zone_id=session()->get('zone_id');
        // if (!$request->hasHeader('zoneId')) {
        //     $errors = [];
        //     array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
        //     return _response(0,'error',$errors,403);
        //     // return response()->json([
        //     //     'errors' => $errors
        //     // ], 403);
        // }

        $type = $request->query('type', 'all');
        $onlyOpen = $request->query('only_open', '0');
        $businessType =$request->query('business_type', '');

        $topRated =$request->query('top_rated', '');
        $searchedText =$request->query('searched_text', '');
        $withRating =$request->query('with_rating', '');
        $selected_cat =$request->query('res_category', $id);

        // var_dump($searchedText);exit;
        $filter_data='all';
        $restaurants = RestaurantLogic::get_restaurants($zone_id, $filter_data, 0, 0, $type, $businessType,$onlyOpen, $topRated, $searchedText, $withRating, $selected_cat);
        // $restaurants = RestaurantLogic::get_restaurants($zone_id, $filter_data, $request['limit'], $request['offset'], $type, $businessType,$onlyOpen, $topRated, $searchedText, $withRating, $selected_cat);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting($restaurants['restaurants'], true);
        // return _response(1,'success',$restaurants,200); 
        return view('home.restaurant.category_wise',$restaurants);
    }


    public function restaurent_details(Request $request, $id)
    {


        try {
            // return Auth()->user();
          $restaurent = Http::get(site_url() . 'api/v1/restaurants/details/' . $id); //site_url is helper
            // $restaurent = Http::get('http://127.0.0.1:8000/api/v1/restaurants/details/' . $id);
            if ($restaurent->successful()) {
                $restaurent = $restaurent->json(); // Get the response data
                $data['restaurent'] = $restaurent;
            }
            //categories
            $categories = [
                'category_ids' => json_encode($data['restaurent']['response']['category_ids']),
                'restaurant_id' => $id
            ];
            $cat_products = Http::post(site_url() . 'api/v1/categories/products', $categories);
            if ($cat_products->successful()) {
                $cat_products = $cat_products->json();
                $data['categories'] = $cat_products;
            }
            $data['reviews']=OrderReview::with('user')->where('restaurant_id',$id)->latest()->get();
            

            $data['page_name'] = 'restuarent_details';
            $data['page_title'] = 'Restaurent Details';

            //popular products
            /* $headers = [
                'Accept' => 'application/json',
                'ZoneId' =>[1], // Just pass the integer value, not an array
                // Add more headers if needed
            ];

            $url = 'https://foodie.junaidali.tk/api/v1/products/popular?offset=0&limit=10&restaurantId=1';
            $response = Http::withHeaders($headers)->get($url);
            if ($response->successful()) {
                $responseData = $response->json();
                // Do something with $responseData
            } */

            // echo '<pre>';
            // print_r($data);
            // exit;
            // return $data;
            // return $data['restaurent']['response']['special_deals'];
            return view('home.detail-restaurant', $data);
        } catch (\Throwable $th) {
            throw $th;
            // return 'something went wrong!';
        }
    }
    public function list_map()
    {
        return view('home.list-map');
    }

    public function detail_raustaurent()
    {
        return view('home.detail-restaurant');
    }

    public function submit_raustaurent()
    {
        return view('home.submit-restaurant');
    }
}
