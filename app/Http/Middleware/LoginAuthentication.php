<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
* @author Kyi Lin Lin Thant
* @create 26/06/2023
* @return mixed
*/
class LoginAuthentication
{
    /**
     * Handle an incoming request.
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('login_id')) {
            // manually authenticate the user based on the stored employee_id
            $loginId = session('login_id');
            Auth::loginUsingId($loginId);

            // user is authenticated, continue with the request
            return $next($request);
        }

        // user is not authenticated, redirect to the login page
        return redirect('/')->with('error', 'Please log in.');
    }
}

