<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class GovernorateResource extends JsonResource
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
            'value' => $this->id,
            'label' => App::getLocale() == 'ar' ? $this->governorate_name_ar : $this->governorate_name_en,
            'label_en' => $this->governorate_name_en,
            'cities' => $this->cities
        ];
    }
}
