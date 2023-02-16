<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        $title = 'Product';
        $titles = 'Products';
        return view('admin.products.index', compact('brands', 'title', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $title = 'Product';
        $titles = 'Products';
        return view('admin.products.edit', compact('title', 'titles', 'brands'));
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
            'name_english' => ['required', 'string', 'max:255', 'unique:'. Product::class],
            'name_arabic' => ['required', 'string', 'max:255'],
            'description_english' => ['required', 'string',],
            'description_arabic' => ['required', 'string',],
            'quantity' => ['required', 'integer', 'max:255',],
            'brand_id' => ['required', 'integer', 'max:255',],
        ]);

        try {

            $data = $request->except(['_token', 'images']);
            $data['slug'] = Str::slug($request->name_english);
            $product = Product::create($data);

            if ($request->hasFile('images')) {
                $fileAdders = $product->addMultipleMediaFromRequest(['images'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('images');
                    });
            }

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($brand_id)
    {
        $items = Product::where('brand_id', $brand_id)->get();
        $brands = Brand::all();
        $title = 'Product';
        $titles = 'Products';
        return view('admin.products.index', compact('items','brands', 'title', 'titles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $title = 'Product';
        $titles = 'Products';
        $brands = Brand::all();
        $item = $product;
        return view('admin.products.edit', compact('item','title', 'titles', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name_english' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'name_arabic' => ['required', 'string', 'max:255'],
            'description_english' => ['required', 'string',],
            'description_arabic' => ['required', 'string',],
            'quantity' => ['required', 'integer', 'max:255',],
            'brand_id' => ['required', 'integer', 'max:255',],
        ]);

        try {

            $data = $request->except(['_token', 'images']);
            $data['slug'] = Str::slug($request->name_english);
            $product->update($data);

            if ($request->hasFile('images')) {
                $fileAdders = $product->addMultipleMediaFromRequest(['images'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('images');
                    });
            }

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if($product->delete())
        {
            flash('Successfully Deleted')->overlay();
        } else {
            flash('Something went worng please try again')->overlay()->success();
        }

        return redirect()->route('admin.products.index');
    }
}
