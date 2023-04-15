<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Http\Resources\GovernorateResource;
use App\Models\Application;
use App\Models\Brand;
use App\Models\City;
use App\Models\Governorate;
use App\Models\Group;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public function governorates() {
        return GovernorateResource::collection(Governorate::all());
    }

    public function geverIndex() {
        $items = Governorate::all();
        $title = "Governorate";
        $titles= "Governorates";
        return view('admin.governorates.index', compact('items', 'title', 'titles'));
    }

    public function geverUpdates(Request $request, $id) {
        $governorate = Governorate::findOrFail($id);
        $governorate->shipping_fees = $request->input('shipping_fees');
        $governorate->save();

        return redirect()->route('governorates.index');
    }

    public function cities(Request $request) {
        return City::where('governorate_id', request('governorate_id'))->get();
    }

    public function status() {
        return Status::where('id', '<>', 0)->get();
    }

    public function menus() {
        $groups = App::getLocale() == 'ar'
                    ? Group::select(DB::raw('id, CONCAT("/gp/", slug) as path, title_arabic as label'))->get()->take(3)
                    : Group::select(DB::raw('id, CONCAT("/gp/", slug) as path, title_english as label'))->get()->take(3);

        $brands = [
            'id' => 1000,
            'path' => '/shops',
            'label' => 'menu-shops',
            'type' => 'mega',
            'mega_categoryCol' => 6,
            'mega_bannerMode' => 'none',
            'mega_bannerImg' => '/assets/images/mega/banner-menu.jpg',
            'mega_bannerUrl' => '/shops',
            'mega_contentBottom' => '<strong>30% Off</strong> the shipping of your first order with the code: <strong>RAZOR-SALE30</strong>',
            'subMenu' => BrandResource::collection(Brand::get())
        ];
        $app = App::getLocale() == 'ar'
                    ? Application::select('id','name_arabic as label', 'slug as path')->get()
                    : Application::select('id','name_english as label', 'slug as path')->get();
        $apps = $app->map(function($item, $key) {
                    $item->path = '/search?dietary='. $item->path;
                    return $item;
                });
        $applications = [
            'id' => 2000,
            'path' => '/',
            'label' => 'menu-dietary',
            'subMenu' => $apps->all()
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

        $return = [];

        if(@$groups[0]) array_push($return, $groups[0]);
        if(@$groups[1]) array_push($return, $groups[1]);
        if($brands) array_push($return, $brands);
        if($applications) array_push($return, $applications);
        // if($tags) array_push($return, $tags);
        if($contacts) array_push($return, $contacts);

        return $return;
    }

    public function feedback(Request $request)
    {
        $return_message = '';
        $status = 'success';

        if ($request->isMethod('post') && ! $request->has('name')) {
            $email = $request->input('email');

            DB::table('form_submissions')->insert([
                'type' => 'newsletter',
                'email' => $email,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $return_message = 'Thank you for subscribing to our newsletter!';

        } elseif ($request->isMethod('post') && $request->has('name', 'email', 'phone', 'message')) {
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $message = $request->input('message');

            DB::table('form_submissions')->insert([
                'type' => 'contact_us',
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $return_message = 'Your message has been sent!';
        } else {
            $status = 'error';
        }

        return [
            'message' => $return_message,
            'status' => $status,
        ];
    }
}
