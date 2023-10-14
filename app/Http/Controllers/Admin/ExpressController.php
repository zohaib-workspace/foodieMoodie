<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Express;

class ExpressController extends Controller
{
    //

    public function express_index(){
        $pickups = Express::with('category')->latest()->paginate(config('default_pagination'));
        return view('admin-views.absher-express.index', compact('pickups'));
    }

    public function express_edit($id){
        $pickup = Express::with('category')->find($id);
        return view('admin-views.absher-express.edit', compact('pickup'));
    }

    public function express_update(Request $req, $id){
        Express::where('id', $id)->update([
            'pickup_name' => $req->pickup_name,
            'pickup_phone' => $req->pickup_phone,
            'pickup_address' => $req->pickup_address,
            'pickup_address_details' => $req->pickup_address_details,
            'dropoff_name' => $req->dropoff_name,
            'dropoff_phone' => $req->dropoff_phone,
            'dropoff_address' => $req->dropoff_address,
            'dropoff_address_details' => $req->dropoff_address_details,
            'description' => $req->description
        ]);
    }
}
