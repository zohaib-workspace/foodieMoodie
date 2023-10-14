<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderReport;
use App\Models\Order;

class OrderReportController extends Controller
{
    public function order_reports_store(Request $req)
    {
        try {
            $orderReport = new OrderReport();
            if (!\App\Models\OrderReport::where('order_id', $req->order_id)->where('status', 'pending')->exists()) {
                $orderReport->order_id = $req->order_id;
                $orderReport->user_id = $req->user_id;
                $orderReport->complain = $req->complain;
                $orderReport->status = 'pending';
                if($orderReport->save()){
                    Order::where('id', $req->order_id)->update(['order_status'=>'disputed']);
                }
            }else{
                throw new \Exception('One report already in pending state');
            }
            
            return _response(1,'Report submitted successfully',['report' => $orderReport],200);

            // return response()->json([
            //     'message' => 'Report submitted successfully',
            //     'orderReport' => $orderReport,
            //     'status' => 200,
            // ]);
        } catch (\Exception $e) {
            
            return _response(0,$e->getMessage(),$orderReport, 200);

            // return response()->json([
            //     'message' => 'Error while submitting order report. Please try again',
            //     'orderReport' => $orderReport,
            //     'status' => 201,
            //     '4'=> $e,
            // ]);   
        }
    }
}
