<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $categories = Category::all();
        $tags = Tag::all();
        $applications = Application::all();

        return view('admin.products.edit', compact('title', 'titles', 'brands', 'categories', 'tags', 'applications'));
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
            DB::beginTransaction();
            $data = $request->except(['_token', 'images', 'applications', 'categories', 'tags']);
            $data['slug'] = Str::slug($request->name_english);
            $product = Product::create($data);

            if ($request->hasFile('images')) {
                $fileAdders = $product->addMultipleMediaFromRequest(['images'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('images');
                    });
            }

            if($request->has('applications')) $product->applications()->attach($request->applications);
            if($request->has('categories')) $product->categories()->attach($request->categories);
            if($request->has('tags')) $product->tags()->attach($request->tags);

            DB::commit();

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            DB::rollback();
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
        $categories = Category::all();
        $tags = Tag::all();
        $applications = Application::all();
        $item = $product;
        $pa = $product->applications->pluck('id')->toArray();
        $pc = $product->categories->pluck('id')->toArray();
        $pt = $product->tags->pluck('id')->toArray();
        return view('admin.products.edit', compact('item','title', 'titles',
                    'brands', 'pa', 'pc', 'pt', 'categories', 'tags', 'applications'));
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
            DB::beginTransaction();
            $data = $request->except(['_token', 'images', 'applications', 'categories', 'tags']);
            $data['slug'] = Str::slug($request->name_english);
            $product->update($data);

            $p = Product::find($product->id);

            $p->applications()->sync($request->applications);
            $p->categories()->sync($request->categories);
            $p->tags()->sync($request->tags);

            if ($request->hasFile('images')) {
                $p->addMultipleMediaFromRequest(['images'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('images');
                    });
            }

            DB::commit();

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            DB::rollback();
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
