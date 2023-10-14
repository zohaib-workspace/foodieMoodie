<?php

namespace App\Http\Controllers\Admin;

use App\Models\Query;
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

class querycontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Query::where('parent_id','0')->get();
        // print_r($query);
        // exit;
        return view('admin-views.query.list', compact('query'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $query = new Query();
        $query->name = $request->name;
        $query->description = $request->description;
        $query->parent_id = 0;
        $query->level = 0;
        $query->role = $request->role;
        $query->status = 1;
        $query->save();
        Toastr::success(translate('messages.query_added_successfully'));
        return back();
    }
    public function edit($id)
    {
        $query=Query::selectRaw("*")->findOrFail($id);
        // dd($query->coordinates);
        return view('admin-views.query.edit', compact('query'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $query=Query::findOrFail($id);
        $query->name = $request->name;
        $query->role = $request->role;
        $query->description = $request->description;
        $query->save();
        Toastr::success(translate('messages.query_updated_successfully'));
        return redirect()->route('admin.query.home');
    }
    public function status(Request $request)
    {
        $query = Query::find($request->id);
        $query->status = $request->status;
        // print_r( $business->status);
        // exit;
        $query->save();
        Toastr::success(translate('messages.Query Status Updated'));
        return back();
    }
    public function destroy(Query $query)
    {
        $query->delete();
        Toastr::success(translate('messages.Query_deleted_successfully'));
        return back();
    }
// crud of sub-query
public function add_sub()
{
    $sub_query_1 = Query::where('level','1')->get(); 
    // print_r($sub_query_1);
    // exit;
    return view('admin-views.query.add_sub', compact('sub_query_1'));
}

public function sub_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'parent_id' => 'required',
        ]);
        $query = new Query();
        $query->name = $request->name;
        $query->description = $request->description;
        $query->parent_id =$request->parent_id;
        $query->level = 1;
        $query->role = $request->role;
        $query->status = 1;
        $query->save();
        Toastr::success(translate('messages.query_added_successfully'));
        return back();
    }
    
    public function sub_edit($id)
    {
        $query=Query::selectRaw("*")->findOrFail($id);
        // dd($query->coordinates);
        return view('admin-views.query.edit_sub_query', compact('query'));
    }

    public function sub_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $query=Query::findOrFail($id);
        $query->name = $request->name;
        $query->parent_id = $request->parent_id;
        $query->description = $request->description;
        $query->role = $request->role;
        $query->save();
        Toastr::success(translate('messages.query_updated_successfully'));
        return redirect()->route('admin.query.add_sub');
    }


}
