<?php

namespace App\Http\Controllers\Admin\Product;

use App\Admin\Pproduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Verot\Upload\Upload as UploadClass;
use Str;

class PproductController extends Controller
{
    public $postData;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        return view('admin.pproducts.products'); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function category($slug)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pproducts.edit');
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
            'types'  => 'required',
            'category'  => 'required',
            'sub'       => 'required',
            'model'      => 'required'
        ]);

        $product = Pproduct::create([
            'types_slug' => Str::slug($request->types),
            'types' => $request->types,
            'types_ar' => $request->types_ar ?? $request->types,
            'category_slug' => Str::slug($request->category),
            'category' => $request->category,
            'category_ar' => $request->category_ar ?? $request->category,
            'sub_slug' => Str::slug($request->sub),
            'sub' => $request->sub,
            'sub_ar' => $request->sub_ar ?? $request->sub,
            'slug' => Str::slug($request->model),
            'name' => $request->model,
            'name_ar' => $request->model ?? $request->model,
            'features' => $request->features,
            'features_ar' => $request->features_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'technical_data' => $request->technical_data,
            'technical_data_ar' => $request->technical_data_ar,
        ]);

        $this->postData = $request;

        if($product) {

            $this->attachData($product, $request->application, 'Application', 'applications');
            $this->attachData($product, $request->application_ar, 'Arapplication', 'arapplications');
            $this->attachData($product, $request->fluid, 'Fluid', 'fluids');
            $this->attachData($product, $request->fluid_ar, 'Arfluid', 'arfluids');

          if($request->file('file')){
              $fileAdders = $product
              ->addMultipleMediaFromRequest(['file'])
              ->each(function ($fileAdder) {
                  $fileAdder->toMediaCollection('images');
              });
          }
          if($request->file('download')){
              $fileAdders = $product
              ->addMultipleMediaFromRequest(['download'])
              ->each(function ($fileAdder) {                  
                  $fileAdder->withCustomProperties(['code' => $this->postData->code]);
                  $fileAdder->toMediaCollection('download');
              });
          }
            flash('Successfully Added')->overlay()->success();
        } else {
            flash('Something went worng please try again')->overlay()->success();
        }

        return redirect()->route('pproducts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        return view('admin.products.products', [
            'products' => Product::where('category', $category)->orderBy('sort_order')->get()
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Pproduct::findOrFail($id);
        return view('admin.pproducts.edit', ['item' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        $item = Pproduct::findOrFail($product);

        $request->validate([
            'types'  => 'required',
            'category'  => 'required',
            'sub'       => 'required',
            'model'      => 'required'
        ]);

        $item->types_slug = Str::slug($request->types);
        $item->types = $request->types;
        $item->types_ar = $request->types_ar ?? $request->types;
        $item->category_slug = Str::slug($request->category);
        $item->category = $request->category;
        $item->category_ar = $request->category_ar ?? $request->category;
        $item->sub_slug = Str::slug($request->sub);
        $item->sub = $request->sub;
        $item->sub_ar = $request->sub_ar ?? $request->sub;
        $item->slug = Str::slug($request->model);
        $item->name = $request->model;
        $item->name_ar = $request->model_ar ?? $request->model;
        $item->features = $request->features;
        $item->features_ar = $request->features_ar;
        $item->description = $request->description;
        $item->description_ar = $request->description_ar;
        $item->technical_data = $request->technical_data;
        $item->technical_data_ar = $request->technical_data_ar;

        $this->postData = $request;

        if($item->save()) {
            
            $this->attachData($item, $request->application, 'Application', 'applications');
            $this->attachData($item, $request->application_ar, 'Arapplication', 'arapplications');
            $this->attachData($item, $request->fluid, 'Fluid', 'fluids');
            $this->attachData($item, $request->fluid_ar, 'Arfluid', 'arfluids');

            if($request->file('file')){
                $fileAdders = $item
                ->addMultipleMediaFromRequest(['file'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('images');
                });
            }
            if($request->file('download')){
                  $fileAdders = $item
                  ->addMultipleMediaFromRequest(['download'])
                  ->each(function ($fileAdder) {                  
                      $fileAdder->withCustomProperties(['code' => $this->postData->code]);
                      $fileAdder->toMediaCollection('download');
                  });
            }
            flash('Successfully Update')->overlay()->success();
        } else {
            flash('Something went worng please try again')->overlay()->success();
        }        

        return redirect()->route('pproducts.index');
    }


    public function attachData($item, $request, $class, $rels)
    {
        $className = '\App\Admin\Pdata\\' . $class;
        $names = [];
        if (!empty($request)) {
            foreach (explode(',', $request) as $name)
            {
                $tag = $className::firstOrCreate(['name'=>$name, 'slug'=>Str::slug($name)]);
                if($tag)
                {
                    $names[] = $tag->id;
                }
            }            
        }
        $item->$rels()->sync($names);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $item = Pproduct::findOrFail($product);

        if($item->delete()){
            $item->applications()->detach();
            $item->arapplications()->detach();
            $item->fluids()->detach();
            $item->arfluids()->detach();
          flash('Successfully Deleted')->overlay();
        } else {
          flash('Something went worng please try again')->overlay()->success();
        }

        return redirect()->route('pproducts.index');
    }

}