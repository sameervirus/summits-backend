<?php

namespace App\Http\Resources\Categories;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

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
            'name' => App::getLocale() == 'ar' ? $this->name_arabic : $this->name,
            'name_arabic' => $this->name_arabic,
            'productCount' => 0,
            'image' => [],
            'children' => CategoryChild::collection($this->when($this->childs->count() > 0, $this->childs))
        ];
    }
}
