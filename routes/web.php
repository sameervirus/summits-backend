<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Models\Admin\Pages\Page;
use Illuminate\Http\Request;
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

Route::get('/calc', [OrderController::class, "create"]);
Route::get('/pages', function() {
    return Page::find(2)->content;
    return json_decode(Page::find(2)->content);
});
Route::get('/complete-order', function(Request $request) {
    return $request->all();
});