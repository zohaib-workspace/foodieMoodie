<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class RestaurentController extends Controller
{
    public $web_url;
    public function __construct()
    {
        $this->web_url = 'http://127.0.0.1:8000/';
    }

    public function restaurent_details($request , $id){
        $response = Http::get($this->web_url.'restaurants/details/1');
        if ($response->successful()) {
        $response = $response->json(); // Get the response data

        }
    }
}
