<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'street' => $this->address,
            'address' => ['formatted_address' => $this->address . ', ' . $this->city->city_name_ar . ', ' . $this->governorate->governorate_name_ar],
            'default' => $this->default ? true : false,
            'city_id' => $this->city_id,
            'governorate_id' => $this->governorate_id,
            'shipping_fees' => optional($this->governorate)->shipping_fees
        ];
    }
}
