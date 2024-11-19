<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

//auth
Route::get('/login', LoginController::class)->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('submitLogin');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/users', [Usercontroller::class, 'index'])->name('users.index');
        Route::get('/users/create', [Usercontroller::class, 'create'])->name('users.create');
        Route::post('/users', [Usercontroller::class, 'store'])->name('users.store');

        Route::delete('/users/{user}', [Usercontroller::class, 'destroy'])->name('users.destroy');
        //change user status
        Route::get('/users/{user}/is_active', [Usercontroller::class, 'is_active'])->name('users.is_active');
    });
});
