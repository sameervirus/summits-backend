<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->pivot->name,
            "quantity" => $this->pivot->quantity,
            "price" => $this->pivot->price,
            "image" => [
                'id' => optional($this->getMedia('images')->where('custom_properties.fav', true)->first())->id,
                "thumbnail" => optional($this->getMedia('images')->where('custom_properties.fav', true)->first())->getFullUrl('thumbnail'),
            ]
        ];
    }
}
