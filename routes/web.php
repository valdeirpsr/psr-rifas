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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('rifas')->group(function () {
    Route::get(
        '/{rifa:slug}/orders/{telephone}',
        [\App\Http\Controllers\RifasController::class, 'showOrders']
    )->name('rifas.show.orders');

    Route::get('/{rifa:slug}', [App\Http\Controllers\RifasController::class, 'show'])->name('rifas.show');
});

Route::prefix('payments')->group(function () {
    Route::get('/{payment:id}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');

    Route::get('/{payment:id}/check', [App\Http\Controllers\PaymentController::class, 'check'])->name('payment.check');

    Route::post('/notification', [App\Http\Controllers\PaymentController::class, 'update'])->name('payment.update');

    Route::post('/', [App\Http\Controllers\PaymentController::class, 'store'])->name('payment.store');
});

Route::prefix('orders')->group(function () {
    Route::post('/', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');

    Route::get('/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

Route::get('/terms', [App\Http\Controllers\TermsController::class, 'index'])->name('terms');

Route::get('/rifas-finalizadas', [App\Http\Controllers\RifasController::class, 'list'])->name('rifas.finalizadas');

Route::get('/depoimentos', [App\Http\Controllers\TestimonialController::class, 'index'])->name('testimonials.list');

Route::get('/contato', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');

Route::post('/contato', [App\Http\Controllers\ContactController::class, 'send'])->name('contact.post');
