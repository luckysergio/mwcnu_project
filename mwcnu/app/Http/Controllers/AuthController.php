<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login()
    {
        // if(Auth::check()){
        //     return back();
        // }

        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {

        // if (Auth::check()) {
        //     return back();
        // }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/')->with('success', 'Berhasil login, Selamat datang kembali');
        }

        return back()->withErrors([
            'email' => 'Periksa kembali email dan password anda',
        ])->onlyInput('email');
    }


    public function _logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function logout(Request $request)
    {
        // if  (!Auth::check()) {
        //     return redirect('/');
        // }

        $this->_logout($request);

        return redirect('/');
    }

    public function registerView()
    {

        // if (Auth::check()) {
        //     return back();
        // }

        return view('pages.auth.register');
    }

    
}
