<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            activity('user_login')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'time' => now()->toDateTimeString(),
            ])
            ->log('User logged in');
            return redirect()->route('dashboard');
        } else {
            Alert::error('Oops!', 'Wrong email or password');
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        activity('user_logout')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'time' => now()->toDateTimeString(),
            ])
            ->log('User logged out');
        Auth::logout();
        return redirect()->route('login');
    }
}
