<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\country;
use App\Models\Timezone;
use Brian2694\Toastr\Facades\Toastr;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use App\CentralLogics\Helpers;
use Rap2hpoutre\FastExcel\FastExcel;

class CountryController extends Controller
{
    //
    public function index()
    {
        $country = country::all();
         $timezone= Timezone::with('timezone');
        return view('admin-views.country.index', compact('country','timezone'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'sortname' => 'required',
            'phonecode' => 'required',
            'timezone_id' =>'required',
        ]);

        $country = new country();
        $country->name = $request->name;
        $country->sortname = $request->sortname;
        $country->phonecode = $request->phonecode;
        $country->timezone_id = $request->timezone_id;
        $country->save();
        Toastr::success(translate('messages.country_added_successfully'));
        return back();
    }
    public function edit($id)
    {
        $country=country::selectRaw("*")->findOrFail($id);
        // dd($country->coordinates);
        return view('admin-views.country.edit', compact('country'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:191',
            'sortname' => 'required',
            'phonecode' => 'required',
        ]);
        $country=country::findOrFail($id);
        $country->name = $request->name;
        $country->sortname = $request->sortname;
        $country->phonecode = $request->phonecode;
        $country->timezone_id = $request->timezone_id;
        $country->save();
        Toastr::success(translate('messages.country_updated_successfully'));
        return redirect()->route('admin.country.home');
    }
    public function destroy(country $country)
    {
    
        $country->delete();
        Toastr::success(translate('messages.country_deleted_successfully'));
        return back();
    }
    public function status(Request $request)
    {
        if(env('APP_MODE')=='demo' && $request->id == 1)
        {
            Toastr::warning('Sorry!You can not inactive this country!');
            return back();
        }
        $country = country::findOrFail($request->id);
        $country->status = $request->status;
        $country->save();
        Toastr::success(translate('messages.country_status_updated'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $countries=country::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.country.partials._table',compact('countries'))->render(),
            'total'=>$countries->count()
        ]);
    }
    public function country_filter($id)
    {
        if($id == 'all')
        {
            if(session()->has('country_id')){
                session()->forget('country_id');
            }
        }
        else{
            session()->put('country_id', $id);
        }

        return back();
    }

}
