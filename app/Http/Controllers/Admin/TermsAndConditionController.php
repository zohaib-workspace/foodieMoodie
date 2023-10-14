<?php

namespace App\Http\Controllers\Admin;

use App\Models\terms_and_condition;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Scopes\RestaurantScope;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\ProductLogic;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $terms = terms_and_condition::latest()->paginate(config('default_pagination'));
        return view('admin-views.about.index', compact('terms'));
    }


    public function list()
    {
        // $terms = terms_and_condition::latest()->paginate(config('default_pagination'));
        $terms_and_condition = terms_and_condition::where("status",'1')->first();

        return view('admin-views.terms_and_con.list', compact('terms_and_condition'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show(){
        $terms_and_condition = terms_and_condition::where("status",'1')->first();
        return view('terms-and-conditions', compact('terms_and_condition'));
    }
    public function create()
    {
        return view('admin-views.terms_and_con.add_new');

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
    
             $terms = new terms_and_condition;
            $terms->image = Helpers::upload('terms_and_con/', 'png', $request->file('image'));
            $terms->title = $request->title;
            $terms->description = $request->description;
            $terms->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\terms_and_condition  $terms_and_condition
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $about = terms_and_condition::find($request->id);
        $about->status = $request->status;
        // print_r( $business->status);
        // exit;
        $about->save();
        Toastr::success(translate('messages.Terms And Conditions Status Updated'));
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\terms_and_condition  $terms_and_condition
     * @return \Illuminate\Http\Response
     */  public function edit($id)
    {
        $terms_and_condition=terms_and_condition::selectRaw("*")->findOrFail($id);
        // print_r($terms_and_condition);
        // exit;
        return view('admin-views.terms_and_con.edit', compact('terms_and_condition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\terms_and_condition  $terms_and_condition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        // print_r( $request->description);
        // exit;
        $terms_and_condition=terms_and_condition::findOrFail($id);
        $terms_and_condition->title = $request->title;
        $terms_and_condition->description = $request->description;
        $terms_and_condition->image = $request->has('image') ? Helpers::update('terms_and_con/', $terms_and_condition->image, 'png', $request->file('image')) : $terms_and_condition->image;;
        $terms_and_condition->save();

        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\terms_and_condition  $terms_and_condition
     * @return \Illuminate\Http\Response
     */
    public function delete(terms_and_condition $terms_and_condition)
    {
        // print_r($terms_and_condition);
        // exit;
        $terms_and_condition->delete();
        Toastr::success(translate('messages.terms_and_condition_deleted_successfully'));
        return back();
    }
}
