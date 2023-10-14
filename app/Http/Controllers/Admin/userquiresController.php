<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\UserQuery;
use App\Models\RiderQuery;
use App\Http\Controllers\Controller;

use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class userquiresController extends Controller
{
    public function list(){
        $orderReports = UserQuery::with(['user'])->paginate(20);
        return view('admin-views.query.user-query', compact('orderReports'));
    }

    public function admin_resp_to_order_report(Request $req, $id){



        // print_r('yes');
        // exit;
        try{
            UserQuery::where('id', $id)->
                    update([
                        'response'=> $req->response,
                        'status' => $req->status,
                    ]);
            Toastr::success(translate('messages.response_to_rider_Query_recorded'));
            return redirect(route('admin.userquires.list'));
            
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect(route('admin.userquires.list'));
        }
        
    }



    public function rider_list(){
        $riderReports = RiderQuery::with(['rider'])->paginate(20);
        return view('admin-views.query.rider-query', compact('riderReports'));
    }

    public function admin_resp_to_rider_query(Request $req, $id){



        // print_r('yes');
        // exit;
        try{
            RiderQuery::where('id', $id)->
                    update([
                        'response'=> $req->response,
                        'status' => $req->status,
                    ]);
            Toastr::success(translate('messages.response_to_rider_Query_recorded'));
            return redirect(route('admin.userquires.rider_list'));
            
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect(route('admin.userquires.rider_list'));
        }
        
    }
}
