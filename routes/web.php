<?php

use App\Enums\FoodPartEnum;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LotteryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
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
    Route::post('/', [OrderController::class, 'order'])->name('order');
});


/*Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});*/

Route::resource('cart', CartController::class);

require __DIR__ . '/auth.php';
