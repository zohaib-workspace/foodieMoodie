<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // return Auth()->user();
        $notifications=UserNotification::where('user_id',Auth()->id())->latest()->get();
        return view('home.notification.index', compact('notifications'));
    }
}
