<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RequestOrderController;

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
        //user
        Route::get('/users', [Usercontroller::class, 'index'])->name('users.index');
        Route::get('/users/create', [Usercontroller::class, 'create'])->name('users.create');
        Route::post('/users', [Usercontroller::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [Usercontroller::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [Usercontroller::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [Usercontroller::class, 'destroy'])->name('users.destroy');
        //change user status
        Route::get('/users/{user}/is_active', [Usercontroller::class, 'is_active'])->name('users.is_active');

        //role
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    });

    Route::middleware('role:Super Admin|Admin Warehouse')->group(function () {
        //warehouse
        Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
        Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
        Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
        Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
        Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
        Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');

        //categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        //goods
        Route::get('/goods', [GoodsController::class, 'index'])->name('goods.index');
        Route::get('/goods/create', [GoodsController::class, 'create'])->name('goods.create');
        Route::post('/goods', [GoodsController::class, 'store'])->name('goods.store');
        Route::get('/goods/{goods}/edit', [GoodsController::class, 'edit'])->name('goods.edit');
        Route::put('/goods/{goods}', [GoodsController::class, 'update'])->name('goods.update');
        Route::delete('/goods/{goods}', [GoodsController::class, 'destroy'])->name('goods.destroy');

        //vendors
        Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
        Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendors.create');
        Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');
        Route::get('/vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
        Route::put('/vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
        Route::delete('/vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    });

    Route::middleware('role:Super Admin|Admin Engineer')->group(function () {
        //request
        Route::get('/request-goods', [RequestOrderController::class, 'index'])->name('request-goods.index');
        Route::post('/request-goods', [RequestOrderController::class, 'store'])->name('request-goods.store');

        //return
        Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
        Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');
    });

    Route::middleware('role:Super Admin|Admin Warehouse|Head Warehouse|Admin Engineer')->group(function () {
       //outbounds
       Route::get('/outbounds', [OutboundController::class, 'index'])->name('outbounds.index');
       Route::get('/outbounds/{outbound}/show', [OutboundController::class, 'show'])->name('outbounds.show');
       Route::get('/outbounds/{outbound}/{status}', [OutboundController::class, 'changeStatus'])->name('outbounds.changeStatus');
    });
});
