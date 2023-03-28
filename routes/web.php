<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact dev@visiongrp.net'], 404);
});
Route::get('/', function(){
    return [
        "app" => "Summits Application",
    ];
});

Route::get('/login', [AuthController::class, 'index'])->name('admin.login');
Route::post('/login', [AuthController::class, 'store'])
                ->middleware('guest')
                ->name('web-login');
Route::post('/logout', [AuthController::class, 'destroy'])
                ->middleware('auth')
                ->name('web-logout');
require __DIR__.'/admin.php';

Route::group([
    'prefix'     => 'orders',
    'as'         => 'order.',
    'middleware' => 'auth',
], function () {
    ctf0\PayMob\PayMobRoutes::routes();
});