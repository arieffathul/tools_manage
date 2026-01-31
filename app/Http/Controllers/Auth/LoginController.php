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
            'name' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::attempt($creds, $req->filled('remember'))) {
            // Ambil user yang sudah login
            $user = Auth::user();

            $req->session()->put('user_name', $user->name);

            // Catat aktivitas login
            // activity()
            //     ->causedBy($user)
            //     ->event('login')
            //     ->log('Pengguna '.$user->name.' melakukan Login');

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['name' => 'Username atau password salah'])
            ->onlyInput('name');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login.show');
    }
}
