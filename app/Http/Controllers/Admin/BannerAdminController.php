<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BannerAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = ['hero', 'icons', 'banner1', 'banner2'];
        $title = 'Banner';
        $titles = 'Banners';
        return view('admin.banners.index', compact('positions', 'title', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Banner';
        $titles = 'Banners';
        $positions = ['hero', 'icons', 'banner1', 'banner2'];
        return view('admin.banners.edit', compact('title', 'titles', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_english' => ['required', 'string', 'max:255'],
            'title_arabic' => ['required', 'string', 'max:255',],
            'position' => ['required', 'string', 'max:255', Rule::in(['hero', 'icons', 'banner1', 'banner2'])],
            'slug' => ['required', 'string', 'max:255',]
        ]);

        try {

            $data = $request->except(['_token', 'banner']);
            $banner = Banner::create($data);

            $banner->addMedia($request->banner)->toMediaCollection($request->position);

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.banners.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show($position)
    {
        $items = Banner::where('position', $position)->get();
        $positions = ['hero', 'icons', 'banner1', 'banner2'];
        $title = 'Banner';
        $titles = 'Banners';
        return view('admin.banners.index', compact('items','positions', 'title', 'titles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $title = 'Banner';
        $titles = 'Banners';
        $positions = ['hero', 'icons', 'banner1', 'banner2'];
        $item = $banner;
        return view('admin.banners.edit', compact('item','title', 'titles', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title_english' => ['required', 'string', 'max:255'],
            'title_arabic' => ['required', 'string', 'max:255',],
            'position' => ['required', 'string', 'max:255', Rule::in(['hero', 'icons', 'banner1', 'banner2'])],
            'slug' => ['required', 'string', 'max:255',]
        ]);

        try {

            $data = $request->except(['_token', 'banner']);
            $banner->update($data);

            if($request->hasFile('banner')) {
                $banner->clearMediaCollection($request->position);
                $banner->addMedia($request->banner)->toMediaCollection($request->position);
            }

            flash('Successfully Updated')->overlay()->success();
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.banners.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        if($banner->delete())
        {
            flash('Successfully Deleted')->overlay();
        } else {
            flash('Something went worng please try again')->overlay()->success();
        }

        return redirect()->route('admin.banners.index');
    }
}
