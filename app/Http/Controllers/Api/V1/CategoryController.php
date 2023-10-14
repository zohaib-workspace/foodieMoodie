<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CategoryLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function get_categories(Request $request)
    {
        try {
            $type = $request->query('business_type','');
            $categories = Category::where(['position'=>0,'status'=>1])->when(!empty($type), function($q) use ($type){
                if($type > 0)
                    $q->where('business_type',$type);
            })->orderBy('priority','desc')->get();
            
            $data['categories'] = Helpers::category_data_formatting($categories, true);
            
            return _response(1,'success',$data,200);
            // return response()->json(Helpers::category_data_formatting($categories, true), 200);
        } catch (\Exception $e) {
            return _response(0,$e->getMessage(),[],200);
            // return response()->json([], 200);
        }
    }

    public function get_childes($id, $storeId)
    {
        try {
            // $categories = Category::where(['parent_id' => $id,'status'=>1])->orderBy('priority','desc')->get();
            // return response()->json(Helpers::category_data_formatting($categories, true), 200);
            // $data['data'] = Helpers::category_data_formatting($categories, true);
            // $ids = array();
            // $ids[] = $id;
            
            $data['data'] = CategoryLogic::categories_products2($id,$storeId);
            
            return _response(1,'success',$data,200);
        } catch (\Exception $e) {
            // return response()->json([], 200);
            return _response(0,$e->getMessage(),[],200);
        }
        
        //  $categories = Category::where(['parent_id' => $id,'status'=>1])->orderBy('priority','desc')->get();
        //     // return response()->json(Helpers::category_data_formatting($categories, true), 200);
        //     $data['data'] = Helpers::category_data_formatting($categories, true);
            
        //     return _response(1,'success',$data,200);
        
    }

    public function get_products($id, Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $zone_id= json_decode($request->header('zoneId'), true);

        $type = $request->query('type', 'all');
        $data = CategoryLogic::products($id, $zone_id, $request['limit'], $request['offset'], $type);
        $data['products'] = Helpers::product_data_formatting($data['products'] , true, false, app()->getLocale());
        return response()->json($data, 200);
    }


    public function get_restaurants($id, Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $zone_id= json_decode($request->header('zoneId'), true);

        $type = $request->query('type', 'all');

        $data = CategoryLogic::restaurants($id, $zone_id, $request['limit'], $request['offset'], $type);
        $data['restaurants'] = Helpers::restaurant_data_formatting($data['restaurants'] , true);
        return response()->json($data, 200);
    }

    public function get_categories_products(Request $request)
    {
        // print_r($request->category_ids);
        // exit;
        $validator = Validator::make($request->all(), [
            // 'category_ids' => 'required|array',
            'category_ids' => 'required',
            'category_ids.*' => 'integer',
            'restaurant_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        try {
            // $categoriesString = $request->category_ids;
            
            $ids = json_decode($request->category_ids, true);
            // $ids = $request->category_ids;
            $restaurant_id = $request->restaurant_id;
            $response["data"] = CategoryLogic::categories_products($ids,$restaurant_id);
            return _response(1,'success',$response,200);
        } catch (\Exception $e) {
            return _response(0,$e->getMessage(),[],200);
        }
    }

    public function get_all_products($id,Request $request)
    {
        try {
            return response()->json(Helpers::product_data_formatting(CategoryLogic::all_products($id), true, false, app()->getLocale()), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
