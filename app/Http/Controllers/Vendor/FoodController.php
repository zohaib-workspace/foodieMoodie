<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Food;
use App\Models\Restaurant;
use App\Models\SpecialDeal;
use App\Models\DealProducts;
use App\Models\DealOptions;
use App\Models\Review;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Models\Translation;

class FoodController extends Controller

{
    
    public function index()
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $categories = Category::where(['position' => 0])->get();
        return view('vendor-views.product.index', compact('categories'));
    }

    public function store(Request $request)
    {
        
        
        // if(!Helpers::get_restaurant_data()->food_section)
        // {
        //     return response()->json([
        //             'errors'=>[
        //                 ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
        //             ]
        //         ]);
        // }

        $validator = Validator::make($request->all(), [
            'name' => 'array',
            'name.0' => 'required',
            'name.*' => 'max:191',
            'category_id' => 'required',
            //'image' => 'required',
            'price' => 'required|numeric|between:.01,999999999999.99',
            'description.*' => 'max:1000',
            'discount' => 'nullable|numeric|min:0',
        ], [
            'name.0.required' => translate('messages.item_name_required'),
            'category_id.required' => translate('messages.category_required'),
            'veg.required'=>translate('messages.item_type_is_required'),
            'description.*.max' => translate('messages.description_length_warning'),
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $food = new Food;
        $food->name = $request->name[array_search('en', $request->lang)];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }
        $food->category_id = $request->sub_category_id?$request->sub_category_id:$request->category_id;
        $food->category_ids = json_encode($category);
        $food->description = $request->description[array_search('en', $request->lang)];

        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                if ($request[$str][0] == null) {
                    $validator->getMessageBag()->add('name', translate('messages.attribute_choice_option_value_can_not_be_null'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', preg_replace('/\s+/', ' ', $request[$str])));
                array_push($choice_options, $item);
            }
        }
        $food->choice_options = json_encode($choice_options);
        $variations = [];
        $options = [];
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        $str .= str_replace(' ', '', $item);
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = abs($request['price_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
            }
        }
        //combinations end
        $food->variations = json_encode($variations);
        $food->price = $request->price;
        $food->veg = $request->veg;
        $food->image = Helpers::upload('product/', 'png', $request->file('image'));
        $food->available_time_starts = $request->available_time_starts;
        $food->available_time_ends = $request->available_time_ends;
        $food->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
        $food->discount_type = $request->discount_type;
        $food->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $food->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $food->restaurant_id = Helpers::get_restaurant_id();
        $food->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Models\Food',
                    'translationable_id' => $food->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                ));
            }
            if ($request->description[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Models\Food',
                    'translationable_id' => $food->id,
                    'locale' => $key,
                    'key' => 'description',
                    'value' => $request->description[$index],
                ));
            }
        }


        Translation::insert($data);

        return response()->json([], 200);
    }

    public function view($id)
    {
        $product = Food::findOrFail($id);
        $reviews=Review::where(['food_id'=>$id])->latest()->paginate(config('default_pagination'));
        return view('vendor-views.product.view', compact('product','reviews'));
    }

    public function edit($id)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }

        $product = Food::withoutGlobalScope('translate')->findOrFail($id);
        $product_category = json_decode($product->category_ids);
        $categories = Category::where(['parent_id' => 0])->get();
        return view('vendor-views.product.edit', compact('product', 'product_category', 'categories'));
    }
    public function edit_product_mobile(Request $request)
    {
        $vid = $request->vid;
        $rid = $request->rid;
        $pid = $request->pid;
        $product = Food::withoutGlobalScope('translate')->findOrFail($pid);
        $product_category = json_decode($product->category_ids);
        $categories = Category::where(['parent_id' => 0])->get();
        return view('vendor-views.product.edit_mobile', compact('product', 'product_category', 'categories','rid','vid'));
    }
    public function update_product_mobile(Request $request, $id,$rid)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'array',
            'name.0' => 'required',
            'name.*' => 'max:191',
            'category_id' => 'required',
            'price' => 'required|numeric|between:0.01,999999999999.99',
            'description.*' => 'max:1000',
            'discount' => 'required|numeric|min:0',
        ], [
            'name.0.required' => translate('messages.item_name_required'),
            'category_id.required' => translate('messages.category_required'),
            'veg.required'=>translate('messages.item_type_is_required'),
            'description.*.max' => translate('messages.description_length_warning'),
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $p = Food::find($id);

        $p->name = $request->name[array_search('en', $request->lang)];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $p->category_id = $request->sub_category_id?$request->sub_category_id:$request->category_id;
        $p->category_ids = json_encode($category);
        $p->description = $request->description[array_search('en', $request->lang)];

        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                if ($request[$str][0] == null) {
                    $validator->getMessageBag()->add('name', translate('messages.attribute_choice_option_value_can_not_be_null'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', preg_replace('/\s+/', ' ', $request[$str])));
                array_push($choice_options, $item);
            }
        }
        $p->choice_options = json_encode($choice_options);
        $variations = [];
        $options = [];
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        $str .= str_replace(' ', '', $item);
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = abs($request['price_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
            }
        }
        //combinations end
        $p->variations = json_encode($variations);
        $p->price = $request->price;
        $p->veg = $request->veg;
        $p->image = $request->has('image') ? Helpers::update('product/', $p->image, 'png', $request->file('image')) : $p->image;
        $p->available_time_starts = $request->available_time_starts;
        $p->available_time_ends = $request->available_time_ends;
        $p->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
        $p->discount_type = $request->discount_type;
        $p->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $p->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);

        $p->save();
        
        

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Models\Food',
                        'translationable_id' => $p->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
            if ($request->description[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Models\Food',
                        'translationable_id' => $p->id,
                        'locale' => $key,
                        'key' => 'description'],
                    ['value' => $request->description[$index]]
                );
            }
        }
        
        return view('vendor-views.success');
        //return response()->json([], 200);
    }
    public function status(Request $request)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $product = Food::find($request->id);
        $product->status = $request->status;
        $product->save();
        Toastr::success(translate('Food status updated!'));
        return back();
    }
    

    public function update(Request $request, $id)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
                ]
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'array',
            'name.0' => 'required',
            'name.*' => 'max:191',
            'category_id' => 'required',
            'price' => 'required|numeric|between:0.01,999999999999.99',
            'description.*' => 'max:1000',
            'discount' => 'required|numeric|min:0',
        ], [
            'name.0.required' => translate('messages.item_name_required'),
            'category_id.required' => translate('messages.category_required'),
            'veg.required'=>translate('messages.item_type_is_required'),
            'description.*.max' => translate('messages.description_length_warning'),
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $p = Food::find($id);

        $p->name = $request->name[array_search('en', $request->lang)];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $p->category_id = $request->sub_category_id?$request->sub_category_id:$request->category_id;
        $p->category_ids = json_encode($category);
        $p->description = $request->description[array_search('en', $request->lang)];

        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                if ($request[$str][0] == null) {
                    $validator->getMessageBag()->add('name', translate('messages.attribute_choice_option_value_can_not_be_null'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', preg_replace('/\s+/', ' ', $request[$str])));
                array_push($choice_options, $item);
            }
        }
        $p->choice_options = json_encode($choice_options);
        $variations = [];
        $options = [];
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        $str .= str_replace(' ', '', $item);
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = abs($request['price_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
            }
        }
        //combinations end
        $p->variations = json_encode($variations);
        $p->price = $request->price;
        $p->veg = $request->veg;
        $p->image = $request->has('image') ? Helpers::update('product/', $p->image, 'png', $request->file('image')) : $p->image;
        $p->available_time_starts = $request->available_time_starts;
        $p->available_time_ends = $request->available_time_ends;
        $p->discount = $request->discount_type == 'amount' ? $request->discount : $request->discount;
        $p->discount_type = $request->discount_type;
        $p->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $p->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);

        $p->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Models\Food',
                        'translationable_id' => $p->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
            if ($request->description[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Models\Food',
                        'translationable_id' => $p->id,
                        'locale' => $key,
                        'key' => 'description'],
                    ['value' => $request->description[$index]]
                );
            }
        }
        return response()->json([], 200);
    }

    public function delete(Request $request)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $product = Food::find($request->id);

        if($product->image)
        {
            if (Storage::disk('public')->exists('product/' . $product['image'])) {
                Storage::disk('public')->delete('product/' . $product['image']);
            }
        }

        $product->delete();
        Toastr::success(translate('Food removed!'));
        return back();
    }
    
    public function deal_delete(Request $request)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $deal = SpecialDeal::find($request->id);

        if($deal->image)
        {
            if (Storage::disk('public')->exists('product/' . $deal['image'])) {
                Storage::disk('public')->delete('product/' . $deal['image']);
            }
        }

        $deal->delete();
        Toastr::success(translate('Deal removed!'));
        return back();
    }

    public function variant_combination(Request $request)
    {
        $options = [];
        $price = $request->price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $result = [[]];
        foreach ($options as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        $combinations = $result;
        return response()->json([
            'view' => view('vendor-views.product.partials._variant-combinations', compact('combinations', 'price', 'product_name'))->render(),
        ]);
    }

    public function get_categories(Request $request)
    {
        $cat = Category::where(['parent_id' => $request->parent_id])->get();
        $res = '<option value="' . 0 . '" disabled selected>---Select---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->sub_category) {
                $res .= '<option value="' . $row->id . '" selected >' . $row->name . '</option>';
            } else {
                $res .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
        }
        return response()->json([
            'options' => $res,
        ]);
    }

    public function list(Request $request)
    {
        $category_id = $request->query('category_id', 'all');
        $type = $request->query('type', 'all');
        $foods = Food::
        when(is_numeric($category_id), function($query)use($category_id){
            return $query->whereHas('category',function($q)use($category_id){
                return $q->whereId($category_id)->orWhere('parent_id', $category_id);
            });
        })
        ->type($type)->latest()->paginate(config('default_pagination'));
        $category =$category_id !='all'? Category::findOrFail($category_id):null;
        return view('vendor-views.product.list', compact('foods', 'category', 'type'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $foods=Food::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->where('name', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.product.partials._table',compact('foods'))->render()
        ]);
    }

    public function bulk_import_index()
    {
        return view('vendor-views.product.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }

        $data = [];
        $skip = ['youtube_video_url'];
        foreach ($collections as $collection) {
            if ($collection['name'] === "" || $collection['category_id'] === "" || $collection['sub_category_id'] === "" || $collection['price'] === "" || empty($collection['available_time_starts']) === "" || empty($collection['available_time_ends']) || empty($collection['veg']) === "") {
                Toastr::error(translate('messages.please_fill_all_required_fields'));
                return back();
            }
            array_push($data, [
                'name' => $collection['name'],
                'category_id' => $collection['sub_category_id']?$collection['sub_category_id']:$collection['category_id'],
                'category_ids' => json_encode([['id' => $collection['category_id'], 'position' => 0], ['id' => $collection['sub_category_id'], 'position' => 1]]),
                'veg' => $collection['veg'],  //$request->item_type;
                'price' => $collection['price'],
                'discount' => $collection['discount'],
                'discount_type' => $collection['discount_type'],
                'description' => $collection['description'],
                'available_time_starts' => $collection['available_time_starts'],
                'available_time_ends' => $collection['available_time_ends'],
                'image' => $collection['image'],
                'restaurant_id' => Helpers::get_restaurant_id(),
                'add_ons' => json_encode([]),
                'attributes' => json_encode([]),
                'choice_options' => json_encode([]),
                'variations' => json_encode([]),
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }

        try
        {
            DB::beginTransaction();
            DB::table('food')->insert($data);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            Toastr::error(translate('messages.failed_to_import_data'));
            return back();
        }

        Toastr::success(translate('messages.product_imported_successfully', ['count'=>count($data)]));
        return back();
    }

    public function bulk_export_index()
    {
        return view('vendor-views.product.bulk-export');
    }

    public function bulk_export_data(Request $request)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }

        $request->validate([
            'type'=>'required',
            'start_id'=>'required_if:type,id_wise',
            'end_id'=>'required_if:type,id_wise',
            'from_date'=>'required_if:type,date_wise',
            'to_date'=>'required_if:type,date_wise'
        ]);
        $products = Food::when($request['type']=='date_wise', function($query)use($request){
            $query->whereBetween('created_at', [$request['from_date'].' 00:00:00', $request['to_date'].' 23:59:59']);
        })
        ->when($request['type']=='id_wise', function($query)use($request){
            $query->whereBetween('id', [$request['start_id'], $request['end_id']]);
        })
        ->where('restaurant_id', Helpers::get_restaurant_id())
        ->get();
        return (new FastExcel(ProductLogic::format_export_foods($products)))->download('Foods.xlsx');
    }
    
    /////////Deals
    
    
    
    public function create_deal()
    {
        // if(!Helpers::get_restaurant_data()->food_section)
        // {
        //     Toastr::warning(translate('messages.permission_denied'));
        //     return back();
        // }
        $res_data = Helpers::get_restaurant_data();
        $food = Food::where(['restaurant_id' => $res_data->id])->get();
        return view('vendor-views.deal.index', compact('food'));
    }
    public function create_deal_mobile(Request $request)
    {
        $rid = $request->rid;
        $food = Food::where(['restaurant_id' => $rid])->get();

        return view('vendor-views.deal.index_mobile', compact('food', 'rid'));
    }
    
     public function get_variants_mobile(Request $request)
    {
        $food = Food::find($request->parent_id);

        if ($food) {
            $variants = json_decode($food->variations);

            $options = [];
            foreach ($variants as $row) {
                $options[] = [
                    'value' => $row->type,
                    'label' => $row->type,
                ];
            }

            return response()->json([
                'options' => $options,
            ]);
        }

        return response()->json([
            'options' => [],
        ]);
    }
    
    public function get_variants(Request $request)
    {
        $food = Food::where(['id' => $request->parent_id])->first();
        
        $variants = json_decode($food->variations);
        
        $res = '<option value="' . 0 . '" disabled selected>---Select---</option>';
        foreach ($variants as $row) {
                $res .= '<option value="' . $row->type . '" selected >' . $row->type . '</option>';
                
            // if ($row->id == $request->sub_category) {
            // } else {
            //     $res .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            // }
        }
        return response()->json([
            'options' => $res,
        ]);
    }
    
    public function store_deal(Request $request)
    {
        $resData = Helpers::get_restaurant_data();
        if(!$resData->food_section)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
                ]
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'array',
            'name.0' => 'required',
            'name.*' => 'max:191',
            'items' => 'required',
            'image' => 'required',
            'price' => 'required|numeric|between:.01,999999999999.99',
            'description.*' => 'max:1000',
            // 'discount' => 'nullable|numeric|min:0',
        ], [
            'name.0.required' => translate('messages.item_name_required'),
            'description.*.max' => translate('messages.description_length_warning'),
        ]);
        
        if($validator->fails()){
            $errors = [];
            foreach ($validator->errors()->all() as $message) {
                $errors[]['message'] = $message;
            }
          return response()->json([
                    'errors'=> $errors
                ]);
          
    
        }

        // return response()->json([$request->all()], 200);
        try{
        
        $deal = new SpecialDeal;
        $deal->title = $request->name[array_search('en', $request->lang)];
        $deal->description = $request->description[array_search('en', $request->lang)];
        $deal->restaurant_id = Helpers::get_restaurant_id();
        $deal->price = $request->price;
        $deal->number_of_items = count($request->items);
        $deal->image = Helpers::upload('product/', 'png', $request->file('image'));
        // $deal->start_time = $request->available_time_starts;
        // $deal->end_time = $request->available_time_ends;
        
        $deal->save();
        // return response()->json([$request->all()], 200);
        
        $item_count = 0;
        
        // DealProducts::where('deal_id', $deal->id)->delete();
        // DealOptions::where('deal_id', $deal->id)->delete();
        
         if ($request->has('items')) {
            foreach ($request->items as $key => $val) {
                for ($x = 0; $x < count($val['name']); $x++) {
                 
                // $variant = $val['variant'][$x];
                $name = $val['name'][$x];
                $hasOptions = false;
                
                if($val['options'][$x]){
                $options = explode(',', implode('|', preg_replace('/\s+/', ' ', $val['options'][$x])));
                if(count($options)>0){
                    foreach ($options as $opt){
                    if(empty($opt) || !$opt){
                        continue;
                    }
                    $hasOptions = true;
                    $option = new DealOptions;
                    $option->food_id = $key;
                    $option->deal_id = $deal->id;
                    $option->value = $opt;
                    $option->save();
                    }
                }
            }
            
            $prod = new DealProducts;
                    $prod->food_id = $key;
                    $prod->deal_id = $deal->id;
                    $prod->has_options = $hasOptions;
                    $prod->name = $name;
                    $prod->save();
                    $item_count++;
                }   

                
            }
        }
        
        SpecialDeal::where('id', $deal->id)->update(['number_of_items' => $item_count]);

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Models\Food',
                    'translationable_id' => $food->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                ));
            }
            if ($request->description[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Models\Food',
                    'translationable_id' => $food->id,
                    'locale' => $key,
                    'key' => 'description',
                    'value' => $request->description[$index],
                ));
            }
        }


        Translation::insert($data);

        return response()->json([$request->all()], 200);
        }catch (\Exception $exception) {
            return response()->json([$exception], 300);
            
        }
    }
    
        public function store_deal_mobile(Request $request)
    {
        // dd($request->all());
        // exit;
        // $resData = Helpers::get_restaurant_data();
        $rid = $request->rid;
        $resData = Restaurant::find($rid);
        //    dd($rid);


        $validator = Validator::make($request->all(), [
            'name' => 'array',
            'name.0' => 'required',
            'name.*' => 'max:191',
            'items' => 'required',
            'image' => 'required',
            'price' => 'required|numeric|between:.01,999999999999.99',
            'description.*' => 'max:1000',
            // 'discount' => 'nullable|numeric|min:0',
        ], [
            'name.0.required' => translate('messages.item_name_required'),
            'description.*.max' => translate('messages.description_length_warning'),
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $message) {
                $errors[]['message'] = $message;
            }
            return response()->json([
                'errors' => $errors
            ]);


        }

        
        try {

            $deal = new SpecialDeal;
            $deal->title = $request->name[array_search('en', $request->lang)];
            $deal->description = $request->description[array_search('en', $request->lang)];
            $deal->restaurant_id = $rid;
            $deal->price = $request->price;
            $deal->number_of_items = count($request->items);
            $deal->image = Helpers::upload('product/', 'png', $request->file('image'));
            // $deal->start_time = $request->available_time_starts;
            // $deal->end_time = $request->available_time_ends;

            $deal->save();
            

            $item_count = 0;

            // DealProducts::where('deal_id', $deal->id)->delete();
            // DealOptions::where('deal_id', $deal->id)->delete();

            if ($request->has('items')) {
                foreach ($request->items as $key => $val) {
                    for ($x = 0; $x < count($val['name']); $x++) {

                        // $variant = $val['variant'][$x];
                        $name = $val['name'][$x];
                        $hasOptions = false;

                        if ($val['options'][$x]) {
                            $options = explode(',', implode('|', preg_replace('/\s+/', ' ', $val['options'][$x])));
                            if (count($options) > 0) {
                                foreach ($options as $opt) {
                                    if (empty($opt) || !$opt) {
                                        continue;
                                    }
                                    $hasOptions = true;
                                    $option = new DealOptions;
                                    $option->food_id = $key;
                                    $option->deal_id = $deal->id;
                                    $option->value = $opt;
                                    $option->save();

                                }
                            }
                        }

                        $prod = new DealProducts;
                        $prod->food_id = $key;
                        $prod->deal_id = $deal->id;
                        $prod->has_options = $hasOptions;
                        // $prod->name = $name;
                        $prod->save();
                        $item_count++;
                    }


                }
            }

            SpecialDeal::where('id', $deal->id)->update(['number_of_items' => $item_count]);

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Food',
                        'translationable_id' => $food->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    )
                    );
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Food',
                        'translationable_id' => $food->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
                    )
                    );
                }
            }


            Translation::insert($data);

            return response()->json([$request->all()], 200);
        } catch (\Exception $exception) {
            return response()->json([$exception], 300);

        }
    }
    
    public function deal_list(Request $request)
    {
        // $category_id = $request->query('category_id', 'all');
        // $type = $request->query('type', 'all');
        $deals = SpecialDeal::where('restaurant_id', Helpers::get_restaurant_id())
        ->paginate(config('default_pagination'));
    
        return view('vendor-views.deal.list', compact('deals'));
    }
    
    public function deal_search(Request $request){
        $key = explode(' ', $request['search']);
        $deals=SpecialDeal::where('restaurant_id', Helpers::get_restaurant_id())->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->where('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.deal.partials._table',compact('deals'))->render()
        ]);
    }
    
    public function deal_status(Request $request)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $product = SpecialDeal::find($request->id);
        $product->status = $request->status == 0 ? 'Inactive' : 'Active';
        $product->save();
        Toastr::success(translate('Deal status updated!'));
        return back();
    }
    
    public function deal_edit($id)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }

        $deal = SpecialDeal::find($id);
        $res_data = Helpers::get_restaurant_data();
        $food = Food::where(['restaurant_id' => $res_data->id])->get();
        return view('vendor-views.deal.edit', compact('deal', 'food'));
    }
    
    public function deal_update(Request $request, $id)
    {
        $resData = Helpers::get_restaurant_data();
        if(!$resData->food_section)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
                ]
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'array',
            'name.0' => 'required',
            'name.*' => 'max:191',
            'items' => 'required',
            // 'image' => 'required',
            'price' => 'required|numeric|between:.01,999999999999.99',
            'description.*' => 'max:1000',
            // 'discount' => 'nullable|numeric|min:0',
        ], [
            'name.0.required' => translate('messages.item_name_required'),
            'description.*.max' => translate('messages.description_length_warning'),
        ]);
        
        if($validator->fails()){
            $errors = [];
            foreach ($validator->errors()->all() as $message) {
                $errors[]['message'] = $message;
            }
          return response()->json([
                    'errors'=> $errors
                ]);
          
    
        }

        // return response()->json([$request->all()], 200);
        try{
        
        $p = SpecialDeal::find($id);

        $p->title = $request->name[array_search('en', $request->lang)];
        
        $p->description = $request->description[array_search('en', $request->lang)];
        
        
        $p->price = $request->price;
        $p->image = $request->has('image') ? Helpers::update('product/', $p->image, 'png', $request->file('image')) : $p->image;
     
        $p->save();
        $item_count = 0;
        // return response()->json([$request->all()], 200);
        DealOptions::where('deal_id', $p->id)->delete();
        DealProducts::where('deal_id', $p->id)->delete();
        
        
         if ($request->has('items')) {
            foreach ($request->items as $key => $val) {
                for ($x = 0; $x < count($val['name']); $x++) {
                 
                $prod_id = $val['prods_ids'][$x];
                $name = $val['name'][$x];
                $hasOptions = false;
                
                if($val['options'][$x]){
                $options = explode(',', implode('|', preg_replace('/\s+/', ' ', $val['options'][$x])));
                if(count($options)>0){
                    $hasOptions = true;
                    foreach ($options as $opt){
                        if(empty($opt) || !$opt){
                        
                        continue;
                    }
                        //  if($prod_id && $prod_id!= '0'){  
                        //     $options = DealOptions::where(['deal_id' => $p->id, 'food_id' => $key, 'value' => $opt])->first();
                        //     if($options){
                        //         continue;
                        //     }
                        // }
                    $option = new DealOptions;
                    $option->food_id = $key;
                    $option->deal_id = $p->id;
                    $option->value = $opt;
                    $option->save();
                    }
                    
                }
            }
                $prod = new DealProducts;
                    $prod->food_id = $key;
                    $prod->deal_id = $p->id;
                    $prod->has_options = $hasOptions;
                    $prod->name = $name;
                    $prod->save();
                    $item_count++;
                }   

                
            }
        }
        
        SpecialDeal::where('id', $p->id)->update(['number_of_items' => $item_count]);


        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Models\Food',
                        'translationable_id' => $p->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
            if ($request->description[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Models\Food',
                        'translationable_id' => $p->id,
                        'locale' => $key,
                        'key' => 'description'],
                    ['value' => $request->description[$index]]
                );
            }
        }
        return response()->json([$request->all()], 200);
        }catch (\Exception $exception) {
            return response()->json([$exception, $request->all()], 300);
            
        }
    }
    
    public function deal_edit_mobile($id)
    {
        // if (!Helpers::get_restaurant_data()->food_section) {
        //     Toastr::warning(translate('messages.permission_denied'));
        //     return back();
        // }
        // $rid= $request->rid;
        $deal = SpecialDeal::find($id);
        $rid = $deal->restaurant_id;
        // return $rid;
        // $res_data = Helpers::get_restaurant_data();
        $food = Food::where(['restaurant_id' => $rid])->get();
         
        // dd($food);
        // exit;
        return view('vendor-views.deal.edit_deal_mobile', compact('deal', 'food','rid'));
    }
    public function deal_update_mobile(Request $request, $id)
    {
        // $resData = Helpers::get_restaurant_data();
        $res_id = $request->rid;
        $resData= Restaurant::find($res_id);
        // dd($res_id);
        // exit;

      // Check if $res_id is null before accessing its 'food_section' property
      if(!$resData->food_section)
      {
          return response()->json([
              'errors'=>[
                  ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
              ]
          ]);
      }

        $validator = Validator::make($request->all(), [
            'name' => 'array',
            'name.0' => 'required',
            'name.*' => 'max:191',
            'items' => 'required',
            // 'image' => 'required',
            'price' => 'required|numeric|between:.01,999999999999.99',
            'description.*' => 'max:1000',
            // 'discount' => 'nullable|numeric|min:0',
        ], [
            'name.0.required' => translate('messages.item_name_required'),
            'description.*.max' => translate('messages.description_length_warning'),
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $message) {
                $errors[]['message'] = $message;
            }
            return response()->json([
                'errors' => $errors
            ]);


        }

        // return response()->json([$request->all()], 200);
        try {

            $p = SpecialDeal::find($id);
            // dd($p);
            // exit;

            $p->title = $request->name[array_search('en', $request->lang)];
            
            $p->description = $request->description[array_search('en', $request->lang)];
           
            $p->price = $request->price;
            
            $p->image = $request->has('image') ? Helpers::update('product/', $p->image, 'png', $request->file('image')) : $p->image;
            
            $p->save();
            
            $item_count = 0;
            // return response()->json([$request->all()], 200);

            DealOptions::where('deal_id', $p->id)->delete();
            // dd($p->id);
            // exit;
            DealProducts::where('deal_id', $p->id)->delete();

            //  dd($p->id);
            // exit;
            if ($request->has('items')) {
                foreach ($request->items as $key => $val) {
                    for ($x = 0; $x < count($val['name']); $x++) {

                        $prod_id = $val['prods_ids'][$x];
                        $name = $val['name'][$x];
                        $hasOptions = false;

                        if ($val['options'][$x]) {
                            $options = explode(',', implode('|', preg_replace('/\s+/', ' ', $val['options'][$x])));
                            if (count($options) > 0) {
                                $hasOptions = true;
                                foreach ($options as $opt) {
                                    if (empty($opt) || !$opt) {

                                        continue;
                                    }
                                    //  if($prod_id && $prod_id!= '0'){  
                                    //     $options = DealOptions::where(['deal_id' => $p->id, 'food_id' => $key, 'value' => $opt])->first();
                                    //     if($options){
                                    //         continue;
                                    //     }
                                    // }
                                    $option = new DealOptions;
                                    $option->food_id = $key;
                                    $option->deal_id = $p->id;
                                    $option->value = $opt;
                                    $option->save();
                                }

                            }
                        }
                        $prod = new DealProducts;
                        $prod->food_id = $key;
                        $prod->deal_id = $p->id;
                        $prod->has_options = $hasOptions;
                        // $prod->name = $name;
                        $prod->save();
                        $item_count++;
                    }


                }
            }
            // return response()->json([$request->all()], 200);


            SpecialDeal::where('id', $p->id)->update(['number_of_items' => $item_count]);


            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Food',
                            'translationable_id' => $p->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $request->name[$index]]
                    );
                }
                if ($request->description[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Food',
                            'translationable_id' => $p->id,
                            'locale' => $key,
                            'key' => 'description'
                        ],
                        ['value' => $request->description[$index]]
                    );
                }
            }
            return response()->json([$request->all()], 200);
        } catch (\Exception $exception) {
            return response()->json([$exception, $request->all()], 300);

        }
    }
    public function add_product(Request $request)
    {
        
        $rid = $request->rid;
        $vid = $request->vid;
        
        if($rid && $vid){
        $categories = Category::where(['position' => 0])->get();
    
        return view('vendor-views.product.index_mobile', compact('categories','rid','vid'));
        }else{
            return view('error');
        }
        
    }
    public function success()
    {
        return view('success');
    }
    
}


