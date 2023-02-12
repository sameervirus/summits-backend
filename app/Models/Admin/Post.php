<?php

namespace s\Admin;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Post extends Model implements HasMedia
{

	use HasMediaTrait;
    protected $appends = ['images'];
    protected $hidden = ["media"];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
              ->width(248)
              ->height(217);
    }

    protected $fillable = [
        'title', 'slug', 'body', 'title_ar', 'body_ar', 'sort_order',
    ];

    public function getImagesAttribute()
    {
        foreach ($this->getMedia('post_img') as $image) {
          $item[] = ['thumb' => $image->getUrl('thumb'), 'image' => $image->getUrl()];
        };
        return $item;
    }

}
