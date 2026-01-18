<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class AdminMiddleware
// {
//     /**
//      * Handle an incoming request.
//      */
//     public function handle(Request $request, Closure $next)
//     {
//         // Cek apakah user sudah login dan role-nya admin
//         if (Auth::check() && Auth::user()->role->name === 'admin') {
//             return $next($request);
//         }

//         // Jika user belum login, redirect ke halaman login
//         if (!Auth::check()) {
//             return redirect()->route('login');
//         }

//         // Jika sudah login tapi bukan admin, tampilkan 403 forbidden
//         abort(403, 'Unauthorized');
//     }
// }
