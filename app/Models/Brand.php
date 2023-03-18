<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Brand extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];
    protected $appends = ['name', 'address', 'description'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
              ->fit(Manipulations::FIT_CROP, 120, 120)
              ->nonQueued();
        $this->addMediaConversion('cover')
              ->fit(Manipulations::FIT_CROP, 255, 160)
              ->nonQueued();
    }

    public function getNameAttribute()
    {
        return App::getLocale() == 'ar' ? $this->name_arabic : $this->name_english;
    }

    public function getAddressAttribute()
    {
        return App::getLocale() == 'ar' ? $this->address_arabic : $this->address_english;
    }

    public function getDescriptionAttribute()
    {
        return App::getLocale() == 'ar' ? $this->description_arabic : $this->description_english;
    }
}
