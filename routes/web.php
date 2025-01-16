<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\GetDataController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DeliveryAreaController;
use App\Http\Controllers\ProblemItemController;
use App\Http\Controllers\RequestOrderController;
use App\Http\Controllers\SignatureController;
use Symfony\Component\HttpKernel\Profiler\Profile;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/coba', function () {
    $outbound = \App\Models\Outbound::find(1);
   return view('outbounds.pdf.outbound', compact('outbound'));
});

//auth
Route::get('/', LoginController::class)->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('submitLogin');

Route::middleware('auth', 'is_active')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    //profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('users.showProfile');
    Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('users.updateProfile');
    Route::put('/profile/{user}/password', [ProfileController::class, 'updatePassword'])->name('users.updatePassword');
    Route::post('/signature', [SignatureController::class, 'upload'])->name('signature.upload');
    Route::get('/signature/delete', [SignatureController::class, 'delete'])->name('signature.delete');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //get date
    Route::get('/get-categories', [GetDataController::class, 'getCategories'])->name('getCategories');
    Route::get('/get-goods/{category}', [GetDataController::class, 'getGoods'])->name('getGoods');

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

    Route::middleware('role:Super Admin|Admin Warehouse|Head Warehouse')->group(function () {
        //warehouse
        Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
        Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
        Route::get('/warehouses/create/cek', [WarehouseController::class, 'create'])->name('warehouses.create');
        Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
        Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
        Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
        Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');

        //containers
        Route::resource('containers', ContainerController::class);

        //categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        //units
        Route::get('/units', [UnitController::class, 'index'])->name('units.index');
        Route::get('/units/create', [UnitController::class, 'create'])->name('units.create');
        Route::post('/units', [UnitController::class, 'store'])->name('units.store');
        Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
        Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update');
        Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');

        //goods
        Route::get('/goods', [GoodsController::class, 'index'])->name('goods.index');
        Route::get('/goods/create', [GoodsController::class, 'create'])->name('goods.create');
        Route::post('/goods', [GoodsController::class, 'store'])->name('goods.store');
        Route::get('/goods/{goods}/edit', [GoodsController::class, 'edit'])->name('goods.edit');
        Route::put('/goods/{goods}', [GoodsController::class, 'update'])->name('goods.update');
        Route::delete('/goods/{goods}', [GoodsController::class, 'destroy'])->name('goods.destroy');
        Route::get('/report/goods', [GoodsController::class, 'reportGoods'])->name('goods.reportGoods');

        //vendors
        Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
        Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendors.create');
        Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');
        Route::get('/vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
        Route::put('/vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
        Route::delete('/vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');

        //area
        Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
        Route::get('/areas/create/{warehouse}', [AreaController::class, 'create'])->name('areas.create');
        Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
        Route::get('/areas/{area}/edit', [AreaController::class, 'edit'])->name('areas.edit');
        Route::put('/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
        Route::delete('/areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');

        //delivery area
        Route::resource('delivery-areas', DeliveryAreaController::class);

        //problems_item
        Route::get('/problem-items', [ProblemItemController::class, 'index'])->name('problems.index');
        Route::post('/resend-items', [InboundController::class, 'resendItems'])->name('inbounds.resendItems');
        Route::put('/problem-items/{problem_item}', [ProblemItemController::class, 'updateWorthy'])->name('problems.updateWorthy');
    });

    Route::middleware('role:Super Admin|Admin Engineer')->group(function () {


        //request
        Route::get('/request-goods', [OutboundController::class, 'request'])->name('outbounds.request');
        Route::post('/request-goods', [OutboundController::class, 'storeRequest'])->name('outbounds.storeRequest');
        Route::get('/request-goods/{outbound}/edit', [OutboundController::class, 'editItems'])->name('outbounds.editItems');
        Route::put('/request-goods/update/{outbound}', [OutboundController::class, 'updateRequest'])->name('outbounds.updateRequest');

        //order
        Route::get('/orders', [InboundController::class, 'order'])->name('orders.index');
        Route::post('/orders', [InboundController::class, 'storeOrder'])->name('orders.store');

        //return
        Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');

        //payment
        Route::post('/payment/outbound', [PaymentController::class, 'paymentOutbound'])->name('payment.outbound');

        //resend
        Route::get('/projects/{outbound}/resend', [ProjectController::class, 'resend'])->name('inbounds.resend');

    });

    Route::middleware('role:Super Admin|Admin Warehouse|Head Warehouse|Admin Engineer')->group(function () {
        //projects
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/detail', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/return', [ProjectController::class, 'return'])->name('projects.return');
        Route::post('/projects/{project}/return', [ProjectController::class, 'storeReturn'])->name('projects.storeReturn');
        Route::get('/projects/{outbound}/show', [ProjectController::class, 'showOutbound'])->name('projects.showOutbound');
        Route::get('/projectss/{project}/print', [ProjectController::class, 'print'])->name('projects.print');
        Route::get('/projectss/{project}/export', [ProjectController::class, 'export'])->name('projects.export');
        Route::get('/projects/{outbound}/print-outbound', [ProjectController::class, 'printOutbound'])->name('projects.printOutbound');

       //outbounds
       Route::get('/outbounds', [OutboundController::class, 'index'])->name('outbounds.index');
       Route::get('/outbounds/{outbound}/show', [OutboundController::class, 'show'])->name('outbounds.show');
       Route::get('/outbounds/{outbound}/{status}', [OutboundController::class, 'changeStatus'])->name('outbounds.changeStatus');
       Route::put('/outbounds/{outbound}/pickup', [OutboundController::class, 'pickup'])->name('outbounds.pickup');
       Route::put('/outbounds/{outbound}/delivery', [OutboundController::class, 'delivery'])->name('outbounds.delivery');
       Route::put('/outbounds/{outbound}/approveDelivery', [OutboundController::class, 'approveDelivery'])->name('outbounds.approveDelivery');
       Route::get('/download-invoice-delivery/{outbound}', [OutboundController::class, 'downloadInvoiceDelivery'])->name('outbounds.downloadInvoiceDelivery');

       //inbounds
       Route::get('/inbounds', [InboundController::class, 'index'])->name('inbounds.index');
       Route::get('/inbounds/{inbound}/show', [InboundController::class, 'show'])->name('inbounds.show');
       Route::get('/inbounds/{inbound}/{status}', [InboundController::class, 'changeStatus'])->name('inbounds.changeStatus');
       Route::put('/inbounds/{inbound}/delivery', [InboundController::class, 'delivery'])->name('inbounds.delivery');
       Route::put('/inbounds/{inbound}/success', [InboundController::class, 'success'])->name('inbounds.success');

       //print image payment
       Route::get('/download-image-payment/{payment}', [PaymentController::class, 'downloadImagePayment'])->name('payment.downloadImagePayment');

       Route::get('/download-invoice-delivery-inbound/{inbound}', [InboundController::class, 'downloadInvoiceDelivery'])->name('inbounds.downloadInvoiceDelivery');

    });
});
