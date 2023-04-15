<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $user = Auth::user();
        $orders = $user->orders()->orderBy('id', 'desc')->get();
        if($orders->count() < 1) return [];
        return OrderResource::collection($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = [
            [
                "id"=> 1,
                "name"=> "Order Received",
                "color"=> "#02B290",
            ],
            [
                "id"=> 2,
                "name"=> "Order placed",
                "color"=> "#02B290",
            ],
            [
                "id"=> 3,
                "name"=> "On the way",
                "color"=> "#FED030",
            ],
            [
                "id"=> 4,
                "name"=> "Delivered",
                "color"=> "#02B290",
            ]
        ];

        DB::table('status')->insert($items);
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
            $user         = Auth::user();
            $total        = $request->total;
            $items        = collect($request->items);
            $payment      = $request->payment_option;
    
            $order = Order::create([
                "payment_gateway" => $payment,
                "total" => $total,
                "notes" => $request->notes,
                "coupon" => $request->coupon,
                "discount" => $request->discount,
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
                $product = Product::find($item['id']);
                $product->quantity -= $item['quantity'];
                $product->save();
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
            } else {
                DB::commit();
                $code = rand(100,999);
                $code .= str_replace('-', '', date('y-m-d'));
                $code .= $user->id;
                $code .= $order->id;
                $order->paymob_order = $code;
                $order->save();
                event(new OrderCreated($order));
                return [
                    "order" => $code,
                    "status" => "success",
                    "payment_option" => "cash-delivery"
                ];
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

    private function calculateHash($json) {
        if(!$json) return false;
        
        $amount_cents                           = $json->obj->amount_cents;
        $created_at                             = $json->obj->created_at;
        $currency                               = $json->obj->order->currency;
        $error_occured                          = $json->obj->error_occured ? 'true' : 'false';
        $has_parent_transaction                 = $json->obj->has_parent_transaction ? 'true' : 'false';
        $obj_id                                 = $json->obj->id;
        $integration_id                         = $json->obj->integration_id;
        $is_3d_secure                           = $json->obj->is_3d_secure ? 'true' : 'false';
        $is_auth                                = $json->obj->is_auth ? 'true' : 'false';
        $is_capture                             = $json->obj->is_capture ? 'true' : 'false';
        $is_refunded                            = $json->obj->is_refunded ? 'true' : 'false';
        $is_standalone_payment                  = $json->obj->is_standalone_payment ? 'true' : 'false';
        $is_voided                              = $json->obj->is_voided ? 'true' : 'false';
        $order_id                               = $json->obj->order->id;
        $owner                                  = $json->obj->owner;
        $pending                                = $json->obj->pending ? 'true' : 'false';
        $source_data_pan                        = $json->obj->source_data->pan;
        $source_data_sub_type                   = $json->obj->source_data->sub_type;
        $source_data_type                       = $json->obj->source_data->type;
        $success                                = $json->obj->success ? 'true' : 'false';


        $str = $amount_cents.$created_at.$currency.$error_occured.$has_parent_transaction.$obj_id.$integration_id.$is_3d_secure.$is_auth.$is_capture.$is_refunded.$is_standalone_payment.$is_voided.$order_id.$owner.$pending.$source_data_pan.$source_data_sub_type.$source_data_type.$success;

        $secure_hash = $json->hmac;

        $hamc = hash_hmac('sha512', $str, env('ACCEPT_HMAC_ID'));

        return $hamc == $secure_hash ? true : false;
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
        $json = json_decode(json_encode($request->all()));

        DB::table('pages')->insert([
            'page' => 'kayla',
            'content' => json_encode($json)
        ]);
        
        if(! $this->calculateHash($json)) return false;

        $order = Order::where('paymob_order', $json->obj->order->id)->first();
        if($json->obj->success == false && $json->obj->pending == false) {
            foreach ($order->products as $item) {
                $product = Product::find($item['id']);
                $product->quantity += $item['quantity'];
                $product->save();
            }
            $order->status_id = 0;
            $order->save();
            // TODO: send email with transaction failure
            return false;
        }

        // TODO: send email with transaction id after payment completion

        // save the transaction data to the server
        
        $order->paymob_id = $json->obj->id;
        $order->paymob_pending = $json->obj->pending;
        $order->paymob_success = $json->obj->success;
        $order->paymob_amount = $json->obj->amount_cents / 100;
        $order->save();
        event(new OrderCreated($order));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
  
        if($order->status_id != 1) {
            return response()->json([
                'message' => 'Order cannot be canceled',
            ], 400);
        }

        if($order->payment_gateway == 'credit-card') {
            
            $token = Http::post('https://accept.paymob.com/api/auth/tokens', [
                "api_key" => env('ACCEPT_API_KEY') 
            ]);
            
            if($token && $order->paymob_id) {

                $refund = Http::post('https://accept.paymob.com/api/acceptance/void_refund/refund', [
                    "auth_token" => $token['token'],
                    "transaction_id" => $order->paymob_id,
                    "amount_cents" => $order->paymob_amount * 100
                ]);

                if(!@$refund || !@$refund['success']) {
                    return response()->json([
                        'message' => 'Please contact our customer service for more information',
                    ], 400);
                } else {
                    DB::table('refunds')->insert([
                        'user_id' => Auth::id(),
                        'order_id' => $order->paymob_order,
                        'order' => json_encode($order),
                        'refund_response' => json_encode($refund),
                    ]);
                }
            }
        }
        foreach ($order->products as $item) {
            $product = Product::find($item['id']);
            $product->quantity += $item['quantity'];
            $product->save();
        }
        // $order->products()->detach();
        $order->status_id = 0;
        if($order->save()) {
            // TODO: send email order has been successfully canceled
            return response()->json([
                'message' => 'Order canceled successfully',
            ], 201);
        }

        return response()->json([
            'message' => 'server error',
        ], 500);
    }

}
