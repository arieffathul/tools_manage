<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Form\BorrowController;
use App\Http\Controllers\Master\EngineerController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
// Auth
// Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
// Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class,  'show'])->name('login.show');
Route::post('/login', [LoginController::class,  'login'])->name('login');
Route::post('/logout', [LoginController::class,  'logout'])->name('logout');

Route::middleware('admin')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('engineer', EngineerController::class);
    Route::patch('engineer/{id}/inactive', [EngineerController::class, 'inactive'])->name('engineer.inactive');
    Route::patch('/engineer/{id}/activate', [EngineerController::class, 'activate'])
        ->name('engineer.activate');
    Route::resource('tool', App\Http\Controllers\Master\ToolController::class);
    Route::resource('borrow', BorrowController::class);
    Route::patch('borrow/{id}/complete', [BorrowController::class, 'complete'])->name('borrow.complete');
});

Route::get('form/borrow', [BorrowController::class, 'form'])->name('borrow.form');
Route::post('form/borrow/submit', [BorrowController::class, 'store'])->name('borrow.store');
Route::get('form/complete', function () {
    return view('forms.complete');
})->name('forms.complete');
