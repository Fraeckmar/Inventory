<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ItemBoundController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\OrdersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {
    return redirect('/dashboard');
    // if (isset($locale) && in_array($locale, config('app.available_locales'))) {
    //     app()->setLocale($locale);
    // }    
    // echo "current ".app()->getLocale();
    // return view('index');
});

// Dashboard
Route::get('/dashboard', [PageController::class, 'dashboard']);
// User Auth
Route::get('/register', [PageController::class, 'register']);
Route::get('/login', [PageController::class, 'login']);
Route::post('/login', [AuthController::class, 'login'])->name('login');;
Route::post('/logout', [AuthController::class, 'logout']);
// User
Route::resource('/users', UserController::class);
Route::post('/register', [UserController::class, 'store']);
Route::get('/users', [PageController::class, 'customers']);
Route::post('/users', [PageController::class, 'customers']);
// Reports
Route::get('/reports', [ItemBoundController::class, 'report']);
Route::post('/generate-report', [ItemBoundController::class, 'generate_report']);
// Items
Route::resource('/items', ItemsController::class);
Route::post('/items', [ItemsController::class, 'index']);
Route::post('/store-item', [ItemsController::class, 'store']);
// Orders
Route::resource('/order', ItemBoundController::class);
Route::post('/order', [ItemBoundController::class, 'index']);
Route::post('/store-order', [ItemBoundController::class, 'store']);
Route::post('/update-order', [ItemBoundController::class, 'update']);
Route::get('/inbound', [ItemBoundController::class, 'inbound']);
Route::get('/outbound', [ItemBoundController::class, 'outbound']);

// Setting
Route::get('/settings', [Settings::class, 'page']);
Route::post('/save-settings', [Settings::class, 'save']);