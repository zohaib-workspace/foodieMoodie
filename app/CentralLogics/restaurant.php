<?php

namespace App\CentralLogics;

use App\Models\OrderReview;
use App\Models\Restaurant;
use App\Models\OrderTransaction;

class RestaurantLogic
{
    public static function get_restaurants($zone_id, $filter, $limit = 10, $offset = 1, $type='all',$businessType = '',$onlyOpen = 0, $topRated = '', $searchedText = '', $withRating = '', $selected_cat = '')
    {
         $dayOfWeek = now()->dayOfWeek;
         $time = now()->format('H:i:s');
         
        $paginator = Restaurant::withOpen()
        ->when($onlyOpen == 1, function($q){
            $q->OnlyOpen();
        })
        ->with(['discount'=>function($q){
            return $q->validate();
        }, 'business_type'])
        ->whereIn('zone_id', $zone_id)
        ->when($filter=='delivery', function($q){
            return $q->Delivery();
        })
        ->when($filter=='take_away', function($q){
            return $q->Takeaway();
        })
        ->active()
        ->when(!empty($topRated), function($q) use ($topRated){
            $q->orderBy('order_count', 'desc');
        })
        ->when(!empty($withRating), function($q) use ($withRating){
            $q->where('rating', $withRating);
        })
         ->when(!empty($searchedText), function($q) use($searchedText){
             $q->where('name','like',"%$searchedText%")->
            orWhereHas('foods',function($query) use($searchedText) {
                 $query->where('name','like',"%$searchedText%");
            });
        })
        ->when(!empty($selected_cat), function($q) use($selected_cat){
            $q->whereHas('foods', function($query) use ($selected_cat){
                $query
                // ->whereRaw("category_ids->>'$[*].id' = '$selected_cat'");
                ->where('category_ids', 'like','%"'.$selected_cat.'"%');
                
            });
        })
        ->type($type)
        ->businessType($businessType)
        // ->orderBy('open', 'desc')
        ->paginate($limit, ['*'], 'page', $offset);
        
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator->items()
        ];
    }

    public static function get_latest_restaurants($zone_id, $limit = 10, $offset = 1, $type='all')
    {
        $paginator = Restaurant::withOpen()
        ->with(['discount'=>function($q){
            return $q->validate();
        }])->whereIn('zone_id', $zone_id)
        ->Active()
        ->type($type)
        ->latest()
        ->limit(50)
        ->get();
        // ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->count(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator
        ];
    }

    public static function get_popular_restaurants($zone_id, $limit = 10, $offset = 1, $type='all')
    {
        $paginator = Restaurant::withOpen()
        ->with(['discount'=>function($q){
            return $q->validate();
        }, 'business_type'])->whereIn('zone_id', $zone_id)
        ->Active()
        ->type($type)
        ->withCount('orders')
        ->orderBy('open', 'desc')
        ->orderBy('orders_count', 'desc')
        ->limit(5)
        ->get();
        // ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            // 'total_size' => $paginator->count(),
            // 'limit' => $limit,
            // 'offset' => $offset,
            'restaurants' => $paginator
        ];
    }

    public static function get_restaurant_details($restaurant_id)
    {
        return Restaurant::with(['discount'=>function($q){
            return $q->validate();
        }, 'campaigns', 'schedules'])
        ->with(['discount'=>function($q){
            return $q->validate();
        }, 'business_type'])
        ->active()->whereId($restaurant_id)->first();
    }
    public static function get_restaurant_reviews($restaurant_id)
    {
        return OrderReview::with('user')->where('restaurant_id', $restaurant_id)->get();
    }

    public static function calculate_restaurant_rating($ratings)
    {
        $total_submit = $ratings[0]+$ratings[1]+$ratings[2]+$ratings[3]+$ratings[4];
        $rating = ($ratings[0]*5+$ratings[1]*4+$ratings[2]*3+$ratings[3]*2+$ratings[4])/($total_submit?$total_submit:1);
        return ['rating'=>$rating, 'total'=>$total_submit];
    }

    public static function update_restaurant_rating($ratings, $product_rating)
    {
        $restaurant_ratings = [1=>0 , 2=>0, 3=>0, 4=>0, 5=>0];
        if($ratings)
        {
            $restaurant_ratings[1] = $ratings[4];
            $restaurant_ratings[2] = $ratings[3];
            $restaurant_ratings[3] = $ratings[2];
            $restaurant_ratings[4] = $ratings[1];
            $restaurant_ratings[5] = $ratings[0];
            $restaurant_ratings[$product_rating] = $ratings[5-$product_rating] + 1;
        }
        else
        {
            $restaurant_ratings[$product_rating] = 1;
        }
        return json_encode($restaurant_ratings);
    }

    public static function search_restaurants($name, $zone_id, $category_id= null,$limit = 10, $offset = 1, $type='all')
    {
        $key = explode(' ', $name);
        $paginator = Restaurant::withOpen()->with(['discount'=>function($q){
            return $q->validate();
        }, 'business_type'])
        ->whereIn('zone_id', $zone_id)->weekday()->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })
        ->when($category_id, function($query)use($category_id){
            $query->whereHas('foods.category', function($q)use($category_id){
                return $q->whereId($category_id)->orWhere('parent_id', $category_id);
            });
        })
        ->active()->type($type)->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator->items()
        ];
    }

    public static function get_overall_rating($reviews)
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }

    public static function get_earning_data($vendor_id)
    {
        $monthly_earning = OrderTransaction::whereMonth('created_at', date('m'))->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');
        $weekly_earning = OrderTransaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');
        $daily_earning = OrderTransaction::whereDate('created_at', now())->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');

        return['monthely_earning'=>(float)$monthly_earning, 'weekly_earning'=>(float)$weekly_earning, 'daily_earning'=>(float)$daily_earning];
    }

    public static function format_export_restaurants($restaurants)
    {
        $storage = [];
        foreach($restaurants as $item)
        {
            if($item->restaurants->count()<1)
            {
                break;
            }
            $storage[] = [
                'id'=>$item->id,
                'ownerFirstName'=>$item->f_name,
                'ownerLastName'=>$item->l_name,
                'restaurantName'=>$item->restaurants[0]->name,
                'logo'=>$item->restaurants[0]->logo,
                'phone'=>$item->phone,
                'email'=>$item->email,
                'latitude'=>$item->restaurants[0]->latitude,
                'longitude'=>$item->restaurants[0]->longitude,
                'zone_id'=>$item->restaurants[0]->zone_id,
            ];
        }

        return $storage;
    }
}
