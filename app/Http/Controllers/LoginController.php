<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * @author Kyi Lin Lin Thant
 * @create 26/06/2023
 * @return array
 */
class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $credentials = $request->only('login_id', 'password');

        $fixedAccounts = [
            '00001' => 1,
            '00002' => 2,
        ];

        if (isset($credentials['login_id'])) {

            if (isset($fixedAccounts[$credentials['login_id']]) && $credentials['password'] == '123') {

                // check login inputs condition
                session(['login_id' => $fixedAccounts[$credentials['login_id']]]);
                // redirect to the desired page
                return redirect()->route('employees')->with('success', 'Login successfully!');
            }
        }

        // if authentication failed 
        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    /**
     * Logout the account
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // clear the session and log out
        $request->session()->flush();
        Auth::logout();
        return redirect('/')->with('success', 'Logout successfully!');
    }
}
