<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            "id" => $this->id,
            "owner_id" => $this->id,
            "owner_name" => $this->name,
            "is_active" => $this->is_active,
            "address" => $this->address,
            "phone" => $this->phone,
            "website" => $this->website,
            "name" => $this->name,
            "name_arabic" => $this->name_arabic,
            "slug" => $this->slug,
            "description" => $this->description,
            "description_arabic" => $this->description_arabic,
            "cover_image" => [
                "id" =>  1,
                "thumbnail" =>  "/assets/images/shop/shop-banner-1.jpg",
                "original" =>  "/assets/images/shop/shop-banner-mobile-1.png"
            ],
            "logo" => [
                "id" =>  1,
                "thumbnail" =>  "/assets/images/shop/shop-logo-1.jpg",
                "original" =>  "/assets/images/shop/shop-logo-1.jpg"
            ],
            "created_at" =>  $this->create_at->format('Y-m-d'),
            "updated_at" =>  $this->updated_at->format('Y-m-d')
        ];
    }
}
