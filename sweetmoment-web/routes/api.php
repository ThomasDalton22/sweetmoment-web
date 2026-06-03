<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/midtrans-callback', [PembayaranController::class, 'callback']);


Route::get('/vendors', [SessionController::class, 'getVendors']);
Route::get('/vendors/{id}', [SessionController::class, 'getVendorDetail']);
Route::get('/categories', [SessionController::class, 'getCategories']);
Route::get('/news', [SessionController::class, 'getNews']);
Route::get('/testimonials', [SessionController::class, 'getTestimonials']);

Route::post('/payment/webhook', [PaymentController::class, 'handlePakasirWebhook'])
    ->name('payment.webhook');
