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
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $userStatus = Auth::user()->status;

            if ($userStatus == 'submitted') {
                return back()->withErrors(['email' => 'Akun anda belum aktif, silahkan hubungi admin!']);
            } else if ($userStatus == 'rejected') {
                return back()->withErrors(['email' => 'Akun anda telah ditolak oleh admin']);
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Terjadi kesalahan email atau password anda',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function registerView(){
        return view('pages.auth.register');
    }

    public function register(Request $request){
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required','email'],
            'password'=> ['required'],
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role_id = 2;
        $user->saveOrFail();

        return redirect('/login')->with('success','Berhasil mendaftar akun, menunggu persetujuan admin');

    }
}
