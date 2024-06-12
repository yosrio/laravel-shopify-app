<?php

use App\Http\Controllers\Shopify\AppController;
use App\Http\Controllers\Shopify\Home;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Shopify as MiddlewareShopify;

Route::middleware([
    MiddlewareShopify\VerifyWebRequest::class
])->group(function () {
    Route::get('install', [AppController::class, 'install']);
    Route::get('redir', [AppController::class, 'redir']);
});