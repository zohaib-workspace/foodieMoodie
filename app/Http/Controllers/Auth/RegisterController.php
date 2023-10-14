<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Str; // Import the Str class
class RegisterController extends Controller
{
    public function register()
    {
        return view('Auth.register');
    }

    public function user_register(Request $request)
    {
        // $users = User::all();
        // dd($users);
        // exit;
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:8',
            'password_confirmation' => 'required|same:password',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the image validation rules
        ], [
            'password_confirmation.same' => 'The confirm password and password must match.',
        ]);

        $user = new User();

        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->phone  = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $photo_name = Str::random() . $file->getClientOriginalName();
        //     $file->move('user/assets/img/category', $photo_name);
        //     $user->image = $photo_name;
        // }

        $result = $user->save();
        // dd($result);
        // exit;
        if ($result) {
            return redirect('/user/login')->with('success', 'You have registered successfully');
        } else {
            dd("Data was not saved"); // Add this line for debugging
            return back()->with('fail', 'Something went wrong');
            // return back()->with('fail', 'Something went wrong');
        }
    }


    // public function user_register(Request $request)
    // {

    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:3|max:8',
    //          'confirm_password' => 'required|same:password',
    //             ], [
    //                 'confirm_password.same' => 'The confirm password and password must match.',
    //             ]);



    //    $user = new User();

    //    $user->f_name = $request->f_name;
    //    $user->l_name = $request->l_name;
    //    $user->phone  = $request->phone;
    //    $user->email = $request->email;
    //    $user->password = Hash::make($request->password);

    //    if ($request->hasFile('image')) {
    //     $file = $request->file('image');
    //     $photo_name = Str::random() . $file->getClientOriginalName();
    //     $file->move('public/assets/img/user', $photo_name);
    //     $user->image = $photo_name;
    //  }

    //    $result = $user->save();
    //    if($result){
    //        // return back()->with('success', 'You have register successfully');
    //        return redirect('/login')->with('success', 'You have Register successfuly');
    //    }else{
    //        return back()->with('fail', 'something went wrong');
    //    }

    //     // return redirect->route('/login');
    // }
}
