<?php

use App\Enums\FoodPartEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallbackController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LotteryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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

Route::group(['prefix' => 'special'], function () {
    Route::get('/', [ProductController::class, 'getMenus'])->name('special');

    Route::get('/{slug}', [ProductController::class, 'getMenu'])
        ->name('special.menu');
});

Route::get('/specialists', [ProfileController::class, 'getSpecialists'])->name('specialists');


Route::get('/{foodPart}', [ProductController::class, 'getProducts'])
    ->whereIn('foodPart', FoodPartEnum::getConstants())
    ->name('products');

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::get('user', [AuthController::class, 'getUser'])->name('user');

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'chats'], function () {
        Route::get('/', function () {
            return Inertia::render('Chats');
        })->name('chats');
        Route::get('/get', [ChatController::class, 'getChats'])->name('chats.get');
        Route::get('/{id}', [ChatController::class, 'getMessages'])->name('chat.messages');
        Route::get('/create/{id}', [ChatController::class, 'createChat'])->name('create.chat');
        Route::post('/send', [ChatController::class, 'sendMessage'])->name('send.message');
        Route::put('/seen/{id}', [ChatController::class, 'seenMessage'])->name('seen.message');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', function () {
            return Inertia::render('Profile');
        })->name('profile');
        Route::get('/get', [ProfileController::class, 'getProfile'])->name('profile.get');
        Route::put('/', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    });

    Route::group(['middleware' => 'role:specialist'], function () {
        Route::resource('menu', MenuController::class)->except(['index', 'show', 'update']);

    });

    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

});


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

Route::post('callback', [CallbackController::class, 'sendMessage'])->name('callback');

