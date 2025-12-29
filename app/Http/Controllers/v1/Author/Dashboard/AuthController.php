<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signIn()
    {
        if (Auth::check()){
            return redirect()->route('admins.list');
        }
        return view('pages.static-sign-in');
    }

    public function AuthSignIn(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password') , $request->filled('remember'))){
            return $this->check_user_type();
        }
        else{
            return redirect()->back()->with('error', 'Invalid Credentials');
        }

    }

    public function signOut()
    {
        Auth::logout();
        return redirect()->route('admins.signIn');
    }

    public function check_user_type()
    {
        if (Auth::check()){
            if (Auth::user()->role_id == '1') {
                return redirect()->route('admins.list');
            }
            else {
                Auth::logout();
                return redirect()->route('admins.signIn')->with('error', 'Access Denied');
            }
        }
        else {
            return redirect()->route('admins.signIn');
        }
    }
}
