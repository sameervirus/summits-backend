<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Http\Resources\GovernorateResource;
use App\Models\Application;
use App\Models\Brand;
use App\Models\City;
use App\Models\Governorate;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CommonController extends Controller
{
    public function governorates() {
        return GovernorateResource::collection(Governorate::all());
    }

    public function cities(Request $request) {
        return City::where('governorate_id', request('governorate_id'))->get();
    }

    public function menus() {
        $groups = App::getLocale() == 'ar'
                    ? Group::select('id','title_arabic as label', 'slug as path')->get()->take(3)
                    : Group::select('id','title_english as label', 'slug as path')->get()->take(3);

        $brands = [
            'id' => 1000,
            'path' => '/shops',
            'label' => 'menu-shops',
            'type' => 'mega',
            'mega_categoryCol' => 5,
            'mega_bannerMode' => 'none',
            'mega_bannerImg' => '/assets/images/mega/banner-menu.jpg',
            'mega_bannerUrl' => '/shops',
            'mega_contentBottom' => '<strong>30% Off</strong> the shipping of your first order with the code: <strong>RAZOR-SALE30</strong>',
            'subMenu' => BrandResource::collection(Brand::get())
        ];
        $applications = [
            'id' => 2000,
            'path' => '/',
            'label' => 'menu-dietary',
            'subMenu' => App::getLocale() == 'ar'
                            ? Application::select('id','name_arabic as label', 'slug as path')->get()
                            : Application::select('id','name_english as label', 'slug as path')->get(),
        ];
        // $tags = [
        //     'id' => 3000,
        //     'path' => '/',
        //     'label' => 'menu-dietary',
        //     'subMenu' => App::getLocale() == 'ar'
        //                     ? Tag::select('id','name_arabic as label', 'slug as path')->get()
        //                     : Tag::select('id','name_english as label', 'slug as path')->get(),
        // ];

        $contacts = [
            'id'=> 7000,
            'path' => '/contact-us',
            'label' => 'menu-contact-us',
        ];

        return [
            $groups[0],
            $groups[1],
            $brands,
            $applications,
            // $tags,
            $contacts,
        ];
    }
}
