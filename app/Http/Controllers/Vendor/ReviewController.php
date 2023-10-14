<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\Review;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::whereHas('food', function($query){
            return $query->where('restaurant_id', Helpers::get_restaurant_id());
        })->latest()->paginate(config('default_pagination'));
        return view('vendor-views.review.index', compact('reviews'));
    }
    
    public function reviews_mobile(Request $request)
    {
    $rid = $request->rid;
     //dd($rid);
     //exit;

    $reviews = Review::whereHas('food', function($query) use ($rid) {
        return $query->where('restaurant_id', $rid);
    })->latest()->paginate(config('default_pagination'));
    
    
    //$reviews = Review::with('food')->get();
    //return response()->json($reviews);
    //dd($reviews);
    //exit;

    return view('vendor-views.review.reviews_mobile_index', compact('reviews', 'rid'));
    }
}
