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

            $userStatus = Auth::user()->status;

            if ($userStatus == 'submitted') {
                // $this->_logout($request);
                return back()->withErrors(['email' => 'Akun anda belum aktif, silahkan hubungi admin!']);
            } else if ($userStatus == 'rejected') {
                // $this->_logout($request);
                return back()->withErrors(['email' => 'Akun anda telah ditolak oleh admin']);
            }

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

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role_id = 2;
        $user->saveOrFail();

        return redirect('/register')->with('success', 'Berhasil mendaftar akun, menunggu persetujuan admin');
    }
}
