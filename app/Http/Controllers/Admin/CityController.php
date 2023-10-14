<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\city;
use App\Models\country;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Rap2hpoutre\FastExcel\FastExcel;

class CityController extends Controller
{
    public function index()
    {
        $city = city::all();
        $country= country::with('country');
        return view('admin-views.city.index', compact('city','country'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'provence' => 'required',
            'country_id' => 'required',
        ]);

        $city = new city();
        $city->name = $request->name;
        $city->provence = $request->provence;
        $city->country_id = $request->country_id;
        $city->save();
        Toastr::success(translate('messages.city_added_successfully'));
        return back();
    }
    public function edit($id)
    {
        $city=city::selectRaw("*")->findOrFail($id);
        return view('admin-views.city.edit', compact('city'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:191',
            'provence' => 'required',
            'country_id' => 'required',
        ]);
        $city=city::findOrFail($id);
        $city->name = $request->name;
        $city->provence = $request->provence;
        $city->country_id = $request->country_id;
        $city->save();
        Toastr::success(translate('messages.city_updated_successfully'));
        return redirect()->route('admin.city.home');
    }
    public function destroy(city $city)
    {
    
        $city->delete();
        Toastr::success(translate('messages.city_deleted_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        if(env('APP_MODE')=='demo' && $request->id == 1)
        {
            Toastr::warning('Sorry!You can not inactive this city!');
            return back();
        }
        $city = city::findOrFail($request->id);
        $city->status = $request->status;
        $city->save();
        Toastr::success(translate('messages.city_status_updated'));
        return back();
    }
    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $cities=City::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.city.partials._table',compact('cities'))->render(),
            'total'=>$cities->count()
        ]);
    }
}
