<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CommonController;
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
Route::resource('brands', BrandController::class)->scoped(['brand' => 'slug']);

Route::get('fake/{file}', function()
{
    return response()->file(app_path('../api/'. request('file')));
});
