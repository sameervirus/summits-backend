<?php

namespace App\Http\Controllers;

use App\Mail\AskPriceMail;
use App\Models\AskPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AskPriceController extends Controller
{
    public function store(Request $request) {

        $request->validate([
            'product_id' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required|max:255'
        ]);

        $mail = AskPrice::create($request->except('product'));

        if($mail) {
            Mail::to($mail->email)->send(new AskPriceMail(
                $mail->name, $mail->email, $mail->phone, $mail->company, $mail->message, $request->product
            ));
            return response()->json([
                'status' => 'success',
                'message' => __('success'),
            ]);
        } else {
            return response()->json([
                'status' => 'failure',
                'message' => __('fail'),
            ]);
        }
    }
}
