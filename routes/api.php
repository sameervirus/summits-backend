<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\SiteContent\SitecontentController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AskPriceController;
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
    Route::post('addresses', [AddressController::class, 'store']);
    Route::resource('users', UserController::class);
});
Route::get('addresses', [AddressController::class, 'index']);
Route::get('governorates', [CommonController::class, 'governorates']);
Route::get('cities', [CommonController::class, 'cities']);
Route::get('menus', [CommonController::class, 'menus']);
Route::get('static-content', [SitecontentController::class, 'create']);

Route::post('ask-price', [AskPriceController::class, 'store']);

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
Route::get('social', function() {
    return [
        'social' => [
            [
            'id'=> 1,
            'path'=> 'https://www.facebook.com/',
            'image'=> 'http://localhost:3000/assets/images/social/facebook.svg',
            'name'=> 'facebook',
            'width'=> 20,
            'height'=> 20,
            ],
            [
                'id'=> 2,
                'path'=> 'https://twitter.com/',
                'image'=> '/assets/images/social/twitter.svg',
                'name'=> 'twitter',
                'width'=> 20,
                'height'=> 20,
            ],
            [
                'id'=> 3,
                'path'=> 'https://instagram.com/',
                'image'=> '/assets/images/social/instagram.svg',
                'name'=> 'instagram',
                'width'=> 20,
                'height'=> 20,
            ],
            [
                'id'=> 4,
                'path'=> 'https://youtube.com/',
                'image'=> '/assets/images/social/youtube.svg',
                'name'=> 'youtube',
                'width'=> 20,
                'height'=> 20,
            ],
        ],
        'contacts'=> [
            'phone'=> '1232322323232',
            'email'=> 'example@example.com',
            'address'=> 'Acme Widgets 123 Widget Street Acmeville, AC 12345 United States of America'
        ],
        'topbartext'=> 'الشحن المجاني. لا يوجد حد أدنى للشراء مطلوب *' . date('H:i:s')
    ];
});
