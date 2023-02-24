<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\GroupProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
require __DIR__.'/auth.php';
Route::middleware(['auth'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth'])->group(function () {
    Route::resource('addresses', AddressController::class);
    Route::resource('users', UserController::class);
});
Route::get('governorates', [CommonController::class, 'governorates']);
Route::get('cities', [CommonController::class, 'cities']);
Route::resource('products', ProductController::class)->scoped(['product' => 'slug']);
Route::resource('groups', GroupProductController::class)->scoped(['group' => 'slug']);
Route::resource('brands', BrandController::class)->scoped(['brand' => 'slug']);
Route::resource('categories', CategoryController::class)->scoped(['category' => 'slug']);
Route::resource('tags', TagController::class)->scoped(['tag' => 'slug']);
Route::resource('applications', ApplicationController::class)->scoped(['application' => 'slug']);
Route::resource('banners', BannerController::class);

Route::get('fake/{file}', function()
{
    return response()->file(app_path('../api/'. request('file')));
});
