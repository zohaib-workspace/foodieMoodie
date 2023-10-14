<?php

namespace App\Http\Controllers\Admin;

use App\Models\about;
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

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //$about = About::latest()->paginate(config('default_pagination'));
        $about = About::where("status",'=','1')->first();    
        return view('admin-views.about.list', compact('about'));
    }

public function show(){
    $about = About::where("status",'=','1')->first();    
    $content = $about['description'];
    $text = strip_tags($content);
    // print_r($text);
    // exit;
    
    //return redirect()->back();
    return view('about-us', compact('about'));
}

public function index(){
    $about = About::latest()->paginate(config('default_pagination'));
    return view('admin-views.about.index', compact('about'));
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-views.about.add_new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
       // echo "store";
       $validator = Validator::make($request->all(), [
        'name' => 'required',
        'description' => 'required',
       ]);
       if ($validator->fails()) {
        return response()->json(['errors' => Helpers::error_processor($validator)]);
    }

         $about = new About;
        $about->image = Helpers::upload('about_us/', 'png', $request->file('image'));
        $about->name = $request->name;
        $about->description = $request->description;
        $about->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\about  $about
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $about = About::find($request->id);
        $about->status = $request->status;
        // print_r( $business->status);
        // exit;
        $about->save();
        Toastr::success(translate('messages.About Us Status Updated'));
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\about  $about
     * @return \Illuminate\Http\Response
     */
    public function edit(about $about)
    {
        return view('admin-views.about.edit', compact('about'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\about  $about
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, about $about)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $about->name = $request->name;
        $about->description = $request->description;
        $about->image = $request->has('image') ? Helpers::update('about_us/', $about->image, 'png', $request->file('image')) : $about->image;;
        $about->save();

        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\about  $about
     * @return \Illuminate\Http\Response
     */
    public function delete(About $about)

    {
       
        $about->delete();
        Toastr::success(translate('messages.about_deleted_successfully'));
        return back();
    }
}
