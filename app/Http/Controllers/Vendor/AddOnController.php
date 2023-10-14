<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\Translation;

class AddOnController extends Controller
{
    public function index()
    {
        $addons = AddOn::orderBy('name')->paginate(config('default_pagination'));
        return view('vendor-views.addon.index', compact('addons'));
    }
    
    public function index_mobile(Request $request)
    {
        $rid = $request->rid;
        // dd($rid);
        // exit;
        $addons = AddOn::orderBy('name')->paginate(config('default_pagination'));
        return view('vendor-views.addon.index_mobile', compact('addons', 'rid'));
    }

    public function store(Request $request)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $request->validate([
            'name' => 'required|array',
            'name.*' => 'max:191',
            'price' => 'required|numeric|between:0,999999999999.99',
        ],[
            'name.required' => translate('messages.Name is required!'),
        ]);

        $addon = new AddOn();
        $addon->name = $request->name[array_search('en', $request->lang)];
        $addon->price = $request->price;
        $addon->restaurant_id = \App\CentralLogics\Helpers::get_restaurant_id();
        $addon->save();
        $data = [];
        foreach($request->lang as $index=>$key)
        {
            if($request->name[$index] && $key != 'en')
            {
                array_push($data, Array(
                    'translationable_type'  => 'App\Models\AddOn',
                    'translationable_id'    => $addon->id,
                    'locale'                => $key,
                    'key'                   => 'name',
                    'value'                 => $request->name[$index],
                ));
            }
        }
        if(count($data))
        {
            Translation::insert($data);
        }
        Toastr::success(translate('messages.addon_added_successfully'));
        return back();
    }

    public function edit($id)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $addon = AddOn::withoutGlobalScope('translate')->findOrFail($id);
        return view('vendor-views.addon.edit', compact('addon'));
    }

    public function update(Request $request, $id)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $request->validate([
            'name' => 'required|max:191',
            'price' => 'required|numeric|between:0,999999999999.99',
        ], [
            'name.required' => translate('messages.Name is required!'),
        ]);

        $addon = AddOn::find($id);
        $addon->name = $request->name[array_search('en', $request->lang)];
        $addon->price = $request->price;
        $addon->save();

        foreach($request->lang as $index=>$key)
        {
            if($request->name[$index] && $key != 'en')
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\AddOn',
                        'translationable_id'    => $addon->id,
                        'locale'                => $key,
                        'key'                   => 'name'],
                    ['value'                 => $request->name[$index]]
                );
            }
        }

        Toastr::success(translate('messages.addon_updated_successfully'));
        return redirect(route('vendor.addon.add-new'));
    }

    public function delete(Request $request)
    {
        if(!Helpers::get_restaurant_data()->food_section)
        {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }
        $addon = AddOn::find($request->id);
        $addon->delete();
        Toastr::success(translate('messages.addon_deleted_successfully'));
        return back();
    }
    public function store_mobile(Request $request)
    {
        

        if (!$request->has('rid')) {
            Toastr::warning(translate('messages.permission_denied'));
            return back();
        }

        $rid = $request->rid;

        $request->validate([
            'name' => 'required|array',
            'name.*' => 'max:191',
            'price' => 'required|numeric|between:0,999999999999.99',
        ], [
            'name.required' => translate('messages.Name is required!'),
        ]);

        $addon = new AddOn();
        $addon->name = $request->name[array_search('en', $request->lang)];
        $addon->price = $request->price;
        $addon->restaurant_id = $rid;
        $addon->save();
        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                array_push(
                    $data,
                    array(
                        'translationable_type' => 'App\Models\AddOn',
                        'translationable_id' => $addon->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    )
                );
            }
        }
        if (count($data)) {
            Translation::insert($data);
        }
        Toastr::success(translate('messages.addon_added_successfully'));
        return back();
    }
    
    public function edit_mobile(Request $request, $id)
    {
        $addon = AddOn::findOrFail($id);
        $rid = $request->rid;

        $addon = AddOn::withoutGlobalScope('translate')->findOrFail($id);
        // dd($addon);
        return view('vendor-views.addon.edit_mobile', compact('addon', 'rid'));
    }
    
    public function update_mobile(Request $request, $id)
    {
        //    dd($request->all());
        //    exit;
        // if(!Helpers::get_restaurant_data()->food_section)
        $rid = $request->rid;

        $request->validate([
            'name' => 'required|max:191',
            'price' => 'required|numeric|between:0,999999999999.99',
        ], [
            'name.required' => translate('messages.Name is required!'),
        ]);
        $addon = AddOn::find($id);
        $addon->name = $request->name[0];
        $addon->price = (float) $request->price;
        // dd($addon);
        // exit;
        AddOn::where("id",$addon->id)->update([
            "name"=> $addon->name,
            "price"=>$addon->price
        ]);

        // $addon->save();
        // return response()->json([$request->all()], 200);
        // exit;
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => 'App\Models\AddOn',
                        'translationable_id' => $addon->id,
                        'locale' => $key,
                        'key' => 'name'
                    ],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success(translate('messages.addon_updated_successfully'));
        return redirect(route('addon-new-mobile'));
    }
}
