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
                "id" =>  optional($this->getFirstMedia('banners'))->id,
                "thumbnail" =>  optional($this->getFirstMedia('banners'))->getFullUrl(),
                "original" =>  optional($this->getFirstMedia('banners'))->getFullUrl()
            ],
            "logo" => [
                "id" =>  optional($this->getFirstMedia('logos'))->id,
                "thumbnail" =>  optional($this->getFirstMedia('logos'))->getFullUrl(),
                "original" =>  optional($this->getFirstMedia('logos'))->getFullUrl()
            ],
            "created_at" =>  $this->created_at->format('Y-m-d'),
            "updated_at" =>  $this->updated_at->format('Y-m-d')
        ];
    }
}
