<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if (auth('admin')->attempt(['email' => "junaidaliansaree@gmail.com", 'password' => "11223344"], "true")) {
        //     // return redirect()->route('admin.dashboard');
        // }

        if (Auth::guard('admin')->check()) {
            return $next($request);
        }
        return redirect()->route('admin.auth.login');
    }
}
