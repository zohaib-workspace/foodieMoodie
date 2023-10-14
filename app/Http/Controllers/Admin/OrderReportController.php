<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderReport;
use Brian2694\Toastr\Facades\Toastr;

class OrderReportController extends Controller
{
    //

    public function order_reports_index()
    {
        $orderReports = OrderReport::paginate(20);
        return view('admin-views.order-reports.order-reports-view', compact('orderReports'));
    }

    public function admin_resp_to_order_report(Request $req, $id){

        try{
            OrderReport::where('id', $id)->
                    update([
                        'response'=> $req->response,
                        'status' => $req->status,
                    ]);
            Toastr::success(translate('messages.response_to_order_report_recorded'));
            return redirect(route('admin.report.order-reports-index'));
            
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect(route('admin.report.order-reports-index'));
        }
        
    }

    
}
