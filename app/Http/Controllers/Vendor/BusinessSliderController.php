<?php

namespace App\Http\Controllers\Vendor;

use App\Models\BusinessSlider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Restaurant;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use App\Models\Translation;
use Illuminate\Support\Facades\Auth;

class BusinessSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index()
    {
        return view('vendor-views.business_slider.index');
    }

    public function list()
    {
        $business = BusinessSlider::latest()->paginate(config('default_pagination'));
        return view('vendor-views.business_slider.list' , compact('business'));

    }
    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description'=> 'required',
        ]);

        
        // return response()->json(['errors' => Helpers::error_processor($validator)]);
        // if ($validator->fails()) {
        // } 
       $id= Helpers::get_restaurant_id();

        $business = new BusinessSlider;
        $business->title = $request->title;
        $business->description = $request->description;
        $business->business_id = $id;
        $business->image = Helpers::upload('campaign/', 'png', $request->file('image'));
        $business->save();
        Toastr::success(translate('messages.business_Slider_added_successfully'));
        return back();
    }
    

    
    public function status(Request $request)
    {
        $business = BusinessSlider::find($request->id);
        $business->status = $request->status;
        // print_r( $business->status);
        // exit;
        $business->save();
        Toastr::success(translate('messages.Business Slider Status Updated'));
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $business=BusinessSlider::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
       
        return response()->json([
            'view'=>view('vendor-views.business_slider.partials._table',compact('business'))->render(),
            'count'=>$business->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BusinessSlider  $businessSlider
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BusinessSlider  $businessSlider
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessSlider $businessSlider)
    {
        return view('vendor-views.business_slider.edit', compact('businessSlider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // print_r($id);
        // exit;
        $validator = Validator::make($request->all(),[
            'title' => 'require',
            'description' => 'required'
        ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => Helpers::error_processor($validator)]);
        // }
        $business_id= Helpers::get_restaurant_id();
 $businessSlider=BusinessSlider::findOrFail($id);
        $businessSlider->business_id = $business_id;
        $businessSlider->title = $request->title;
        $businessSlider->description = $request->description;
        $businessSlider->image = $request->has('image') ? Helpers::update('campaign/', $businessSlider->image, 'png', $request->file('image')) : $businessSlider->image;;
        $businessSlider->save();

       
        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessSlider  $businessSlider
     * @return \Illuminate\Http\Response
     */
    public function delete(BusinessSlider $businessSlider)

    {
       
        $businessSlider->delete();
        Toastr::success(translate('messages.Business Slider_deleted_successfully'));
        return back();
    }
}
