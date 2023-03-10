<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
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
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'quantity'=>$this->quantity,
            'price' => (float)$this->price,
            'sale_price' => (float)$this->sale_price,
            'brand' => $this->brand->name,
            'weight' => $this->weight,
            'unit' => $this->unit,
            'product_type' => $this->product_type,
            'tag' => $this->tags,
            'variations' => $this->when($this->product_type == 'variable', [
                [
                    "id" => 8,
                    "attribute_id" => 3,
                    "value" => "12oz",
                    "attribute" => [
                      "id" => 3,
                      "slug" => "available-in",
                      "name" => "Available In",
                      "values" => [
                        [
                          "id"  => 8,
                          "attribute_id" => 3,
                          "value" => "12oz"
                        ]
                      ]
                    ]
                ]
            ]),
            'image' => [
                'id' => optional($this->getMedia('images')->where('custom_properties.fav', true)->first())->id,
                'original' => optional($this->getMedia('images')->where('custom_properties.fav', true)->first())->getFullUrl('original'),
                'thumbnail' => optional($this->getMedia('images')->where('custom_properties.fav', true)->first())->getFullUrl('thumbnail'),
            ],
            'gallery' => ProductGalleryResource::collection($this->getMedia('images'))
        ];
    }
}
