<?php

namespace App\Services;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Database;
use Kreait\Firebase\Factory;

class FirebaseService
{
    public static function connect()
    {
        // echo base_path();
        // exit;
        // return $database;
        
        $firebase = (new Factory)
            // ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));

        return $firebase->createDatabase();
    }
    public static function setRiderOrder($orderId, $riderId){
        
        $database = static::connect();
         
         $ref = $database->getReference('riders/'.$riderId.'/new_order');
    
        $ref->set(['order_id'=> $orderId, 'timestamp' => now(),'check'=>false,'status'=>"Pending"]);
        self::setOrderStatus($orderId, "Confirmed");
    }
    ///change order status:
    public static function setOrderStatus($orderId, $status){
        
        $database = static::connect();
         
         $ref = $database->getReference('orders/'.$orderId);
    
        $ref->set(['status'=> $status], ['merge' => true]);
    }
    public static function setOrderStatusVendor($rid,$orderId, $status){
        
        $database = static::connect();
         
         $ref = $database->getReference('vendor_orders/'.$rid.'/'.$orderId);
    
        $ref->set(['status'=> $status], ['merge' => true]);
    }
    // change order status for vendor
    // notifyAdmin
     public static function notifyAdmin($orderId){
        $database = static::connect();
         $ref = $database->getReference('admin/pending_orders');
    
        $ref->set(['order_id'=> $orderId, 'timestamp' => now()]);
    }
    
    public static function notifyTemp($data){
        $database = static::connect();
         $ref = $database->getReference('temp');
    
        $ref->set($data);
    }
    
    public static function getData(){
         $database = static::connect();
         
         $ref = $database->getReference('temp');
         return $ref->getSnapshot()->getValue();
    }
}