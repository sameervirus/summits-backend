<?php

namespace App\Http\Resources\Categories;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResources extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'productCount' => 0,
            'image' => [
                "id" => optional($this->getFirstMedia('image'))->id,
                "thumbnail" => optional($this->getFirstMedia('image'))->getFullUrl('thumbnail'),
                "original" => optional($this->getFirstMedia('image'))->getFullUrl(),
            ],
            'children' => CategoryChild::collection($this->when($this->childs->count() > 0, $this->childs))
        ];
    }
}
