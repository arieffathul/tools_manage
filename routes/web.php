<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Form\BorrowController;
use App\Http\Controllers\Form\BorrowReturnController;
use App\Http\Controllers\Form\BrokenToolsController;
use App\Http\Controllers\Master\EngineerController;
use App\Http\Controllers\Master\ToolController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
// Auth
// Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
// Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class,  'show'])->name('login.show');
Route::post('/login', [LoginController::class,  'login'])->name('login');
Route::post('/logout', [LoginController::class,  'logout'])->name('logout');

Route::middleware('admin')->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola engineer
    Route::resource('engineer', EngineerController::class);
    Route::patch('engineer/{id}/inactive', [EngineerController::class, 'inactive'])->name('engineer.inactive');
    Route::patch('/engineer/{id}/activate', [EngineerController::class, 'activate'])
        ->name('engineer.activate');

    // Kelola tool
    Route::resource('tool', ToolController::class);

    // Kelola borrow
    Route::get('borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::patch('borrow/{id}/complete', [BorrowController::class, 'complete'])->name('borrow.complete');

    // return management
    Route::get('return', [BorrowReturnController::class, 'index'])->name('return.index');
});

// form borrow
Route::get('form/borrow', [BorrowController::class, 'form'])->name('borrow.form');
Route::post('form/borrow/submit', [BorrowController::class, 'store'])->name('borrow.store');

// form complete
Route::get('form/complete', function () {
    return view('forms.complete');
})->name('forms.complete');

// form return
Route::get('form/select-return', [BorrowReturnController::class, 'create'])
    ->name('borrowReturn.select');
Route::get('form/return', [BorrowReturnController::class, 'form'])
    ->name('return.form');
Route::post('form/return/submit', [BorrowReturnController::class, 'store'])
    ->name('return.store');

// form broken tools
Route::get('form/select-broken', [BrokenToolsController::class, 'select'])
    ->name('broken.select');
Route::get('form/broken', [BrokenToolsController::class, 'create'])->name('broken.form');
Route::post('form/broken/submit', [BrokenToolsController::class, 'store'])->name('broken.store');
Route::get('form/broken/{id}/edit', [BrokenToolsController::class, 'edit'])->name('broken.edit');
Route::put('form/broken/{id}/update', [BrokenToolsController::class, 'update'])->name('broken.update');
