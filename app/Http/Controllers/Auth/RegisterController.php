<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use App\Models\Regular;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;      // ← import Facade Auth
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $req)
    {
        $data = $req->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => ['required', 'email', 'max:255',
                'regex:/^[\w.+-]+@puredc\.com$/i',
                'unique:users,email'],
            'password' => 'required|string|min:8|confirmed',
        ], ['email.regex' => 'Gunakan akun email dari Pure DC']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // 'role_id' => $role->id,
        ]);

        // tidak ke reguler
        // Regular::create(['user_id' => $user->id]);

        Auth::login($user);

        return redirect()->route('login.show')->with('success', 'Pendaftaran berhasil! Silahkan Login '.$user->name);
    }
}
