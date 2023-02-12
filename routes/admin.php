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

use App\Http\Controllers\Admin\SiteContent\SitecontentController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:web"], "prefix" => "admin"], function () {
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
    Route::resource("sitecontent", "Admin\SiteContent\SitecontentController");
    Route::resource("products", "Admin\Product\ProductController");
    Route::resource("wproducts", "Admin\Product\WproductController");
    Route::resource("pproducts", "Admin\Product\PproductController");
    Route::post("/wdelimg", "Admin\AdminController@wdelimg")->name("wdelimg");
    Route::post("/pdelimg", "Admin\AdminController@pdelimg")->name("pdelimg");

    Route::resource("/pages", "Admin\Pages\PageController");
    Route::get("/pages-video", "Admin\Pages\PageController@video")->name(
        "pages.video"
    );

    Route::resource("downloads", "Admin\DownloadController");
    Route::resource("posts", "Admin\PostController");
    Route::resource("distributors", "Admin\DistributorController");

    Route::get("/feedbacks", "Admin\AdminController@feedbacks")->name(
        "feedbacks.index"
    );
});
