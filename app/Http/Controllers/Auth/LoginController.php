<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->session()->get('loginId')) {
            return view('home.index');
        }
        return view('Auth.login');
    }

    public function user_login(Request $request)
    {

        $request->validate([

            'email' => 'required|email',
            'password' => 'required|min:5|max:9'
        ]);

        $user = User::Where('email', '=', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                Auth()->login($user);
                $token = auth()->user()->createToken('RestaurantCustomerAuth')->accessToken;
                $request->session()->put(['loginId'=> $user->id, 'token'=>$token]);
                // return ;
                return redirect('/user');
            } else {
                return back()->with('fail', 'password not matched');
            }
        } else {

            return back()->withErrors([
                'email' => 'Invalid credentials',
            ]);
        }
    }

    public function logout(Request $request)
    {
        if (Session::has('loginId')) {
            Session::pull('loginId');
            Auth()->logout();
            return redirect('user/login');
        }
    }

}