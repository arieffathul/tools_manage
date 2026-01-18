<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::redirect('/', '/login');
# Auth
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login',    [LoginController::class,  'show'])->name('login.show');
Route::post('/login',    [LoginController::class,  'login'])->name('login');
Route::post('/logout',   [LoginController::class,  'logout'])->name('logout');
