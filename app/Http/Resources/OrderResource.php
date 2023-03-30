<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public function __construct($resource) { self::withoutWrapping(); parent::__construct($resource); }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            "tracking_number"=> $this->paymob_order,
            "customer" => [
                "id" => $this->user_id,
                "email" => $this->email
            ],
            "total" => $this->paymob_amount ?? $this->total,
            "shipping_fee" => $this->shipping_fee,
            "delivery_fee" => $this->shipping_fee,
            "amount" => ($this->paymob_amount ?? $this->total) - $this->shipping_fee,
            "payment_gateway" => $this->payment_gateway,
            "products" => OrderProductResource::collection($this->products),
            "note" => $this->notes,
            "shipping_address" => $this->address,
            "discount" => $this->discount,
            "delivery_time" => optional($this->delivery_time)->format('Y-m-d'),
            "status" => $this->status,
            "created_at" => $this->created_at->format('Y-m-d'),
        ];
    }
}
