<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use ctf0\PayMob\Facades\PayMob;
use ctf0\PayMob\Integrations\CreditCard;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Hash;
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

    private function calculateHash($obj) {
        if(!$obj) return false;

        $json = json_decode($obj);
        
        $amount_cents                           = $json->obj->amount_cents;
        $created_at                             = $json->obj->order->created_at;
        $currency                               = $json->obj->order->currency;
        $error_occured                          = $json->obj->error_occured;
        $has_parent_transaction                 = $json->obj->has_parent_transaction;
        $obj_id                                 = $json->obj->order->id;
        $integration_id                         = $json->obj->integration_id;
        $is_3d_secure                           = $json->obj->is_3d_secure;
        $is_auth                                = $json->obj->is_auth;
        $is_capture                             = $json->obj->is_capture;
        $is_refunded                            = $json->obj->is_refunded;
        $is_standalone_payment                  = $json->obj->is_standalois_capturene_payment;
        $is_voided                              = $json->obj->is_voided;
        $order_id                               = $json->obj->order->id;
        $owner                                  = $json->obj->order->merchant->id;
        $pending                                = $json->obj->pending;
        $source_data_pan                        = $json->obj->source_data->pen;
        $source_data_sub_type                   = $json->obj->source_data->sub_type;
        $source_data_type                       = $json->obj->source_data->type;
        $success                                = $json->obj->success;

        $str = $amount_cents.$created_at.$currency.$error_occured.$has_parent_transaction.$obj_id.$integration_id.$is_3d_secure.$is_auth.$is_capture.$is_refunded.$is_standalone_payment.$is_voided.$order_id.$owner.$pending.$source_data_pan.$source_data_sub_type.$source_data_type.$success;

        $secure_hash = $json->obj->data->secure_hash;

        return hash_hmac('sha512', $str, env('ACCEPT_HMAC_ID')) == $secure_hash ? true : false;
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

        // try {
        //     return (new CreditCard($user))->checkOut($total); // or MobileWallet, etc..
        // } catch (RequestException $e) {
        //     return __('something went wrong, please try again later');
        // }
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
        // if(! $this->calculateHash($request->obj)) return false;
        $json = json_decode($request->obj);

        // save the transaction data to the server
        Order::where('paymob_order', $json->obj->order->id)->update([
            "paymob_id" => $json->obj->id,
            "paymob_pending" => $json->obj->pending
        ]);

        // return view('paymob::complete');
    }
}
