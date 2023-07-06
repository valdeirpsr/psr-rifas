<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get(
    '/rifas/{rifa:slug}/orders/{telephone}',
    [\App\Http\Controllers\RifasController::class, 'showOrders']
)->name('rifas.show.orders');

Route::get('/rifas/{rifa:slug}', [App\Http\Controllers\RifasController::class, 'show']);

Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');

Route::get('/checkout/{id}', [App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');

Route::get('/payments/{payment:id}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');

Route::post('/payments/notification', [App\Http\Controllers\PaymentController::class, 'update'])->name('payment.update');

Route::post('/payments', [App\Http\Controllers\PaymentController::class, 'store'])->name('payment.store');
