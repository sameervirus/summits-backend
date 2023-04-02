<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'product_id' => 'required|numeric',
        ]);

        $review = ProductReview::create($validatedData);

        // Return a response indicating success
        return response()->json([
            'message' => 'Review added successfully',
            'review' => $review,
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductReview  $productReview
     * @return \Illuminate\Http\Response
     */
    public function show(ProductReview $productReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductReview  $productReview
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductReview $productReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductReview  $productReview
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductReview  $productReview
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'id' => 'required|numeric',
        ]);

        // Find the review with the provided email and ID
        $review = ProductReview::where('email', $validatedData['email'])
                               ->where('id', $validatedData['id'])
                               ->first();

        // If the review was found, delete it and return a success message
        if ($review) {
            $review->delete();
            return response()->json([
                'message' => 'Review deleted successfully',
            ], 200);
        }

        // If no review was found, return a 404 error
        return response()->json([
            'message' => 'Review not found',
        ], 404);
    }
}
