<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use ctf0\PayMob\Facades\PayMob;
use ctf0\PayMob\Integrations\CreditCard;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
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
        $request->validate([
            'user_id' => 'required',
            'address_id' => 'required',
            'full_address' => 'required',
            'payment_option' => 'required',
            'total' => 'required',
            'shipping' => 'required',
            'items.*.id' => 'required',
            'items.*.quantity' => 'required',
        ]);

        $user         = User::find($request->user_id);
        $total        = $request->total;
        $items        = collect($request->items);
        $payment      = $request->payment_option;

        if($payment != 'cash-delivery') {
            $token = $this->getToken($items, $total, $user);
            if($payment == 'credit-card') {
                return [
                   "url" => "https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('ACCEPT_CARD_IFRAME_ID') . "?payment_token=$token",
                   "payment_option" => "credit-card"
                ];
            }
        }


    }


    private function getToken($items, $total, $user) {
        $newItems = $items->map(function($item){
            return [
               'name' => $item['name'],
               'amount_cents' => $item['price'] * 100,
               'description' => $item['name'],
               'quantity' => $item['quantity'],
            ];
        })
        ->toArray();
        try {
            $token = Http::post('https://accept.paymob.com/api/auth/tokens', [
                "api_key" => env('ACCEPT_API_KEY') 
            ]);
            
            if($token) {
                $orders = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
                    "auth_token" => $token['token'],
                    "delivery_needed" => false,
                    "amount_cents" => $total * 100,
                    "items" => $newItems

                ]);

                if($orders) {
                    $payment_keys = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
                        "auth_token" => $token['token'],
                        "delivery_needed" => false,
                        "amount_cents" => $total * 100,
                        "items" => $newItems,
                        "expiration" => 3600, 
                        "order_id"=> $orders['id'],
                        "billing_data" => [
                            "apartment" => "NA", 
                            "email" => $user->email, 
                            "floor" => "NA", 
                            "first_name" => $user->fname, 
                            "street" => "NA", 
                            "building" => "NA", 
                            "phone_number" => $user->phone, 
                            "shipping_method" => "NA", 
                            "postal_code" => "NA", 
                            "city" => "NA", 
                            "country" => "NA", 
                            "last_name" => $user->lname, 
                            "state" => "NA"
                        ], 
                        "currency" => "EGP", 
                        "integration_id" => env('ACCEPT_CARD_INTEGRATION_ID')
                    ]);
                    return $payment_keys['token'];
                }
            }
        } catch (RequestException $e) {
            return $e;
            
        }

        return __('something went wrong, please try again later');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * process the order on the gateway side.
     */
    public function process(Request $request)
    {
        return $request->all();
        $request->validate([
            'payment_type' => [
                'required',
                'string',
            ],
        ]);

        $payment_type = $request->payment_type;
        $user         = $request->user();
        $total        = 0; // order total

        try {
            return (new CreditCard($user))->checkOut($total); // or MobileWallet, etc..
        } catch (RequestException $e) {
            return __('something went wrong, please try again later');
        }
    }

    /**
     * validate and complete the order.
     *
     * https://acceptdocs.paymobsolutions.com/docs/transaction-callbacks#transaction-response-callback.
     *
     * @return \Illuminate\View\View
     */
    public function complete(Request $request)
    {
        PayMob::validateHmac($request->hmac, $request->id);

        // save the transaction data to the server
        $data = $request->all();

        return view('paymob::complete');
    }
}
