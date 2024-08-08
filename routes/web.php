<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SeriesController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */
Route::get('/', function () {return redirect()->route('admin.home');});

Route::group(['middleware' => ['auth', 'system-user', 'prevent-back-history'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [ProfileController::class, 'dashboard'])->name('home');

    //permission
    Route::get('/permission-datatable', [PermissionController::class, 'dataTable']);
    Route::resource('permissions', PermissionController::class);

    //roles
    Route::get('/roles-datatable', [RolesController::class, 'dataTable']);
    Route::resource('roles', RolesController::class);

    //users
    Route::get('/users-datatable', [UserController::class, 'dataTable']);
    Route::resource('users', UserController::class);

    //point module
    Route::group(['prefix' => 'point_system'], function() {
        //points
        Route::get('/points-list', [PointController::class, 'getPointList']);
        Route::post('/points/update-data/{points}', [PointController::class, 'updateData']);
        Route::resource('points', PointController::class);
    });

    //Product module
    Route::group(['prefix' => 'product_setting'], function() {

        //series
        Route::get('/series-list', [SeriesController::class, 'getSeriesList']);
        Route::post('/series/update-data/{series}', [SeriesController::class, 'updateData']);
        Route::resource('series', SeriesController::class);

        //category
        Route::get('/categories-list', [CategoryController::class, 'getCategoryList']);
        Route::post('/categories/update-data/{category}', [CategoryController::class, 'updateData']);
        Route::resource('categories', CategoryController::class);

        //product
        Route::get('/products-list', [ProductController::class, 'getProductList']);
        Route::post('/products/storeMedia', [ProductController::class, 'storeMedia'])->name('products.storeMedia');
        Route::post('/products/deleteMedia', [ProductController::class, 'deleteMedia'])->name('products.deleteMedia');
        Route::post('/products/update-data/{product}', [ProductController::class, 'updateData']);
        Route::resource('products', ProductController::class);
    });

    //order module
    Route::group(['prefix' => 'order_setting'], function() {
        //orders
        Route::get('/orders-list', [OrderController::class, 'getOrders']);
        Route::get('/order-confirm', [OrderController::class, 'confirmOrder']);
        Route::resource('orders', OrderController::class);
    });

});

require __DIR__ . '/auth.php';
