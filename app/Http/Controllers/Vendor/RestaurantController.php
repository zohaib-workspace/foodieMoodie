<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;

class RestaurantController extends Controller
{
    public function view()
    {
        $shop = Helpers::get_restaurant_data();
        return view('vendor-views.shop.shopInfo', compact('shop'));
    }

    public function edit()
    {
        $shop = Helpers::get_restaurant_data();
        return view('vendor-views.shop.edit', compact('shop'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'address' => 'nullable|max:1000',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:restaurants,phone,'.Helpers::get_restaurant_id(),
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
        ]);
        $shop = Restaurant::findOrFail(Helpers::get_restaurant_id());
        $shop->name = $request->name;
        $shop->address = $request->address;
        $shop->phone = $request->contact;

        $shop->logo = $request->has('image') ? Helpers::update('restaurant/', $shop->logo, 'png', $request->file('image')) : $shop->logo;

        $shop->cover_photo = $request->has('photo') ? Helpers::update('restaurant/cover/', $shop->cover_photo, 'png', $request->file('photo')) : $shop->cover_photo;

        $shop->save();

        if($shop->vendor->userinfo) {
            $userinfo = $shop->vendor->userinfo;
            $userinfo->f_name = $shop->name;
            $userinfo->image = $shop->logo;
            $userinfo->save();
        }

        Toastr::success(translate('messages.Business_Data_Updated'));
        return redirect()->route('vendor.shop.view');
    }
    
    public function business_view_mobile(Request $request)
    {
        
        $rid = $request->rid;
        
        $shop = Restaurant::findOrFail($rid);
         
        return view('vendor-views.shop.shopInfo_mobile', compact('shop'));
    }
     public function business_edit_mobile(Request $request)
    {
        // $shop = Helpers::get_restaurant_data();
        $rid = $request->rid;
        $shop = Restaurant::findOrFail($rid);
        // dd($shop);
        // exit;
        return view('vendor-views.shop.edit_mobile', compact('shop'));
    }
    public function business_update_mobile(Request $request)
    {
        $rid = $request->rid;
        // dd($rid);
        // exit;
        $request->validate([
            'name' => 'required|max:191',
            'address' => 'nullable|max:1000',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:restaurants,phone,'.$rid,
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
        ]);
        
        $shop = Restaurant::find($rid);

        // dd($shop);
        // exit;
        $shop->name = $request->name;
        $shop->address = $request->address;
        $shop->phone = $request->contact;
        // dd($shop);
        // exit;
        $shop->logo = $request->has('image') ? Helpers::update('restaurant/', $shop->logo, 'png', $request->file('image')) : $shop->logo;

        $shop->cover_photo = $request->has('photo') ? Helpers::update('restaurant/cover/', $shop->cover_photo, 'png', $request->file('photo')) : $shop->cover_photo;

        Restaurant::where("id",$shop->id)->update([
            "name"=> $shop->name,
            "address"=>$shop->address,
            "phone"=>$shop->phone,
        ]);
        // dd($shop);
        // exit;
        // $shop->save();

        if($shop->vendor->userinfo) {
            $userinfo = $shop->vendor->userinfo;
            $userinfo->f_name = $shop->name;
            $userinfo->image = $shop->logo;
            $userinfo->save();
        }

        Toastr::success(translate('messages.Business_Data_Updated'));
    
        
        // return view('vendor-views.shop.shopInfo_mobile');
        return redirect(route('business-view-mobile', compact('rid')));
        // return redirect()->route('vendor.shop.view');
    }
}
