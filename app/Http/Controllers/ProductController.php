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
        if($request->has('tag') && $request->tag != 'undefined') {
            $query->whereHas('tags', function($t) use ($request) {
                $t->whereIn('slug', explode(',', $request->tag));
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
        $wishes = Auth::user()->wishes;

        if($wishes->count() < 1) return [];

        return ProductResource::collection($wishes);
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
        $categories = $product->categories;
        $tags = $product->tags;
        $applications = $product->applications;
        $brand = $product->brand;

        $relatedProducts = Product::whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('id', $categories->pluck('id'));
        })
        ->orWhereHas('tags', function ($query) use ($tags) {
            $query->whereIn('id', $tags->pluck('id'));
        })
        ->orWhereHas('applications', function ($query) use ($applications) {
            $query->whereIn('id', $applications->pluck('id'));
        })
        ->orWhere('brand_id', $product->brand_id)
        ->where('id', '<>', $product->id)
        ->inRandomOrder()
        ->take(12)
        ->get();

        return ProductResource::collection($relatedProducts);
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
        $products = Product::withCount('orders')
                            ->orderByDesc('orders_count')
                            ->take(12)
                            ->get();
        return ProductResource::collection($products);
    }

    public function search(Request $request) {
        $query = $request->q;
        if($query == '') return [];
        $products = Product::where('name_english', 'like', '%'.$query.'%')
                            ->orWhere('name_arabic', 'like', '%'.$query.'%')
                            ->orWhereHas('categories', function($q) use ($query) {
                                $q->where('name_english', 'like', '%'.$query.'%')
                                    ->orWhere('name_arabic', 'like', '%'.$query.'%');
                            })
                            ->orWhereHas('applications', function($q) use ($query) {
                                $q->where('name_english', 'like', '%'.$query.'%')
                                    ->orWhere('name_arabic', 'like', '%'.$query.'%');
                            })
                            ->orWhereHas('tags', function($q) use ($query) {
                                $q->where('name_english', 'like', '%'.$query.'%')
                                    ->orWhere('name_arabic', 'like', '%'.$query.'%');
                            })
                            ->orWhereHas('brand', function($q) use ($query) {
                                $q->where('name_english', 'like', '%'.$query.'%')
                                    ->orWhere('name_arabic', 'like', '%'.$query.'%');
                            })
                            ->take(10)
                            ->get();
        return ProductResource::collection($products);
    }
}
