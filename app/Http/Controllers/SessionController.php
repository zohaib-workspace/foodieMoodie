<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $data=Session()->all();
        if($data)
        {
          

            $data=['searched_name'=>$data['searched_name'],'zone_id'=>$data['zone_id'],'s_zone_id'=>$data['s_zone_id'],'lat'=>$data['lat'],'lng'=>$data['lng']];
            return _response(1,'sucess',$data,200);
            // return to detail-restauratn 
        }
        return _response(0,'failed',$data);
    }
    public function store(Request $request)
    {
        $zone_id = json_decode($request->zone_id);
        $s_zone_id = reset($zone_id);
       $data= request()->session()->put(['zone_id'=> $zone_id,'s_zone_id'=> $s_zone_id, 'searched_name'=>$request->searched_name,'lat'=>$request->lat,'lng'=>$request->lng]);
    //    return session()->all();
        return _response(1,'sucess',$data,200);
    }
}
