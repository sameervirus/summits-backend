<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Coupon::all();
        $title = 'Coupon';
        $titles = 'Coupons';
        return view('admin.coupons.index', compact('items', 'title', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Coupon';
        $titles = 'Coupons';
        return view('admin.coupons.edit', compact('title', 'titles'));
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
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:value,percentage',
            'amount' => 'required|numeric|min:0',
            'expires_at' => 'required|date',
        ]);

        try {

            $coupon = new Coupon();
            $coupon->code = $request->input('code');
            $coupon->type = $request->input('type');
            $coupon->amount = $request->input('amount');
            $coupon->expires_at = $request->input('expires_at');
            $coupon->active = true;
            $coupon->save();

            flash('Coupon created successfully.')->overlay()->success();
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $coupon = Coupon::where('code', $code)->first();
        $applied = Order::where('coupon', $code)
                        ->where('user_id', Auth::id())
                        ->first();
        
        // Check if the coupon is valid
        if(!$coupon 
            || !$coupon->active 
            || $coupon->expires_at < now()
            || $applied
        ) {
            return response()->json([
                'message' => 'Coupon is not valid.',
            ], 400);
        }

        // Return a success response
        return response()->json([
            'message' => 'Coupon is valid.',
            'coupon' => $coupon,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', ['item' => $coupon, 'title' => 'Coupon']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        try {
            $coupon->active = !$coupon->active;
            $coupon->save();
            flash('Coupon updated successfully.')->overlay()->success();

        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        if($coupon->delete())
        {
            flash('Successfully Deleted')->overlay();
        } else {
            flash('Something went wrong please try again')->overlay()->success();
        }

        return redirect()->route('admin.coupons.index');
    }
}
