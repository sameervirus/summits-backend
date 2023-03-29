<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use ctf0\PayMob\Facades\PayMob;
use ctf0\PayMob\Integrations\CreditCard;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\Return_;

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

        try {
            DB::beginTransaction();
            $user         = User::find($request->user_id);
            $total        = $request->total;
            $items        = collect($request->items);
            $payment      = $request->payment_option;
    
            $order = Order::create([
                "payment_gateway" => $payment,
                "total" => $total,
                "notes" => $request->notes,
                "shipping_fee" => $request->shipping,
                "user_id" => $request->user_id,
                "fname" => $user->fname,
                "lname" => $user->lname,
                "email" => $user->email,
                "phone" => $user->phone,
                "address_id" => $request->address_id,
                "address" => $request->full_address,
                "status_id" => 1,
            ]);

            foreach ($request->items as $item) {
                $order->products()->attach($item['id'], [
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
    
            if($payment != 'cash-delivery') {
                $paymob_data = $this->getToken($items, $total, $user);
                $token  = $paymob_data[0];
                $order->paymob_order = $paymob_data[1];
                $order->save();
                if($payment == 'credit-card') {
                    DB::commit();
                    return [
                       "url" => "https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('ACCEPT_CARD_IFRAME_ID') . "?payment_token=$token",
                       "payment_option" => "credit-card"
                    ];
                }
            }
            
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
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
                    return [$payment_keys['token'], $orders['id']];
                }
            }
        } catch (RequestException $e) {
            return $e;
            
        }

        return __('something went wrong, please try again later');
    }

    private function calculateHash($obj) {
        if(!$obj) return false;
        
        $amount_cents                           = $obj->amount_cents;
        $created_at                             = $obj->order->created_at;
        $currency                               = $obj->order->currency;
        $error_occured                          = $obj->error_occured;
        $has_parent_transaction                 = $obj->has_parent_transaction;
        $obj_id                                 = $obj->order->id;
        $integration_id                         = $obj->integration_id;
        $is_3d_secure                           = $obj->is_3d_secure;
        $is_auth                                = $obj->is_auth;
        $is_capture                             = $obj->is_capture;
        $is_refunded                            = $obj->is_refunded;
        $is_standalone_payment                  = $obj->is_standalois_capturene_payment;
        $is_voided                              = $obj->is_voided;
        $order_id                               = $obj->order->id;
        $owner                                  = $obj->order->merchant->id;
        $pending                                = $obj->pending;
        $source_data_pan                        = $obj->source_data->pan;
        $source_data_sub_type                   = $obj->source_data->sub_type;
        $source_data_type                       = $obj->source_data->type;
        $success                                = $obj->success;

        $str = $amount_cents.$created_at.$currency.$error_occured.$has_parent_transaction.$obj_id.$integration_id.$is_3d_secure.$is_auth.$is_capture.$is_refunded.$is_standalone_payment.$is_voided.$order_id.$owner.$pending.$source_data_pan.$source_data_sub_type.$source_data_type.$success;

        $secure_hash = $obj->data->secure_hash;

        return hash_hmac('sha512', $str, env('ACCEPT_HMAC_ID')) == $secure_hash ? true : false;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($paymob_order)
    {
        if(! $paymob_order) return [];

        $order = Order::where('paymob_order', $paymob_order)->firstOrFail();

        return new OrderResource($order);
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
    public function update(Request $request)
    {
        $obj = json_decode(json_encode($request->obj));

        if(! $this->calculateHash($obj)) return false;

        // save the transaction data to the server
        Order::where('paymob_order', $obj->order->id)->update([
            "paymob_id" => $obj->id,
            "paymob_pending" => $obj->pending,
            "paymob_success" => $obj->success,
            "paymob_amount" => $obj->amount_cents * 100,
        ]);

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
        
        // return view('paymob::complete');
    }
}
