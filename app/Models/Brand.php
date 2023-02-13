<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Brand extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
              ->fit(Manipulations::FIT_CROP, 120, 120)
              ->nonQueued();
        $this->addMediaConversion('cover')
              ->fit(Manipulations::FIT_CROP, 1919, 260)
              ->nonQueued();
    }
}
