<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'description' => $this->whenNotNull($this->description),
            'btnText' => $this->whenNotNull($this->btnText),
            'btnUrl' => $this->slug,
            'slug' => $this->slug,
            'image' => [
                'mobile' => [
                    'url' => optional($this->getFirstMedia($this->position))->getFullUrl($this->position),
                    'width' => $this->getWidthAndHeight($this->position)['width'],
                    'height' => $this->getWidthAndHeight($this->position)['height'],
                ],
                'desktop' => [
                    'url' => optional($this->getFirstMedia($this->position))->getFullUrl($this->position),
                    'width' => $this->getWidthAndHeight($this->position)['width'],
                    'height' => $this->getWidthAndHeight($this->position)['height'],
                ]
            ]
        ];
    }

    private function getWidthAndHeight($position) {
        $data = [];
        switch ($position) {
            case 'hero':
                $data['width'] = 1740;
                $data['height'] = 562;
                break;

            case 'icons':
                $data['width'] = 190;
                $data['height'] = 190;
                break;

            case 'banner1':
                $data['width'] = 840;
                $data['height'] = 240;
                break;

            case 'banner2':
                $data['width'] = 1130;
                $data['height'] = 240;
                break;

            default:
                # code...
                break;
        }

        return $data;
    }
}
