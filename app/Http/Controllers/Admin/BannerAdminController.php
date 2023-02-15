<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

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
            'title_english' => ['required', 'string', 'max:255', 'unique:'. Banner::class],
            'title_arabic' => ['required', 'string', 'max:255',],
            'position' => ['required', 'string', 'max:255',],
            'slug' => ['required', 'string', 'max:255',],
            'banner' => ['required','image','size:1024']
        ]);

        try {

            $data = $request->except(['_token', 'banner']);
            $banner = Banner::create($data);

            $banner->addMedia($request->banner)->toMediaCollection($request->position);

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.applications.index');


        return $request->all();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        //
    }
}
