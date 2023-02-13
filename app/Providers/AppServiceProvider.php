<?php

namespace App\Providers;

use App\Models\Admin\Pages\Page;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('admin.side', function($view)
        {
            $locale = App::getLocale();
            $pages = Page::all();
            $view->with('pages', $pages);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // JsonResource::withoutWrapping();
    }
}
