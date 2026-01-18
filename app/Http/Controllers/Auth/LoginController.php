<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $req)
    {
        $creds = $req->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($creds, $req->filled('remember'))) {
            // Ambil user yang sudah login
            $user = Auth::user();

            // Simpan email user ke session
            $req->session()->put('user_email', $user->email);

            // Catat aktivitas login
            activity()
                ->causedBy($user)
                ->event('login')
                ->log('Pengguna ' . $user->name . ' melakukan Login');

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Login gagal.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.show');
    }

}
