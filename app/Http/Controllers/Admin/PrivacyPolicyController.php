<?php

namespace App\Http\Controllers\Admin;

use App\Models\privacy_policy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Scopes\RestaurantScope;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\ProductLogic;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PrivacyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        // $privacy_policy = privacy_policy::latest()->paginate(config('default_pagination'));
        $privacy_policy = privacy_policy::where("status",'1')->first();
        return view('admin-views.privacy_policy.list', compact('privacy_policy'));
    }

     public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-views.privacy_policy.add_new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
           ]);
           if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
    
            $privacy_policy = new privacy_policy;
            $privacy_policy->image = Helpers::upload('privacy_policy/', 'png', $request->file('image'));
            $privacy_policy->title = $request->title;
            $privacy_policy->description = $request->description;
            $privacy_policy->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\privacy_policy  $privacy_policy
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $privacy_policy = privacy_policy::find($request->id);
        $privacy_policy->status = $request->status;
        // print_r( $business->status);
        // exit;
        $privacy_policy->save();
        Toastr::success(translate('messages.privacy_policy Status Updated'));
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\privacy_policy  $privacy_policy
     * @return \Illuminate\Http\Response
     */
    public function show(){
        $privacy_policy = privacy_policy::where("status",'1')->first();
        // dd($privacy_policy);
        // exit;
        return view('privacy-policy', compact('privacy_policy'));
    }
     
    public function edit(privacy_policy $privacy_policy)
    {
        return view('admin-views.privacy_policy.edit', compact('privacy_policy'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\privacy_policy  $privacy_policy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, privacy_policy $privacy_policy)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $privacy_policy->title = $request->title;
        $privacy_policy->description = $request->description;
        $privacy_policy->image = $request->has('image') ? Helpers::update('privacy_policy/', $privacy_policy->image, 'png', $request->file('image')) : $privacy_policy->image;
        $privacy_policy->save();

        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\privacy_policy  $privacy_policy
     * @return \Illuminate\Http\Response
     */
    public function delete(privacy_policy $privacy_policy)
    {
        $privacy_policy->delete();
        Toastr::success(translate('messages.privacy_policy_deleted_successfully'));
        return back();
    }
}
