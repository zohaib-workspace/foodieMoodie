<?php

namespace App\Http\Controllers\Api\V1;


use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Query;
use App\Models\UserQuery;
use App\Models\VendorQuery;
use App\Models\RiderQuery;

class QueryController extends Controller
{
    
    public function get_all_queries(Request $request)
    {$role = $request->input('role');

        $queries['data'] = Query::where('role', $role)
            ->get();

        return _response(1,'success',$queries, 200);
    }
    
    public function get_queries(Request $request)
    {
        $role = $request->input('role');
        $parent_id = $request->input('parent_id')??0;

        $queries['data'] = Query::active()->where('role', $role)->where('parent_id', $parent_id)->withCount('children')
            ->get();

        return _response(1,'success',$queries, 200);
    }
    
    public function send_request(Request $request)
{
    // return 'here';
    
    try{
        $validatedData = $request->validate([
        'name' => 'required|string',
        'query_id' => 'required|string',
        'description' => 'nullable|string',
        'images' => 'nullable|array',
        'user_id' => 'required|integer',
    ]);
    
     $userQuery = new UserQuery();
    $userQuery->name = $validatedData['name'];
    $userQuery->description = $validatedData['description'];
    if(!empty($validatedData['images'])){
    $userQuery->images = json_encode($validatedData['images']);    
    }
    
    $userQuery->user_id = $validatedData['user_id'];
    $userQuery->query_id = $validatedData['query_id'];
    if($userQuery->save()){
        return _response(1,'Successfully submitted',['data' => $userQuery,'message'=>"Successfully submitted"], 200);
    }else{
        return _response(2,'Unable to submit','', 200);
    }
    
    }catch(\Exception $e){
        return $e;
        // return _response(2,'Unable to submit, invalid data',['errors' => $e->errors()], 200);
    }
    
}
 public function get_all_user_queries(Request $request)
    {   $user_id = $request->input('user_id');

        $queries['data'] = UserQuery::where('user_id', $user_id)
            ->get();

        return _response(1,'success',$queries, 200);
    }
    
    public function get_user_queries(Request $request)
    {
        
        $user_id = $request->input('user_id')??0;

        $queries['data'] = UserQuery::active()->where('user_id', $user_id)->withCount('children')
            ->get();

        return _response(1,'success',$queries, 200);
    }
    
    
    
    public function get_all_rider_queries(Request $request)
    
    {   
        
    
        $rider_id = $request->input('rider_id');
    
        $queries['data'] = RiderQuery::where('rider_id', $rider_id)
            ->get();
    
        return _response(1,'success',$queries, 200);
    }
    
    public function get_all_vendor_queries(Request $request){
        $vendor_id = $request->input('vendor_id');
        $queries['data'] = VendorQuery::where('vendor_id', $vendor_id)
            ->get();
    
        return _response(1,'success',$queries, 200);
    }

public function get_rider_queries(Request $request)
{
    
    $rider_id = $request->input('rider_id')??0;

    $queries['data'] = UserQuery::active()->where('rider_id', $rider_id)->withCount('children')
        ->get();
    return _response(1,'success',$queries, 200);
}
public function send_rider_request(Request $request)
{
// return 'here';
try{
    $validatedData = $request->validate([
    'name' => 'required|string',
    'query_id' => 'required|string',
    'description' => 'nullable|string',
    'images' => 'nullable|array',
    'rider_id' => 'required|integer',
]);
  $userQuery = new RiderQuery();
$userQuery->name = $validatedData['name'];
$userQuery->description = $validatedData['description'];
if(!empty($validatedData['images'])){
$userQuery->images = json_encode($validatedData['images']);    
}
$userQuery->rider_id = $validatedData['rider_id'];
$userQuery->query_id = $validatedData['query_id'];
if($userQuery->save()){
    return _response(1,'Successfully submitted',['data' => $userQuery], 200);
}else{
    return _response(2,'Unable to submit','', 200);
}
}catch(\Exception $e){
    //return $e;
    return _response(2,'Unable to submit, invalid data',['errors' => $e->errors()], 200);
}
}



public function send_vendor_request(Request $request){

    try{
        $validatedData = $request->validate([
        'name' => 'required|string',
        'query_id' => 'required|string',
        'description' => 'nullable|string',
        'images' => 'nullable|array',
        'vendor_id' => 'required|integer',
    ]);
        $userQuery = new VendorQuery();
        $userQuery->name = $validatedData['name'];
        $userQuery->description = $validatedData['description'];
        if(!empty($validatedData['images'])){
        $userQuery->images = json_encode($validatedData['images']);    
        }
        $userQuery->vendor_id = $validatedData['vendor_id'];
        $userQuery->query_id = $validatedData['query_id'];
        if($userQuery->save()){
            return _response(1,'Successfully submitted',['data' => $userQuery], 200);
        }else{
            return _response(2,'Unable to submit','', 200);
        }
        }catch(\Exception $e){
            //return $e;
            return _response(2,'Unable to submit, invalid data',['errors' => $e], 200);
        }
    }

}