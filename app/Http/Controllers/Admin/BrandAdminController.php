<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BrandAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brands.edit');
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
            'name' => ['required', 'string', 'max:255', 'unique:'. Brand::class],
            'description' => ['required', 'string'],
            'description_arabic' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();
            $data = $request->except(['_token', 'logo', 'banner']);
            $data['is_active'] = $request->is_active == 'on' ? true : false;
            $data['slug'] = Str::slug($request->name);
            $brand = Brand::create($data);

            DB::commit();

            $brand->addMedia($request->logo)
            ->toMediaCollection('logos');

            $brand->addMedia($request->banner)
            ->toMediaCollection('banners');

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }

        return redirect()->route('admin.brands.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', ['item' => $brand]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('brands')->ignore($brand->id)],
            'description' => ['required', 'string'],
            'description_arabic' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();
            $data = $request->except(['_token', 'logo', 'banner']);
            $data['is_active'] = $request->is_active == 'on' ? true : false;
            $data['slug'] = Str::slug($request->name);
            $brand->update($data);

            DB::commit();

            if($request->has('logo')) {
                $brand->getFirstMedia('logos')->delete();
                $brand->addMedia($request->logo)
                    ->toMediaCollection('logos');
            }

            if($request->has('banner')) {
                $brand->getFirstMedia('banners')->delete();
                $brand->addMedia($request->banner)
                    ->toMediaCollection('banners');

            }

            flash('Successfully Updated')->overlay()->success();

        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }

        return redirect()->route('admin.brands.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        if($brand->delete())
        {
            flash('Successfully Deleted')->overlay();
        } else {
            flash('Something went worng please try again')->overlay()->success();
        }

        return redirect()->route('admin.brands.index');
    }
}
