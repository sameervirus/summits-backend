<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request->all();
        $query = Product::orderBy('id', 'desc');

        if($request->has('brand') && $request->brand != 'undefined') {
            $query->whereHas('brand', function($b) use ($request) {
                $b->where('slug',$request->brand);
            });
        }
        if($request->has('dietary') && $request->dietary != 'undefined') {
            $query->whereHas('applications', function($a) use ($request) {
                $a->whereIn('slug', explode(',', $request->dietary));
            });
        }
        if($request->has('category') && $request->category != 'undefined') {
            $query->whereHas('categories', function($c) use ($request) {
                $c->whereIn('slug', explode(',', $request->category));
            });
        }
        $products = $query->paginate(20);
        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        return ProductResource::collection($user->wishes);
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
            'product_id' => 'required',
        ]);

        $user = Auth::user();
        $product = Product::find(request('product_id'));

        if($user && $product) {
            if ($user->wishes()->where('id', request('product_id'))->exists()) {
                // The user has the product, so you can remove it
                $user->wishes()->detach(request('product_id'));
            } else {
                // The user doesn't have the product, so you can add it
                $user->wishes()->attach(request('product_id'));
            }
        }
        return $user->wishes->pluck('id')->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }


    public function bestseller()
    {
        $products = Product::take(12)->get();
        return ProductResource::collection($products);
    }
}
