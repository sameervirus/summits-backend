<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
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
            'governorate_id' => $this->governorate_id
        ];
    }
}
