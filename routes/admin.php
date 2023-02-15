<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\ApplicationAdminController;
use App\Http\Controllers\Admin\BannerAdminController;
use App\Http\Controllers\Admin\BrandAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SiteContent\SitecontentController;
use App\Http\Controllers\Admin\TagAdminController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:web"], "prefix" => "admin"], function () {

    Route::resource("brands", BrandAdminController::class, ['as' => 'admin']);
    Route::resource("categories", CategoryAdminController::class, ['as' => 'admin']);
    Route::resource("tags", TagAdminController::class, ['as' => 'admin']);
    Route::resource("applications", ApplicationAdminController::class, ['as' => 'admin']);
    Route::resource("banners", BannerAdminController::class, ['as' => 'admin']);


    Route::get("/", [SitecontentController::class, 'index']);

    Route::post("/reorder", "Admin\AdminController@reorder")->name("reorder");
    Route::post("/preorder", "Admin\AdminController@preorder")->name(
        "preorder"
    );
    Route::post("/delimg", "Admin\AdminController@delimg")->name("delimg");
    Route::post("/delimgpost", "Admin\AdminController@delimgpost")->name(
        "delimg_post"
    );
    Route::post("/upload/img", "Admin\AdminController@upload_img");

    Route::resource("slider", "Admin\Slide\SliderController");
    Route::resource("sitecontent", SitecontentController::class);


    Route::resource("/pages", "Admin\Pages\PageController");


    Route::resource("posts", PostController::class);

    Route::get("/feedbacks", "Admin\AdminController@feedbacks")->name(
        "feedbacks.index"
    );
});
