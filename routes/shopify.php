<?php

use App\Http\Controllers\Shopify\AppController;
use App\Http\Controllers\Shopify\Home;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Shopify as MiddlewareShopify;

Route::middleware([
    MiddlewareShopify\VerifyWebRequest::class
])->group(function () {
    Route::get('install', [AppController::class, 'install']);
    Route::get('redirect', [AppController::class, 'redirect']);
});

Route::middleware([
])->group(function () {
    Route::get('home', [Home\IndexController::class, 'index'])->name('home');
});