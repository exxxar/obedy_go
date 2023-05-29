<?php

use App\Enums\FoodPartEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallbackController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LotteryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Main');
})->name('main');

Route::get('/self', function () {
    return Inertia::render('Self');
})->name('self');

Route::get('/{foodPart}', [ProductController::class, 'getProducts'])
    ->whereIn('foodPart', FoodPartEnum::getConstants())
    ->name('products');

Route::group(['prefix' => 'lottery'], function () {
    Route::get('/all', [LotteryController::class, 'getLotteryList'])->name('lotteries');
    Route::get('/get/{id}', [LotteryController::class, 'getLottery'])->name('lottery');
    Route::post('/pick', [LotteryController::class, 'pickPlace'])->name('lottery.pick');
});

Route::group(['prefix' => 'order'], function () {
    Route::post('/delivery', [OrderController::class, 'getDeliveryRange'])->name('delivery.range');
    Route::post('/resend/code', [OrderController::class, 'resendCode'])->name('resend.code');
    Route::post('/check', [OrderController::class, 'checkOrder'])->name('check.order');
    Route::post('/', [OrderController::class, 'order'])->name('order');
});


Route::group(['prefix' => 'cart'], function () {
    Route::post('/change', [CartController::class, 'changeCart'])
        ->name('change.cart');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('clear.cart');
    Route::post('/save', [CartController::class, 'saveCart'])->name('save.cart');
    Route::get('/print', [CartController::class, 'printReport'])->name('print.report');
});

Route::group(['prefix' => 'auth', 'middleware'=>'guest'], function(){
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::get('user', [AuthController::class, 'getUser'])->name('user');

Route::middleware('auth')
    ->post('logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::post('callback', [CallbackController::class, 'sendMessage'])->name('callback');

