<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class UserRestaurentController extends Controller
{
    public function index()
    {
        $data['restaurants'] = Restaurant::all();
        return view('home.restaurant.index', $data);
    }


    public function restaurent_details(Request $request, $id)
    {


        try {
            // return Auth()->user();

            $restaurent = Http::get(site_url() . 'api/v1/restaurants/details/' . $id); //site_url is helper
            // $restaurent = Http::get('http://127.0.0.1:8000/api/v1/restaurants/details/' . $id); //site_url is helper
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
